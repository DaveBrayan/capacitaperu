<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File; 

class ProfileController extends Controller
{
    public function show()
    {
        $student = Auth::guard('student')->user();
        
        // Cargar avatares locales para el modal de selección
        $localAvatars = [];
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

        return view('student.profile', compact('student', 'localAvatars'));
    }

    public function update(Request $request)
    {
        $student = Auth::guard('student')->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'ci_nit' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'bio' => 'nullable|string|max:500',
            'avatar' => 'nullable|string' // Validamos como string (ruta), no como imagen
        ]);

        $student->name = $request->name;
        $student->phone = $request->phone;
        $student->ci_nit = $request->ci_nit;
        $student->country = $request->country;
        $student->city = $request->city;
        $student->bio = $request->bio;

        // Actualizar avatar si se seleccionó uno nuevo
        if ($request->filled('avatar')) {
            $student->avatar_url = $request->avatar;
        }

        $student->save();

        return redirect()->back()->with('success', 'Perfil actualizado correctamente.');
    }

    public function updateSecurity(Request $request)
    {
        $student = Auth::guard('student')->user();
        
        $request->validate([
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
            'pin' => 'nullable|digits:4'
        ]);

        // Cambiar Contraseña
        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $student->password)) {
                return back()->withErrors(['current_password' => 'La contraseña actual no es correcta.']);
            }
            $student->password = Hash::make($request->new_password);
        }

        // Cambiar PIN
        if ($request->filled('pin')) {
            $student->pin = $request->pin;
        }

        $student->save();

        return redirect()->back()->with('success', 'Credenciales de seguridad actualizadas.');
    }
}