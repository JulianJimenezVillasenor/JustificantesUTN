<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'name' => 'Julian Jimenez',
                'email' => 'alumno@utnay.edu.mx',
                'password' => Hash::make('123'),
                'role' => 'alumno',
            ],
            [
                'name' => 'Maestro de Prueba',
                'email' => 'tutor@utnay.edu.mx',
                'password' => Hash::make('123'),
                'role' => 'tutor',
            ],
            [
                'name' => 'Docente de Sistemas',
                'email' => 'docente@utnay.edu.mx',
                'password' => Hash::make('123'),
                'role' => 'docente',
            ],
            [
                'name' => 'Administrador',
                'email' => 'admin@utnay.edu.mx',
                'password' => Hash::make('123'),
                'role' => 'admin',
            ],
            [
                'name' => 'Dirección',
                'email' => 'direccion@utnay.edu.mx',
                'password' => Hash::make('123'),
                'role' => 'direccion',
            ],
        ];

        foreach ($users as $userData) {
            DB::table('users')->updateOrInsert(
                ['email' => $userData['email']],
                $userData
            );
        }
    }
}
