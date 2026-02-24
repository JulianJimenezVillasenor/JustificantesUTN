@extends('Plantilla')

@section('menu')
    <ul class="flex justify-between items-center w-full px-6">
        <li class="text-sm italic text-white/80">Panel de Control: Tutor Académico</li>
        <li>
            <a href="{{ route('logout') }}"
            class="bg-red-500 hover:bg-red-600 text-white px-4 py-1.5 rounded-lg transition-all shadow-sm font-semibold">
            Cerrar Sesión
            </a>
        </li>
    </ul>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-4 gap-6 text-sm">
    
    <div class="lg:col-span-3 space-y-6">
        
        <form action="{{ route('tutor.index') }}" method="GET" class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex gap-3 items-center">
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                    <i class="ph ph-magnifying-glass text-lg"></i>
                </span>
                <input type="text" name="buscar" value="{{ request('buscar') }}" 
                    placeholder="Buscar por Folio, Tipo de falta o Motivo..." 
                    class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#004d3d] focus:border-transparent outline-none transition-all">
            </div>
            <button type="submit" class="bg-[#004d3d] text-white px-6 py-2.5 rounded-xl font-bold hover:bg-[#00362b] transition-colors shadow-sm">
                Buscar
            </button>
            @if(request('buscar'))
                <a href="{{ route('tutor.index') }}" class="text-gray-400 hover:text-red-500 transition-colors">
                    <i class="ph ph-x-circle text-2xl"></i>
                </a>
            @endif
        </form>

        <div class="space-y-4">
            @forelse($justificantes as $j)
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 relative hover:border-[#004d3d]/30 transition-all group">
                
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Folio</span>
                            <span class="bg-gray-100 text-gray-700 px-2 py-0.5 rounded font-mono font-bold italic">
                                #{{ str_pad($j->id, 4, '0', STR_PAD_LEFT) }}
                            </span>
                        </div>
                        <h2 class="text-lg font-bold text-gray-800 italic">
                            Alumno: <span class="text-[#004d3d]">{{ $j->nombre_alumno }}</span>
                        </h2>
                    </div>
                    <div class="text-right">
                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest shadow-sm
                            {{ $j->status == 'PENDIENTE' ? 'bg-yellow-100 text-yellow-700' : ($j->status == 'ACEPTADO' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700') }}">
                            {{ $j->status }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 bg-gray-50/50 p-4 rounded-xl border border-gray-100 mb-6">
                    <div>
                        <p class="text-[10px] text-gray-400 uppercase font-bold mb-1">Tipo de Falta</p>
                        <p class="font-bold text-gray-700">{{ $j->tipo_falta }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 uppercase font-bold mb-1">Fecha de Inasistencia</p>
                        <p class="font-bold text-gray-700">{{ date('d/m/Y', strtotime($j->fecha)) }}</p>
                    </div>
                    <div class="col-span-2 md:col-span-1">
                        <p class="text-[10px] text-gray-400 uppercase font-bold mb-1">Horario</p>
                        <p class="font-bold text-gray-700">{{ $j->horas ?? 'Día Completo' }}</p>
                    </div>
                    <div class="col-span-full pt-2 border-t border-gray-200/50">
                        <p class="text-[10px] text-gray-400 uppercase font-bold mb-1">Explicación del Motivo</p>
                        <p class="text-gray-600 italic">"{{ $j->motivo }}"</p>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <a href="{{ asset('storage/' . $j->evidencia_path) }}" 
                        target="_blank" 
                        class="flex items-center gap-2 text-[#004d3d] font-bold hover:text-green-700 transition-colors group/link">
                        <div class="p-2 bg-green-50 rounded-lg group-hover/link:bg-green-100 transition-colors">
                            <i class="ph ph-eye text-xl"></i>
                        </div>
                        <span>Visualizar Evidencia (Receta/Documento)</span>
                    </a>

                    @if($j->status == 'PENDIENTE')
                    <div class="flex gap-2 w-full md:w-auto">
                        <form action="{{ route('tutor.update', $j->id) }}" method="POST" class="flex-1">
                            @csrf
                            <input type="hidden" name="nuevo_estatus" value="RECHAZADO">
                            <button type="submit" class="w-full border-2 border-red-500 text-red-500 px-4 py-2 rounded-xl font-bold hover:bg-red-50 transition-all uppercase text-[11px]">
                                Rechazar
                            </button>
                        </form>
                        <form action="{{ route('tutor.update', $j->id) }}" method="POST" class="flex-1">
                            @csrf
                            <input type="hidden" name="nuevo_estatus" value="ACEPTADO">
                            <button type="submit" class="w-full bg-[#004d3d] text-white px-6 py-2.5 rounded-xl font-bold hover:bg-[#00362b] shadow-md transition-all uppercase text-[11px]">
                                Validar y Generar QR
                            </button>
                        </form>
                    </div>
                    @else
                    <div class="text-gray-400 text-xs italic flex items-center gap-2">
                        <i class="ph ph-check-circle text-lg"></i>
                        Esta solicitud ya fue gestionada el {{ $j->updated_at }}
                    </div>
                    @endif
                </div>
            </div>
            @empty
            <div class="bg-white p-20 rounded-xl border-2 border-dashed border-gray-200 text-center">
                <i class="ph ph-files text-6xl text-gray-200 mb-4 block"></i>
                <p class="text-gray-400 text-lg font-medium">No se encontraron justificantes con ese criterio.</p>
                <a href="{{ route('tutor.index') }}" class="text-[#004d3d] font-bold hover:underline mt-2 inline-block">Ver todos los registros</a>
            </div>
            @endforelse
        </div>
    </div>

    <div class="space-y-4">
        <div class="bg-[#004d3d] p-6 rounded-2xl text-white shadow-lg relative overflow-hidden">
            <div class="relative z-10">
                <h3 class="font-bold text-lg mb-1">Total del Mes</h3>
                <p class="text-xs text-white/70 mb-4 italic">Solicitudes gestionadas</p>
                <div class="text-4xl font-black italic">{{ $justificantes->count() }}</div>
            </div>
            <i class="ph ph-chart-bar absolute -right-4 -bottom-4 text-8xl text-black/10"></i>
        </div>

        <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm">
            <h3 class="font-black text-gray-800 mb-4 border-b pb-2 uppercase text-[11px] tracking-widest">Reportes por Grupo</h3>
            <div class="space-y-3">
                <select class="w-full bg-gray-50 border border-gray-200 rounded-lg p-2.5 outline-none focus:ring-2 focus:ring-[#004d3d]">
                    <option>DSM-51 (Multiplataforma)</option>
                    <option>DSM-52 (Multiplataforma)</option>
                </select>
                <button class="w-full py-3 bg-white border-2 border-[#004d3d] text-[#004d3d] rounded-xl font-bold flex items-center justify-center gap-2 hover:bg-green-50 transition-all">
                    <i class="ph ph-file-pdf text-xl"></i> Generar Reporte PDF
                </button>
            </div>
        </div>
    </div>
</div>
@endsection