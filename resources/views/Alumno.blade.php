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
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow-sm border">
        <h2 class="text-[#004d3d] font-bold text-xl mb-4 border-b pb-2">Nueva Solicitud de Justificante</h2>
        <form action="#" method="POST" enctype="multipart/form-data" class="space-y-4 text-sm">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold">Tipo de Falta</label>
                    <select class="w-full border rounded p-2 bg-gray-50">
                        <option>Médica</option>
                    </select>
                </div>
                <div>
                    <label class="block font-bold">Fecha de Inasistencia</label>
                    <input type="date" class="w-full border rounded p-2 bg-gray-50">
                </div>
            </div>
            <div>
                <label class="block font-bold">Rango de Horas (Opcional)</label>
                <input type="text" placeholder="Ej. 07:00 - 10:00" class="w-full border rounded p-2 bg-gray-50">
            </div>
            <div>
                <label class="block font-bold">Motivo del Justificante</label>
                <textarea class="w-full border rounded p-2 bg-gray-50" rows="3"></textarea>
            </div>
            <div>
                <label class="block font-bold">Carga de Evidencia (Imagen/PDF)</label>
                <div class="border-2 border-dashed p-4 text-center rounded">
                    <input type="file" class="text-xs">
                </div>
            </div>
            <button class="bg-[#006d5b] text-white px-6 py-2 rounded font-bold hover:bg-[#004d3d] transition">
                Enviar para Validación de Tutor
            </button>
        </form>
    </div>

    <div class="space-y-6">
        <div class="bg-white p-4 rounded-lg shadow-sm border">
            <h3 class="font-bold mb-4 border-b pb-2">Estatus Reciente</h3>
            <div class="mb-4">
                <div class="flex justify-between items-center">
                    <span class="font-bold">Folio: #A-782</span>
                    <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-bold">ACEPTADO</span>
                </div>
                <p class="text-xs text-gray-500 mt-1">El profesor de Programación ha leído el documento.</p>
            </div>
            <div class="border-t pt-4">
                <div class="flex justify-between items-center">
                    <span class="font-bold">Folio: #A-795</span>
                    <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-xs font-bold">PENDIENTE</span>
                </div>
                <p class="text-xs text-gray-500 mt-1">Esperando validación de evidencia médica por el tutor.</p>
            </div>
        </div>

        <div class="bg-white p-4 rounded-lg shadow-sm border flex flex-col items-center">
            <h3 class="font-bold self-start mb-4">Mi Último QR</h3>
            <div class="border-2 border-dashed p-10 bg-gray-50 text-gray-400 text-center">
                [QR CODE]
            </div>
            <p class="text-[10px] mt-4 text-gray-400">Escanee este código para validar el folio #A-782</p>
        </div>
    </div>
</div>
@endsection
