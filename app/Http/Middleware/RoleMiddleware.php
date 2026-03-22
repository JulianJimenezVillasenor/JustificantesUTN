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

        if (!$userRole || $userRole !== $role) {
            abort(403, 'Acceso denegado.');
        }

        return $next($request);
    }
}
