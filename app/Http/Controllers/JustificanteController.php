<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;

class JustificanteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    // Dentro de JustificanteController.php

    public function verPDF($id)
    {
        $justificante = DB::table('justificantes as j')
            ->join('users as u', 'j.user_id', '=', 'u.id')
            ->leftJoin('users as t', 'j.tutor_id', '=', 't.id') // Para traer el nombre del tutor
            ->select('j.*', 'u.name as nombre_alumno', 'u.grupo', 't.name as nombre_tutor', 't.firma_path as firma_tutor')
            ->where('j.id', $id)
            ->first();

        if (!$justificante) {
            return redirect()->back()->with('error', 'Justificante no encontrado.');
        }

        if ($justificante->status !== 'ACEPTADO') {
            return redirect()->back()->with('error', 'El justificante aún no ha sido autorizado por el tutor y no puede descargarse.');
        }

        // Opcional: Configurar Carbon en español para la fecha
        \Carbon\Carbon::setLocale('es');

        // Obtener las materias y sus horarios correspondientes
        $materias = DB::table('justificante_materias as jm')
            ->leftJoin('docente_alumno as da', function($join) use ($justificante) {
                $join->on('jm.materia', '=', 'da.materia')
                     ->where('da.alumno_id', '=', $justificante->user_id);
            })
            ->where('jm.justificante_id', $id)
            ->select('jm.materia', 'da.horario')
            ->get();

        return view('PDF_Justificante', compact('justificante', 'materias'));
    }

    public function validarPublico($id)
    {
        $justificante = DB::table('justificantes as j')
            ->join('users as u', 'j.user_id', '=', 'u.id')
            ->select('j.*', 'u.name as nombre_alumno', 'u.grupo', 'u.rol')
            ->where('j.id', $id)
            ->first();

        if (!$justificante) {
            return "Error: Justificante no encontrado.";
        }

        return view('ValidarQR', compact('justificante'));
    }

    public function descargarPDF($id)
    {
        $justificante = DB::table('justificantes as j')
            ->join('users as u', 'j.user_id', '=', 'u.id')
            ->leftJoin('users as t', 'j.tutor_id', '=', 't.id')
            ->select('j.*', 'u.name as nombre_alumno', 'u.grupo', 't.name as nombre_tutor', 't.firma_path as firma_tutor')
            ->where('j.id', $id)
            ->first();

        if (!$justificante) {
            return redirect()->back()->with('error', 'Justificante no encontrado.');
        }

        if ($justificante->status !== 'ACEPTADO') {
            return redirect()->back()->with('error', 'El justificante aún no ha sido autorizado por el tutor y no puede imprimirse.');
        }

        // Obtener las materias y sus horarios correspondientes
        $materias = DB::table('justificante_materias as jm')
            ->leftJoin('docente_alumno as da', function($join) use ($justificante) {
                $join->on('jm.materia', '=', 'da.materia')
                     ->where('da.alumno_id', '=', $justificante->user_id);
            })
            ->where('jm.justificante_id', $id)
            ->select('jm.materia', 'da.horario')
            ->get();

        // Generamos el PDF usando la vista que creamos arriba
        $pdf = Pdf::loadView('PDF_Justificante', compact('justificante', 'materias'));

        // Retorna el PDF para visualizar o descargar
        return $pdf->stream('Justificante_' . $justificante->id . '.pdf');
    }

    // Docente
    public function indexDocente()
    {
        $docenteId = Auth::id();

        // Obtenemos los justificantes (por materia) aceptados por el tutor para este docente
        $justificantes = DB::table('justificantes as j')
            ->join('users as u', 'j.user_id', '=', 'u.id')
            ->join('justificante_materias as jm', 'j.id', '=', 'jm.justificante_id')
            ->where('jm.docente_id', $docenteId)
            ->where('j.status', 'ACEPTADO')
            ->select('j.*', 'u.name as nombre_alumno', 'u.grupo', 'jm.materia', 'jm.firma_docente as firma_materia', 'jm.id as jm_id', 'jm.fecha_firma_docente as jm_fecha_firma')
            ->orderBy('jm.firma_docente', 'asc')
            ->orderBy('j.created_at', 'desc')
            ->get();

        return view('Docente', compact('justificantes'));
    }

    public function firmarDocente($jm_id)
    {
        $docenteId = Auth::id();

        // Verificar que el registro de la materia pertenece a este docente y justificante
        $jm = DB::table('justificante_materias')->where('id', $jm_id)->where('docente_id', $docenteId)->first();

        if (!$jm) {
            return back()->with('error', 'No tienes permisos para firmar esta materia.');
        }

        DB::table('justificante_materias')->where('id', $jm_id)->update([
            'firma_docente' => true,
            'fecha_firma_docente' => now(),
        ]);

        return back()->with('success', 'Materia firmada correctamente.');
    }

    // Tutor
    public function indexTutor(Request $request)
    {
        $tutorId = Auth::id();

        $query = DB::table('justificantes as j')
            ->join('users as u', 'j.user_id', '=', 'u.id')
            ->where('u.tutor_id', $tutorId)
            ->select('j.*', 'u.name as nombre_alumno', 'u.grupo');

        // Lógica del buscador
        if ($request->has('buscar')) {
            $buscar = $request->buscar;
            $query->where(function ($q) use ($buscar) {
                $q->where('u.name', 'like', "%$buscar%")
                    ->orWhere('j.motivo', 'like', "%$buscar%")
                    ->orWhere('j.id', 'like', "%$buscar%");
            });
        }

        $justificantes = $query->orderBy('j.created_at', 'desc')->get();

        return view('Tutor', compact('justificantes'));
    }

    // Cambiar estatus (Aceptar/Rechazar)
    public function updateStatus(Request $request, $id)
    {
        $tutorId = Auth::id();

        // Verificar que el justificante pertenezca a un alumno del tutor
        $justificante = DB::table('justificantes as j')
            ->join('users as u', 'j.user_id', '=', 'u.id')
            ->where('j.id', $id)
            ->where('u.tutor_id', $tutorId)
            ->first();

        if (!$justificante) {
            return back()->with('error', 'No tienes permisos para actualizar este justificante.');
        }

        DB::table('justificantes')->where('id', $id)->update([
            'status' => $request->nuevo_estatus,
            'updated_at' => now()
        ]);

        $mensaje = $request->nuevo_estatus == 'ACEPTADO'
            ? 'Justificante aprobado. El alumno ya tiene su QR activo.'
            : 'Justificante rechazado.';

        return back()->with('success', $mensaje);
    }


    public function index()
    {
        $alumnoId = Auth::id();
        $justificantes = \App\Models\Justificante::with('materias')->where('user_id', $alumnoId)->orderBy('created_at', 'desc')->get();

        // Obtener las materias asignadas al alumno
        $materias = DB::table('docente_alumno')
            ->where('alumno_id', $alumnoId)
            ->select('materia', 'horario')
            ->get();

        // Generar el código QR para el último justificante aceptado del alumno
        $ultimoAceptado = DB::table('justificantes')
            ->where('user_id', $alumnoId)
            ->where('status', 'ACEPTADO')
            ->latest()
            ->first();

        $qrCode = null;
        if ($ultimoAceptado) {
            // El QR contendrá un texto de validación o una URL
            $datosQr = "Validación UT Nayarit\nFolio: #{$ultimoAceptado->id}\nTipo: {$ultimoAceptado->tipo_falta}\nEstatus: VALIDADO";

            $qrCode = QrCode::size(150)->generate($datosQr);

        }

        return view('Alumno', compact('justificantes', 'qrCode', 'materias'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // ... validación de archivos ...

        if ($request->hasFile('evidencia')) {
            $path = $request->file('evidencia')->store('evidencias', 'public');
        }

        $horas = null;
        if ($request->tipo_justificante == 'Parcial' && $request->has('materias')) {
            $horas = implode(', ', $request->materias);
        } elseif ($request->tipo_justificante == 'Completa') {
            $horas = 'Jornada Completa';
        }

        // El insert debe llevar el user_id del usuario autenticado
        $justificanteId = DB::table('justificantes')->insertGetId([
            'user_id' => Auth::id(),
            'tipo_falta' => $request->tipo_falta,
            'tipo_justificante' => $request->tipo_justificante,
            'fecha' => $request->fecha,
            'horas' => $horas,
            'motivo' => $request->motivo,
            'evidencia_path' => $path ?? null,
            'status' => 'PENDIENTE',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Insertar materias y asignar docentes correspondientes
        if ($request->tipo_justificante == 'Parcial' && $request->has('materias')) {
            foreach ($request->materias as $materiaNombre) {
                $docenteAsignado = DB::table('docente_alumno')
                    ->where('alumno_id', Auth::id())
                    ->where('materia', $materiaNombre)
                    ->first();

                if ($docenteAsignado) {
                    DB::table('justificante_materias')->insert([
                        'justificante_id' => $justificanteId,
                        'materia' => $materiaNombre,
                        'docente_id' => $docenteAsignado->docente_id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        } elseif ($request->tipo_justificante == 'Completa') {
            $materiasAlumno = DB::table('docente_alumno')
                ->where('alumno_id', Auth::id())
                ->get();

            foreach ($materiasAlumno as $asignacion) {
                DB::table('justificante_materias')->insert([
                    'justificante_id' => $justificanteId,
                    'materia' => $asignacion->materia,
                    'docente_id' => $asignacion->docente_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return back()->with('success', 'Solicitud enviada correctamente.');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
