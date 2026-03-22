@extends('Plantilla')
@section('menu')
    <!-- Barra de navegación con botón de regreso -->
    <div class="flex items-center justify-between mb-6">

        <a href="{{ route('direccion.index') }}" class="hover:bg-gray-500 text-white font-bold py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
            <i class="ph ph-arrow-left"></i>
            Volver al Panel
        </a>
    </div>
@endsection

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Reportes de Inasistencias</h1>

    @if($justificantes->count() > 0)
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-100 border-b-2 border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">ID</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Alumno</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Tipo de Falta</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Fecha</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Motivo</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Estado</th>
                        <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($justificantes as $justificante)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-700">#{{ $justificante->id }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $justificante->user->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $justificante->tipo_falta }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $justificante->fecha }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $justificante->motivo }}</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    @if($justificante->status == 'ACEPTADO') bg-green-100 text-green-800
                                    @elseif($justificante->status == 'RECHAZADO') bg-red-100 text-red-800
                                    @else bg-yellow-100 text-yellow-800
                                    @endif">
                                    {{ $justificante->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('justificantes.pdf', $justificante->id) }}" class="text-blue-500 hover:text-blue-700 text-sm font-semibold" target="_blank">Ver PDF</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 text-center">
            <p class="text-blue-700 font-semibold">No hay reportes de inasistencias disponibles.</p>
        </div>
    @endif
</div>
@endsection
