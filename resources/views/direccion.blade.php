@extends('Plantilla')
@section('menu')
    <div class="flex items-center gap-4 px-4 py-1 bg-white/10 rounded-2xl border border-white/5 backdrop-blur-sm">
        <div class="flex flex-col items-end border-r border-white/20 pr-4 hidden sm:flex">
            <span class="text-[10px] uppercase font-bold tracking-widest text-emerald-300 leading-none">Sesión Activa</span>
            <span class="text-white text-xs font-semibold">
                {{ Auth::user()->name ?? 'Dirección' }}
            </span>
        </div>


        <a href="{{ route('logout') }}"
            class="flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white text-[11px] font-black uppercase px-4 py-2 rounded-xl transition-all shadow-md hover:scale-105 active:scale-95">
            <i class="ph ph-power-bold text-sm"></i>
            <span>Cerrar Sesión</span>
        </a>
    </div>
@endsection

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-6">Panel de Dirección</h1>

        <!-- Estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold">Total de Usuarios</h3>
                <p class="text-2xl font-bold text-blue-600">{{ $totalUsers }}</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold">Total de Justificantes</h3>
                <p class="text-2xl font-bold text-green-600">{{ $totalJustificantes }}</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold">Justificantes Pendientes</h3>
                <p class="text-2xl font-bold text-red-600">{{ $justificantesPendientes }}</p>
            </div>
        </div>

        <!-- Ver Reportes -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4">Reportes Recibidos</h2>
            <a href="{{ route('direccion.viewReports') }}"
                class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">Ver Reportes</a>
        </div>
    </div>
@endsection