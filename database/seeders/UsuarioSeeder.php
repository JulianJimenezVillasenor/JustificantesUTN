<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            // Usuario Alumno
            [
                'name'     => 'Julian Jimenez',
                'email'    => 'alumno@utnay.edu.mx',
                'password' => Hash::make('123'),
                'rol'      => 'alumno',
                'grupo'    => 'DSM-52',
                'created_at' => now(),
            ],
            // Usuario Tutor
            [
                'name'     => 'Maestro de Prueba',
                'email'    => 'tutor@utnay.edu.mx',
                'password' => Hash::make('123'),
                'rol'      => 'tutor',
                'grupo'    => null,
                'created_at' => now(),
            ],
            // Usuario Docente
            [
                'name'     => 'Docente de Sistemas',
                'email'    => 'docente@utnay.edu.mx',
                'password' => Hash::make('123'),
                'rol'      => 'docente',
                'grupo'    => null,
                'created_at' => now(),
            ],
        ]);
    }
}
