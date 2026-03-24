<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Justificante;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PDF; // Asumiendo que usas dompdf o similar

class AdminController extends Controller
{
    public function index()
    {
        $usuarios_recientes = User::orderBy('id', 'desc')->paginate(10);
        return view('admin', compact('usuarios_recientes'));
    }

    public function createUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|string|in:alumno,tutor,docente',
            'grupo' => 'nullable|string|max:50',
            'es_docente' => 'nullable|boolean',
            'tutor_id' => 'nullable|integer|exists:users,id',
            'materias' => 'nullable|array',
            'materias.*.docente_id' => 'required_with:materias|integer|exists:users,id',
            'materias.*.nombre' => 'required_with:materias|string|max:255',
            'materias.*.horario' => 'nullable|string|max:100',
            'firma' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ];

        if ($request->hasFile('firma')) {
            $userData['firma_path'] = $request->file('firma')->store('firmas', 'public');
        }

        if ($request->role === 'alumno' && $request->grupo) {
            $userData['grupo'] = $request->grupo;
        }

        if ($request->role === 'tutor' && $request->has('es_docente')) {
            $userData['es_docente'] = $request->es_docente;
        }

        // Si es alumno y se asigna tutor
        if ($request->role === 'alumno' && $request->tutor_id) {
            $userData['tutor_id'] = $request->tutor_id;
        }

        $user = User::create($userData);

        // Si es alumno y se asignan materias (Carga Horaria)
        if ($request->role === 'alumno' && $request->has('materias')) {
            foreach ($request->materias as $materiaData) {
                if (isset($materiaData['docente_id']) && isset($materiaData['nombre'])) {
                    DB::table('docente_alumno')->insert([
                        'docente_id' => $materiaData['docente_id'],
                        'alumno_id' => $user->id,
                        'materia' => $materiaData['nombre'],
                        'horario' => $materiaData['horario'] ?? null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Usuario y Carga Horaria creados exitosamente.');
    }

    public function generateReport()
    {
        $justificantes = Justificante::with('user')->get();
        $pdf = PDF::loadView('reporte_inasistencias', compact('justificantes'));
        return $pdf->download('reporte_inasistencias.pdf');
    }

    public function destroyUser($id)
    {
        $user = User::findOrFail($id);

        // Evitar que el administrador se elimine a sí mismo accidentalmente
        if (auth()->id() === $user->id) {
            return redirect()->back()->withErrors(['Error' => 'No puedes eliminar tu propia cuenta de administrador.']);
        }

        $user->delete();

        return redirect()->back()->with('success', 'Usuario eliminado exitosamente.');
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);

        // Si es alumno, obtener su carga horaria actual
        $carga_horaria = [];
        if ($user->role === 'alumno') {
            $carga_horaria = DB::table('docente_alumno')
                ->where('alumno_id', $user->id)
                ->get()
                ->toArray();
        }

        return view('admin_edit', compact('user', 'carga_horaria'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8',
            'grupo' => 'nullable|string|max:50',
            'es_docente' => 'nullable|boolean',
            'tutor_id' => 'nullable|integer|exists:users,id',
            'materias' => 'nullable|array',
            'materias.*.docente_id' => 'required_with:materias|integer|exists:users,id',
            'materias.*.nombre' => 'required_with:materias|string|max:255',
            'materias.*.horario' => 'nullable|string|max:100',
            'firma' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('firma')) {
            if ($user->firma_path && Storage::disk('public')->exists($user->firma_path)) {
                Storage::disk('public')->delete($user->firma_path);
            }
            $user->firma_path = $request->file('firma')->store('firmas', 'public');
        }

        if ($user->role === 'alumno') {
            $user->grupo = $request->grupo;
            $user->tutor_id = $request->tutor_id;

            // Actualizar Carga Horaria
            // Primero eliminamos la actual
            DB::table('docente_alumno')->where('alumno_id', $user->id)->delete();

            // Insertamos la nueva
            if ($request->has('materias')) {
                foreach ($request->materias as $materiaData) {
                    if (isset($materiaData['docente_id']) && isset($materiaData['nombre'])) {
                        DB::table('docente_alumno')->insert([
                            'docente_id' => $materiaData['docente_id'],
                            'alumno_id' => $user->id,
                            'materia' => $materiaData['nombre'],
                            'horario' => $materiaData['horario'] ?? null,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        } elseif ($user->role === 'tutor') {
            $user->es_docente = $request->has('es_docente') ? 1 : 0;
        }

        $user->save();

        return redirect()->route('admin.index')->with('success', 'Usuario actualizado correctamente.');
    }
}
