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
<div class="container mx-auto p-6 max-w-7xl">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 border-b pb-4 border-gray-200">
        <div>
            <h1 class="text-3xl font-black text-gray-800 tracking-tight">Reportes de Inasistencias</h1>
            <p class="text-gray-500 text-sm mt-1">Directorio completo de todas las incidencias registradas en la institución.</p>
        </div>
    </div>

    @php
        $total = $justificantes->count();
        $aceptados = $justificantes->where('status', 'ACEPTADO')->count();
        $pendientes = $justificantes->where('status', 'PENDIENTE')->count();
        $rechazados = $justificantes->where('status', 'RECHAZADO')->count();
    @endphp

    <!-- Dashboard Metrics -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center justify-between">
            <div>
                <p class="text-[10px] uppercase font-bold text-gray-500 tracking-wider">Total Registrados</p>
                <p class="text-2xl font-black text-gray-800">{{ $total }}</p>
            </div>
            <div class="bg-blue-50 text-blue-500 p-3 rounded-lg"><i class="ph ph-files text-xl"></i></div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center justify-between">
            <div>
                <p class="text-[10px] uppercase font-bold text-green-600 tracking-wider">Aprobados</p>
                <p class="text-2xl font-black text-gray-800">{{ $aceptados }}</p>
            </div>
            <div class="bg-green-50 text-green-500 p-3 rounded-lg"><i class="ph ph-check-circle text-xl"></i></div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center justify-between">
            <div>
                <p class="text-[10px] uppercase font-bold text-yellow-600 tracking-wider">Pendientes</p>
                <p class="text-2xl font-black text-gray-800">{{ $pendientes }}</p>
            </div>
            <div class="bg-yellow-50 text-yellow-500 p-3 rounded-lg"><i class="ph ph-clock text-xl"></i></div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center justify-between">
            <div>
                <p class="text-[10px] uppercase font-bold text-red-600 tracking-wider">Rechazados</p>
                <p class="text-2xl font-black text-gray-800">{{ $rechazados }}</p>
            </div>
            <div class="bg-red-50 text-red-500 p-3 rounded-lg"><i class="ph ph-x-circle text-xl"></i></div>
        </div>
    </div>

    @if($justificantes->count() > 0)
        <!-- Search and Filter -->
        <div class="bg-white p-4 rounded-t-lg shadow-sm border border-gray-200 border-b-0 flex justify-between items-center">
            <div class="relative w-full md:w-1/3">
                <i class="ph ph-magnifying-glass absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Buscar por alumno, motivo o folio..." class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
            </div>
            <div class="text-xs text-gray-400 font-semibold uppercase tracking-wider hidden md:block">
                Mostrando {{ $total }} registros
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-b-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full whitespace-nowrap" id="reportsTable">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                            <th class="px-6 py-4">Folio</th>
                            <th class="px-6 py-4">Alumno Funcionario</th>
                            <th class="px-6 py-4">Falta y Fecha</th>
                            <th class="px-6 py-4">Razón Principal</th>
                            <th class="px-6 py-4 text-center">Estado Oficial</th>
                            <th class="px-6 py-4 text-right">Documento</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($justificantes as $justificante)
                            <tr class="hover:bg-gray-50 transition-colors group">
                                <td class="px-6 py-4 text-sm font-semibold text-gray-500">
                                    #{{ str_pad($justificante->id, 4, '0', STR_PAD_LEFT) }}
                                </td>
                                <td class="px-6 py-4 text-sm font-bold text-gray-800">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-xs uppercase">
                                            {{ substr(optional($justificante->user)->name ?? '?', 0, 2) }}
                                        </div>
                                        <span>{{ $justificante->user->name ?? 'Usuario Eliminado' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-semibold text-gray-700 bg-gray-100 w-max px-2 py-0.5 rounded text-[11px] mb-1">{{ $justificante->tipo_falta }}</span>
                                        <span class="text-xs text-gray-500"><i class="ph ph-calendar-blank mr-1"></i>{{ \Carbon\Carbon::parse($justificante->fecha)->format('d/m/Y') }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 truncate max-w-[200px]" title="{{ $justificante->motivo }}">
                                    {{ \Illuminate\Support\Str::limit($justificante->motivo, 35) }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($justificante->status == 'ACEPTADO')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold bg-green-100 text-green-700 border border-green-200">
                                            <div class="w-1.5 h-1.5 rounded-full bg-green-500"></div>Aceptado
                                        </span>
                                    @elseif($justificante->status == 'RECHAZADO')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold bg-red-100 text-red-700 border border-red-200">
                                            <div class="w-1.5 h-1.5 rounded-full bg-red-500"></div>Rechazado
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold bg-yellow-100 text-yellow-700 border border-yellow-200">
                                            <div class="w-1.5 h-1.5 rounded-full bg-yellow-500"></div>Pendiente
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('justificantes.pdf', $justificante->id) }}" title="Ver Archivo Oficial" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-colors border border-blue-100 shadow-sm" target="_blank">
                                        <i class="ph ph-file-pdf text-lg"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Mensaje temporal cuando la búsqueda no devuelva resultados -->
            <div id="noResultsMsg" class="hidden p-8 text-center text-gray-500">
                <i class="ph ph-magnifying-glass text-4xl mb-3 text-gray-300"></i>
                <p class="font-semibold">No se encontraron coincidencias para tu búsqueda.</p>
                <p class="text-xs mt-1">Intenta con otro término o folio.</p>
            </div>
        </div>

        <script>
            function filterTable() {
                let input = document.getElementById("searchInput");
                let filter = input.value.toUpperCase();
                let table = document.getElementById("reportsTable");
                let tr = table.getElementsByTagName("tr");
                let visibleRows = 0;

                // Empezar desde 1 para omitir el tr del Thead
                for (let i = 1; i < tr.length; i++) {
                    let tdFolio = tr[i].getElementsByTagName("td")[0];
                    let tdAlumno = tr[i].getElementsByTagName("td")[1];
                    let tdMotivo = tr[i].getElementsByTagName("td")[3];
                    
                    if (tdFolio || tdAlumno || tdMotivo) {
                        let txtValFolio = tdFolio.textContent || tdFolio.innerText;
                        let txtValAlumno = tdAlumno.textContent || tdAlumno.innerText;
                        let txtValMotivo = tdMotivo.textContent || tdMotivo.innerText;
                        
                        if (txtValFolio.toUpperCase().indexOf(filter) > -1 || 
                            txtValAlumno.toUpperCase().indexOf(filter) > -1 ||
                            txtValMotivo.toUpperCase().indexOf(filter) > -1) {
                            tr[i].style.display = "";
                            visibleRows++;
                        } else {
                            tr[i].style.display = "none";
                        }
                    }
                }

                // Show/hide el span de "No Results"
                document.getElementById('noResultsMsg').style.display = visibleRows === 0 ? 'block' : 'none';
            }
        </script>
    @else
        <div class="bg-gray-50 border border-dashed border-gray-300 rounded-xl p-12 text-center">
            <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="ph ph-files text-3xl text-gray-500"></i>
            </div>
            <p class="text-gray-700 font-bold text-lg">Bandeja Vacía</p>
            <p class="text-gray-500 mt-1 max-w-sm mx-auto text-sm">No existen reportes generados. Aquí aparecerán todos los justificantes sometidos a revisión.</p>
        </div>
    @endif
</div>
@endsection
