<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        $user = User::where('email', $email)->first();

        if (!$user && in_array($email, ['admin@utnay.edu.mx', 'direccion@utnay.edu.mx'])) {
            $defaultRoles = [
                'admin@utnay.edu.mx' => 'admin',
                'direccion@utnay.edu.mx' => 'direccion',
            ];

            $user = User::create([
                'name' => $defaultRoles[$email] === 'admin' ? 'Administrador' : 'Dirección',
                'email' => $email,
                'password' => Hash::make('123'),
            ]);
        }

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado.'
            ], 404);
        }

        if (!Hash::check($password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Contraseña incorrecta.'
            ], 401);
        }

        Auth::login($user);
        session()->save();

        $userRole = $user->role ?? ($user->rol ?? null);

        $hardcodedRoles = [
            'admin@utnay.edu.mx' => 'admin',
            'direccion@utnay.edu.mx' => 'direccion',
        ];
        if (isset($hardcodedRoles[$email])) {
            $userRole = $hardcodedRoles[$email];
        }

        if (!$userRole) {
            return response()->json([
                'success' => false,
                'message' => 'No se ha definido el rol del usuario. Contacta al administrador.'
            ], 403);
        }

        $routeMap = [
            'alumno' => 'alumno.index',
            'tutor' => 'tutor.index',
            'docente' => 'docente.index',
            'admin' => 'admin.index',
            'direccion' => 'direccion.index',
        ];

        if (!isset($routeMap[$userRole])) {
            return response()->json([
                'success' => false,
                'message' => 'Rol no válido.'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'role' => ucfirst($userRole),
            'redirect' => route($routeMap[$userRole])
        ]);
    }
}
