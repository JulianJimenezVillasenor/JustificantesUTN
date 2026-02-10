@extends('Plantilla')

@section('menu')
    <ul class="flex justify-between items-center w-full px-6">
        <li class="text-sm italic">Sesión activa: {{ Request::segment(1) }}</li>
        <li>
            <a href="{{ route('logout') }}"
            class="bg-red-500 hover:bg-red-700 text-white px-3 py-1 rounded transition-colors shadow-sm">
            Cerrar Sesión
            </a>
        </li>
    </ul>
@endsection

@section('content')
<div class="grid grid-cols-4 gap-6 text-sm">
    <div class="col-span-3 bg-white p-6 rounded-lg shadow-sm border relative">
        <div class="flex justify-between items-start mb-6">
            <h2 class="text-xl font-bold">Revisión de Solicitud #A-795</h2>
            <span class="bg-yellow-100 text-yellow-600 px-3 py-1 rounded-full text-xs font-bold uppercase">Esperando Validación</span>
        </div>

        <div class="space-y-4 mb-8">
            <p><strong>Alumno:</strong> Brandon Hernandez (DSM-52)</p>
            <p><strong>Total Faltas Mes:</strong> 12%</p>
            <div class="w-full bg-gray-200 h-2 rounded-full">
                <div class="bg-green-800 h-2 rounded-full" style="width: 12%"></div>
            </div>
        </div>

        <div class="absolute top-20 right-6">
            <button class="border border-[#004d3d] text-[#004d3d] px-4 py-1 rounded hover:bg-gray-50 transition">Ver Receta Médica</button>
        </div>

        <div class="flex justify-end gap-3 mt-10">
            <button class="bg-red-600 text-white px-4 py-2 rounded font-bold">Rechazar (Evidencia Inválida)</button>
            <button class="bg-[#004d3d] text-white px-4 py-2 rounded font-bold">Validar y Generar QR</button>
        </div>
    </div>

    <div class="col-span-1 space-y-4">
        <div class="bg-white p-4 rounded-lg border shadow-sm">
            <h3 class="font-bold mb-2">Reportes Mensuales</h3>
            <p class="text-xs text-gray-500 mb-3">Generar resumen de faltas justificadas por grupo.</p>
            <select class="w-full border rounded p-2 mb-3"><option>Seleccionar Grupo...</option></select>
            <button class="w-full border border-[#004d3d] text-[#004d3d] py-2 rounded font-bold">Descargar PDF</button>
        </div>

        <div class="bg-white p-4 rounded-lg border-l-4 border-red-500 shadow-sm">
            <h3 class="font-bold mb-2">Alertas Críticas</h3>
            <p class="text-xs text-red-600"><strong>Alumno: Carlos R.</strong> ha excedido el límite de faltas permitido. Sistema bloqueado para nuevas solicitudes.</p>
        </div>
    </div>
</div>
@endsection
