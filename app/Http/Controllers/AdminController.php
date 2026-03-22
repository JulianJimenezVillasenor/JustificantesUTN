<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Justificante;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use PDF; // Asumiendo que usas dompdf o similar

class AdminController extends Controller
{
    public function index()
    {
        return view('admin');
    }

    public function createUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|string|in:alumno,tutor,docente',
            'tutor_id' => 'nullable|integer|exists:users,id',
            'docentes' => 'nullable|array',
            'docentes.*' => 'integer|exists:users,id',
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ];

        // Si es alumno y se asigna tutor
        if ($request->role === 'alumno' && $request->tutor_id) {
            $userData['tutor_id'] = $request->tutor_id;
        }

        $user = User::create($userData);

        // Si es alumno y se asignan docentes
        if ($request->role === 'alumno' && $request->has('docentes')) {
            $docentes = $request->docentes;
            foreach ($docentes as $docenteId) {
                DB::table('docente_alumno')->insert([
                    'docente_id' => $docenteId,
                    'alumno_id' => $user->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return redirect()->back()->with('success', 'Usuario creado exitosamente.');
    }

    public function generateReport()
    {
        $justificantes = Justificante::with('user')->get();
        $pdf = PDF::loadView('reporte_inasistencias', compact('justificantes'));
        return $pdf->download('reporte_inasistencias.pdf');
    }
}
