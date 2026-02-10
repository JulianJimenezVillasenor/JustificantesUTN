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
<div class="space-y-6 text-sm">
    <div class="bg-white rounded-lg shadow-sm border p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold">Bandeja de Justificantes Recibidos</h2>
            <span class="text-[10px] text-green-600 bg-green-50 px-2 py-1 rounded border border-green-200">SINCRONIZADO CON SQL</span>
        </div>

        <table class="w-full text-left">
            <thead class="bg-gray-50 text-gray-400 uppercase text-[10px]">
                <tr>
                    <th class="p-3">Alumno</th>
                    <th class="p-3">Grupo</th>
                    <th class="p-3">Motivo</th>
                    <th class="p-3">Fecha/Hora</th>
                    <th class="p-3">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <tr>
                    <td class="p-3">Brandon Hernandez</td>
                    <td class="p-3">DSM-52</td>
                    <td class="p-3">Médico (Cita IMSS)</td>
                    <td class="p-3 italic">03/Feb - 08:00 - 10:00</td>
                    <td class="p-3">
                        <button class="bg-[#004d3d] text-white px-4 py-1 rounded">Firmar Recibido</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="bg-white rounded-lg shadow-sm border p-6">
        <h2 class="text-lg font-bold mb-2">Mi Firma Digital (Configuración)</h2>
        <p class="text-gray-400 mb-6 italic">Esta firma se aplicará a los justificantes que aceptes.</p>

        <div class="border-2 border-dashed border-blue-100 rounded-lg p-12 flex flex-col items-center justify-center bg-gray-50/50">
            <p class="text-gray-400 mb-4">Arrastra tu firma en formato PNG (fondo transparente) aquí</p>
            <input type="file" class="text-xs mb-2">
            <span class="text-[10px] text-green-600 bg-green-50 px-2 py-1 rounded mt-4">FIRMA ACTUAL: FIRMA_GARCIA_DIGITAL.PNG</span>
        </div>
    </div>
</div>
@endsection
