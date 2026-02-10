<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        // SIMULACIÓN DE USUARIOS
        $usuarios = [
            ['email' => 'docente@utnay.edu.mx', 'pass' => '123', 'role' => 'docente'],
            ['email' => 'tutor@utnay.edu.mx',   'pass' => '123', 'role' => 'tutor'],
            ['email' => 'alumno@utnay.edu.mx',  'pass' => '123', 'role' => 'alumno'],
        ];

        foreach ($usuarios as $user) {
            if ($user['email'] === $email && $user['pass'] === $password) {
                return response()->json([
                    'success' => true,
                    'redirect' => route($user['role']),
                    'role' => ucfirst($user['role'])
                ]);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Acceso denegado. Verifica tus datos.'
        ], 401);
    }
}
