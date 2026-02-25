@extends('Plantilla')

@section('menu')
    <ul class="flex justify-between items-center w-full px-6">
        <li class="text-sm">
            <span class="font-bold text-[#004d3d]">Docente:</span> Ing. Julian Jimenez Villaseñor
            <span class="ml-4 bg-red-100 text-red-600 px-2 py-0.5 rounded-full text-[10px] font-black animate-pulse">
                {{ $justificantes->where('firma_docente', false)->count() }} PENDIENTES POR FIRMAR
            </span>
        </li>
        <li>
            <a href="{{ route('logout') }}" class="bg-red-500 hover:bg-red-700 text-white px-3 py-1 rounded transition-colors shadow-sm text-sm">
                Cerrar Sesión
            </a>
        </li>
    </ul>
@endsection

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    <div class="flex justify-between items-end">
        <div>
            <h2 class="text-2xl font-black text-[#004d3d] uppercase tracking-tighter">Bandeja de Firmas</h2>
            <p class="text-gray-500 text-sm">Justificantes validados por tutoría que afectan sus clases.</p>
        </div>
        <div class="flex gap-2">
            <button class="px-4 py-2 bg-white border rounded-lg text-xs font-bold shadow-sm hover:bg-gray-50">TODOS</button>
            <button class="px-4 py-2 bg-[#004d3d] text-white rounded-lg text-xs font-bold shadow-sm">SOLO PENDIENTES</button>
        </div>
    </div>

    <div class="grid gap-4">
        @forelse($justificantes as $j)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
            <div class="flex flex-col md:flex-row">

                <div class="w-2 {{ $j->horas ? 'bg-blue-500' : 'bg-purple-600' }}" title="{{ $j->horas ? 'Falta Parcial' : 'Jornada Completa' }}"></div>

                <div class="p-5 flex-1 grid grid-cols-1 md:grid-cols-4 gap-4 items-center">

                    <div class="col-span-1">
                        <p class="text-[10px] text-gray-400 font-bold uppercase">Alumno y Grupo</p>
                        <p class="font-bold text-gray-800">{{ $j->nombre_alumno }}</p> <p class="text-xs text-gray-600 italic">{{ $j->grupo }}</p>
                    </div>

                    <div class="col-span-1 border-l pl-4">
                        <p class="text-[10px] text-gray-400 font-bold uppercase">Fecha y Horario</p>
                        <p class="font-bold text-gray-800">{{ date('d/m/Y', strtotime($j->fecha)) }}</p>
                        <p class="text-xs {{ $j->horas ? 'text-blue-600 font-bold' : 'text-purple-600 font-bold' }}">
                            <i class="ph {{ $j->horas ? 'ph-clock' : 'ph-calendar-check' }}"></i>
                            {{ $j->horas ?? 'Toda la Jornada' }}
                        </p>
                    </div>

                    <div class="col-span-1 border-l pl-4">
                        <p class="text-[10px] text-gray-400 font-bold uppercase">Motivo</p>
                        <p class="text-xs text-gray-600 line-clamp-2">"{{ $j->motivo }}"</p>
                        <a href="{{ asset('storage/' . $j->evidencia_path) }}" target="_blank" class="text-[#004d3d] text-[10px] font-black underline hover:text-green-700">
                            REVISAR EVIDENCIA MÉDICA
                        </a>
                    </div>

                    <div class="col-span-1 flex flex-col items-center justify-center bg-gray-50 rounded-lg p-3 border-2 border-dashed border-gray-200">
                        @if(!$j->firma_docente)
                            <form action="{{ route('docente.firmar', $j->id) }}" method="POST" class="w-full">
                                @csrf
                                <button type="submit" class="w-full bg-[#004d3d] hover:bg-[#00362b] text-white py-2 rounded-lg font-bold text-xs flex items-center justify-center gap-2 transition-transform active:scale-95">
                                    <i class="ph ph-signature-bold text-lg"></i>
                                    FIRMADO DIGITAL
                                </button>
                            </form>
                            <p class="text-[9px] text-gray-400 mt-2 text-center uppercase">Requiere su firma para validar inasistencia</p>
                        @else
                            <div class="relative">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/3/3a/Jon_Kirsch_Signature.png" class="h-10 opacity-80 mix-blend-multiply" alt="Firma Docente">
                                <div class="absolute inset-0 flex items-center justify-center opacity-10">
                                    <i class="ph ph-seal-check text-4xl text-green-800"></i>
                                </div>
                            </div>
                            <p class="text-[9px] text-green-700 font-black mt-1 italic uppercase tracking-tighter">FIRMADO EL {{ date('d/m/Y H:i', strtotime($j->fecha_firma_docente)) }}</p>
                        @endif
                    </div>

                </div>
            </div>
        </div>
        @empty
        <div class="bg-white p-20 rounded-xl border-2 border-dashed border-gray-200 text-center">
            <i class="ph ph-envelope-open text-6xl text-gray-200 mb-4 block"></i>
            <p class="text-gray-400 text-lg font-medium">No tiene justificantes pendientes de firma.</p>
        </div>
        @endforelse
    </div>
</div>

<style>
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    .animate-pulse { animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
</style>
@endsection
