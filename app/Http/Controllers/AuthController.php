<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File; 
use App\Models\User;
use App\Models\Student; // <--- Importante para buscar estudiantes en login con PIN

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check() || Auth::guard('student')->check()) {
            return $this->redirectUser();
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $loginType = $request->input('login_type');

        // --- CASO 1: LOGIN CON PIN ---
        if ($loginType === 'pin') {
            return $this->loginWithPin($request);
        }

        // --- CASO 2: LOGIN CON CONTRASEÑA (Standard) ---
        
        // A. Intentar como Staff (Web)
        if (Auth::guard('web')->attempt($credentials, $request->remember)) {
            $user = Auth::guard('web')->user();
            return $this->checkSetupAndRedirect($user);
        }

        // B. Intentar como Estudiante
        if (Auth::guard('student')->attempt($credentials, $request->remember)) {
            $user = Auth::guard('student')->user();
            if ($user->status !== 'active') {
                Auth::guard('student')->logout();
                return back()->withErrors(['email' => 'Tu cuenta está inactiva o suspendida.']);
            }
            return $this->checkSetupAndRedirect($user);
        }

        return back()->withErrors(['email' => 'Credenciales incorrectas.']);
    }

    /**
     * Lógica personalizada para verificar el PIN hash con autocorrección
     */
    protected function loginWithPin(Request $request)
    {
        $email = $request->input('email');
        $pinInput = $request->input('password'); // La vista envía el PIN en el campo 'password'

        // 1. Buscar usuario en Staff
        $user = User::where('email', $email)->first();
        $guard = 'web';

        // 2. Si no es Staff, buscar en Estudiantes
        if (!$user) {
            $user = Student::where('email', $email)->first();
            $guard = 'student';
        }

        // 3. Verificar Usuario y PIN
        if ($user) {
            // Si no tiene PIN configurado
            if (empty($user->pin)) {
                return back()->withErrors(['email' => 'Este usuario no tiene un PIN configurado. Ingresa con contraseña.']);
            }

            $isValid = false;

            try {
                // Intentar verificación estándar (Hash)
                if (Hash::check($pinInput, $user->pin)) {
                    $isValid = true;
                }
            } catch (\Exception $e) {
                // Si falla (ej: el PIN en BD es texto plano "1234"), probamos comparación directa
                if ($pinInput === $user->pin) {
                    $isValid = true;
                    // AUTO-FIX: Encriptar el PIN para la próxima vez
                    $user->pin = Hash::make($pinInput);
                    $user->save();
                }
            }

            if ($isValid) {
                // Login manual exitoso
                Auth::guard($guard)->login($user, $request->remember);

                // Verificación extra de estado activo para estudiantes
                if ($guard === 'student' && $user->status !== 'active') {
                    Auth::guard('student')->logout();
                    return back()->withErrors(['email' => 'Tu cuenta está inactiva o suspendida.']);
                }

                return $this->checkSetupAndRedirect($user);
            }
        }

        return back()->withErrors(['email' => 'PIN incorrecto o usuario no encontrado.']);
    }

    public function logout(Request $request)
    {
        if (Auth::guard('student')->check()) {
            Auth::guard('student')->logout();
        } else {
            Auth::logout();
        }
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    // --- SETUP Y CREDENCIALES ---

    public function showSetup()
    {
        $user = $this->getCurrentUser();
        
        // Si no hay nada que configurar (Tiene PIN y NO requiere cambio pass), lo sacamos de aquí
        if (!empty($user->pin) && !$user->force_pass_reset) {
             // Verificación extra para estudiantes (perfil completo)
             if (Auth::guard('student')->check() && (!empty($user->ci_nit) && !empty($user->phone))) {
                 return $this->redirectUser();
             }
        }
        
        // Escanear carpeta de avatares locales para estudiantes
        $localAvatars = [];
        if (Auth::guard('student')->check()) {
            $possiblePaths = [
                public_path('img/avatars/students'),
                base_path('public/img/avatars/students'),
                base_path('../public_html/img/avatars/students'),
                base_path('public_html/img/avatars/students'),
            ];
            
            $path = null;
            foreach ($possiblePaths as $p) {
                if (File::exists($p)) {
                    $path = $p;
                    break;
                }
            }

            if ($path) {
                $files = File::files($path);
                foreach ($files as $file) {
                    if (in_array(strtolower($file->getExtension()), ['png', 'jpg', 'jpeg', 'svg', 'webp'])) {
                        $localAvatars[] = 'img/avatars/students/' . $file->getFilename();
                    }
                }
            }
        }

        return view('auth.setup', compact('localAvatars'));
    }

    public function updateCredentials(Request $request)
    {
        $user = $this->getCurrentUser();
        $isStudent = Auth::guard('student')->check();

        // --- Lógica de Validación Condicional ---
        $missingProfile = $isStudent && (empty($user->ci_nit) || empty($user->phone));
        $missingPin = is_null($user->pin);
        $forcePass = $user->force_pass_reset ?? false;

        $showProfile = $missingProfile;
        $showPin = $missingPin;
        $showPassword = $forcePass || $missingProfile; 

        $rules = [];

        if ($showPassword) {
            $rules['password'] = 'required|confirmed';
        }
        if ($showPin) {
            $rules['pin'] = 'required|digits:4';
        }
        if ($showProfile) {
            $rules['phone'] = 'required|string|max:20';
            $rules['ci_nit'] = 'required|string|max:20';
            $rules['country'] = 'required|string|max:100';
            $rules['city'] = 'required|string|max:100';
            $rules['avatar'] = 'nullable|string';
        }

        $request->validate($rules);

        // --- Actualización ---
        if ($showPassword && $request->filled('password')) {
            $user->password = Hash::make($request->password);
            $user->force_pass_reset = false; 
        }

        if ($showPin && $request->filled('pin')) {
            // Guardamos el PIN encriptado
            $user->pin = Hash::make($request->pin); 
        }

        if ($showProfile) {
            $user->phone = $request->phone;
            $user->ci_nit = $request->ci_nit;
            $user->country = $request->country;
            $user->city = $request->city;
            
            if ($request->filled('avatar')) {
                $user->avatar_url = $request->avatar;
            }
        }

        $user->save();

        return $this->redirectUser()->with('success', '¡Cuenta configurada correctamente!');
    }

    // --- Helpers ---

    private function getCurrentUser()
    {
        return Auth::user() ?? Auth::guard('student')->user();
    }

    private function checkSetupAndRedirect($user)
    {
        // 1. Chequeo de Perfil (Solo estudiantes)
        $isStudent = $user instanceof Student; 
        
        $missingProfile = $isStudent && (empty($user->ci_nit) || empty($user->phone));
        
        // 2. Chequeo de PIN
        $missingPin = empty($user->pin);
        
        // 3. Chequeo de Reset Forzado
        $forcePass = $user->force_pass_reset ?? false;

        if ($missingProfile || $missingPin || $forcePass) {
            return redirect()->route('auth.setup');
        }
        return $this->redirectUser();
    }

    private function redirectUser()
    {
        if (Auth::guard('student')->check()) {
            return redirect()->route('student.dashboard');
        }
        return redirect()->route('admin.dashboard');
    }
}