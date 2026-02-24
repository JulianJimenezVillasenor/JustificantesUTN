@extends('Plantilla')

@section('content')

<div class="max-w-md mx-auto mt-10 p-6 bg-white rounded-3xl shadow-2xl border-t-8 {{ $justificante->status == 'ACEPTADO' ? 'border-green-500' : 'border-red-500' }} text-center">
    
    @if($justificante->status == 'ACEPTADO')
        <div class="mb-4 inline-flex items-center justify-center w-20 h-20 bg-green-100 text-green-600 rounded-full">
            <i class="ph ph-check-circle text-5xl"></i>
        </div>
        <h1 class="text-3xl font-black text-green-700 uppercase">Pase Válido</h1>
    @else
        <div class="mb-4 inline-flex items-center justify-center w-20 h-20 bg-red-100 text-red-600 rounded-full">
            <i class="ph ph-x-circle text-5xl"></i>
        </div>
        <h1 class="text-3xl font-black text-red-700 uppercase">No Válido</h1>
    @endif

    <div class="mt-6 space-y-2 border-y py-4">
        <p class="text-gray-400 text-xs font-bold uppercase">Alumno</p>
        <p class="text-xl font-bold text-gray-800">{{ $justificante->nombre_alumno }}</p>
        <p class="text-sm text-gray-500 font-medium">{{ $justificante->grupo }}</p>
    </div>

    <div class="mt-4 grid grid-cols-2 gap-4">
        <div>
            <p class="text-[10px] text-gray-400 uppercase font-bold">Fecha Justificada</p>
            <p class="font-bold text-gray-700">{{ date('d/m/Y', strtotime($justificante->fecha)) }}</p>
        </div>
        <div>
            <p class="text-[10px] text-gray-400 uppercase font-bold">Folio</p>
            <p class="font-bold text-gray-700">#{{ str_pad($justificante->id, 4, '0', STR_PAD_LEFT) }}</p>
        </div>
    </div>

    <p class="mt-8 text-[10px] text-gray-300 italic">Sistema de Justificantes UT Nayarit &copy; 2026</p>
</div>
@endsection