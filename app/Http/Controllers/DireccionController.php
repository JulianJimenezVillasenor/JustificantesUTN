<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Justificante;

class DireccionController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalJustificantes = Justificante::count();
        $justificantesPendientes = Justificante::where('status', 'pendiente')->count();
        $reportes = []; // Aquí podrías tener una tabla de reportes enviados

        return view('direccion', compact('totalUsers', 'totalJustificantes', 'justificantesPendientes', 'reportes'));
    }

    public function viewReports()
    {
        $justificantes = Justificante::with('user')->orderBy('created_at', 'desc')->get();
        return view('reportes_direccion', compact('justificantes'));
    }
}
