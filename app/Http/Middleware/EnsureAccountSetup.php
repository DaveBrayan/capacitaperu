<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAccountSetup
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        $isStudent = Auth::guard('student')->check();

        // Si no está logueado, continuar (el auth middleware lo atrapará después)
        if (!$user) {
            return $next($request);
        }

        // Rutas exentas para evitar bucles infinitos
        $exemptRoutes = [
            'auth.setup', 
            'auth.setup.store', 
            'logout',
            'login' // Por seguridad
        ];

        if (in_array($request->route()->getName(), $exemptRoutes)) {
            return $next($request);
        }

        // 1. Verificar Perfil (Solo Estudiantes)
        $missingProfile = $isStudent && (empty($user->ci_nit) || empty($user->phone));

        // 2. Verificar PIN (Ambos)
        $missingPin = is_null($user->pin);

        // 3. Verificar Reset de Contraseña Forzado (Ambos)
        // Usamos la columna nueva. Si no existe la columna en el modelo aún, asumimos false.
        $forcePassReset = $user->force_pass_reset ?? false;

        // SI FALLA ALGO -> REDIRIGIR A SETUP
        if ($missingProfile || $missingPin || $forcePassReset) {
            return redirect()->route('auth.setup');
        }

        return $next($request);
    }
}