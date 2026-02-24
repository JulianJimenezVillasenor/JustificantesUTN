<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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
            ->select('j.*', 'u.name as nombre_alumno', 'u.grupo', 't.name as nombre_tutor')
            ->where('j.id', $id)
            ->first();

        if (!$justificante) {
            return redirect()->back()->with('error', 'Justificante no encontrado.');
        }

        // Opcional: Configurar Carbon en español para la fecha
        \Carbon\Carbon::setLocale('es');

        return view('PDF_Justificante', compact('justificante'));
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

    //PDF
    public function descargarPDF($id)
    {
        $justificante = DB::table('justificantes')->where('id', $id)->first();

        // Generamos el PDF usando la vista que creamos arriba
        $pdf = Pdf::loadView('pdf.justificante', compact('justificante'));

        // Retorna el PDF para visualizar o descargar
        return $pdf->stream('Justificante_'.$justificante->id.'.pdf');
    }

    // Docente
    public function indexDocente()
    {
        // Obtenemos todos los justificantes que ya fueron aceptados por el tutor
        // pero que el docente aún necesita visualizar/firmar
        $justificantes = DB::table('justificantes as j')
            ->join('users as u', 'j.user_id', '=', 'u.id')
            ->select('j.*', 'u.name as nombre_alumno', 'u.grupo')
            ->where('j.status', 'ACEPTADO') 
            ->orderBy('j.firma_docente', 'asc')
            ->orderBy('j.created_at', 'desc')
            ->get();

        return view('Docente', compact('justificantes'));
    }

    public function firmarDocente($id)
    {
        DB::table('justificantes')->where('id', $id)->update([
            'firma_docente' => true,
            'fecha_firma_docente' => now(),
        ]);

        return back()->with('success', 'Justificante firmado correctamente.');
    }

    // Tutor 
    public function indexTutor(Request $request)
    {
            $query = DB::table('justificantes as j')
            ->join('users as u', 'j.user_id', '=', 'u.id')
            ->select('j.*', 'u.name as nombre_alumno', 'u.grupo');

        // Lógica del buscador
        if ($request->has('buscar')) {
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar) {
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
        $justificantes = DB::table('justificantes')->orderBy('created_at', 'desc')->get();
    
        // Generar el código QR para el último justificante aceptado
        $ultimoAceptado = DB::table('justificantes')
                            ->where('status', 'ACEPTADO')
                            ->latest()
                            ->first();
        
        $qrCode = null;
        if ($ultimoAceptado) {
            // El QR contendrá un texto de validación o una URL
            $datosQr = "Validación UT Nayarit\nFolio: #{$ultimoAceptado->id}\nTipo: {$ultimoAceptado->tipo_falta}\nEstatus: VALIDADO";
            
            $qrCode = QrCode::size(150)->generate($datosQr);
            
        }

        return view('Alumno', compact('justificantes', 'qrCode'));
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

    // El insert debe llevar el user_id que acabamos de crear en el paso 1
    DB::table('justificantes')->insert([
        'user_id'        => 1, // <--- Este número debe existir en la tabla 'users'
        'tipo_falta'     => $request->tipo_falta,
        'fecha'          => $request->fecha,
        'horas'          => 'Jornada Completa',
        'motivo'         => $request->motivo,
        'evidencia_path' => $path ?? null,
        'status'         => 'PENDIENTE',
        'created_at'     => now(),
        'updated_at'     => now(),
    ]);

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


