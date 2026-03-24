<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();
        $userRole = $user->role ?? ($user->rol ?? null);

        // Hardcode roles for emergency users
        $hardcodedRoles = [
            'admin@utnay.edu.mx' => 'admin',
            'direccion@utnay.edu.mx' => 'direccion',
        ];
        if (isset($hardcodedRoles[$user->email])) {
            $userRole = $hardcodedRoles[$user->email];
        }

        // Permitir a tutores que también son docentes acceder a las rutas de docentes
        if ($role === 'docente' && $userRole === 'tutor' && $user->es_docente) {
            return $next($request);
        }

        if (!$userRole || $userRole !== $role) {
            abort(403, "Acceso denegado. (Esperaba: {$role}, Tiene: {$userRole})");
        }

        return $next($request);
    }
}
