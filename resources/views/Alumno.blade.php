@extends('Plantilla')

@section('menu')
<div class="flex items-center gap-4 px-4 py-1 bg-white/10 rounded-2xl border border-white/5 backdrop-blur-sm">
    <div class="flex flex-col items-end border-r border-white/20 pr-4 hidden sm:flex">
        <span class="text-[10px] uppercase font-bold tracking-widest text-emerald-300 leading-none">Sesión Activa</span>
        <span class="text-white text-xs font-semibold">
            <!--{{ Auth::user('alumno')->name ?? 'Jiménez Villaseñor Julián' }}-->
            {{ Auth::user('alumno')->name ?? 'Alumno' }}
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
<div class="grid grid-cols-1 md:grid-cols-4 gap-6">

    <div class="md:col-span-1 space-y-4">
        <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-[#004d3d]">
            <h2 class="text-lg font-bold mb-4 text-[#004d3d]">Nueva Solicitud</h2>
            <form action="{{ route('justificantes.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-500">Tipo de Falta</label>
                    <select name="tipo_falta" class="w-full border rounded p-2 text-sm">
                        <option>Médica</option>
                        <option>Familiar</option>
                        <option>Trámite Oficial</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-500">Fecha de Inasistencia</label>
                    <input type="date" name="fecha" class="w-full border rounded p-2 text-sm" required>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-500">Motivo</label>
                    <textarea name="motivo" class="w-full border rounded p-2 text-sm" rows="3" placeholder="Explicación breve..."></textarea>
                </div>
                <div>
                    <!-- Original
                    <label class="block text-xs font-bold uppercase text-gray-500">Evidencia (PDF/JPG)</label>
                    <input type="file" name="evidencia" class="w-full text-xs">
                    -->
                    <label class="block text-xs font-bold uppercase text-gray-500">Evidencia (PDF o JPG - Máx 5MB)</label>
                    <input type="file"
                        name="evidencia"
                        id="evidencia_input"
                        accept=".pdf, .jpg, .jpeg, .png"
                        class="w-full text-xs border p-2 rounded"
                        required>
                    <p id="error-msg" class="text-red-500 text-[10px] mt-1 hidden font-bold"></p>
                </div> <!-- div original-->

                <!-- scrip nuevo -->
                    <script>
                    document.getElementById('evidencia_input').addEventListener('change', function() {
                        const file = this.files[0];
                        const errorMsg = document.getElementById('error-msg');
                        const maxSize = 5 * 1024 * 1024; // 5MB en bytes

                        if (file) {
                            // Validar tamaño
                            if (file.size > maxSize) {
                                errorMsg.innerText = " El archivo supera los 5MB. Elige uno más pequeño.";
                                errorMsg.classList.remove('hidden');
                                this.value = ''; // Limpiar el input
                                return;
                            }

                            // Validar extensión (extra seguridad en frontend)
                            const allowedExtensions = /(\.jpg|\.jpeg|\.png|\.pdf)$/i;
                            if (!allowedExtensions.exec(this.value)) {
                                errorMsg.innerText = " Solo se permiten archivos PDF, JPG o PNG.";
                                errorMsg.classList.remove('hidden');
                                this.value = '';
                                return;
                            }

                            errorMsg.classList.add('hidden');
                        }
                    });
                    </script>
                <!-- fin scrip nuevo-->
                <button type="submit" class="w-full bg-[#004d3d] text-white py-2 rounded font-bold hover:bg-[#00362b] transition">
                    Enviar Solicitud
                </button>
            </form>
        </div>

        <div class="bg-white p-4 rounded-lg shadow-md border text-center">
            <h3 class="text-sm font-bold mb-3">Mi QR de Validación</h3>
            @php
                $ultimoAceptado = $justificantes->where('status', 'ACEPTADO')->first();
            @endphp

            @if($ultimoAceptado)
                <div class="p-2 border-2 border-dashed border-[#004d3d] inline-block">
                    {!! QrCode::size(120)->generate(route('validar.publico', $ultimoAceptado->id)) !!}
                </div>
                <p class="text-[10px] mt-2 text-gray-500 italic uppercase">Pase de entrada activo</p>
                <a href="{{ route('justificantes.pdf', $ultimoAceptado->id) }}" target="_blank" class="block mt-2 text-blue-600 underline text-xs font-bold">
                    Ver PDF Oficial
                </a>
            @else
                <div class="py-6 text-gray-300">
                    <i class="ph ph-qr-code text-5xl"></i>
                    <p class="text-xs mt-2">Sin QR disponible</p>
                </div>
            @endif
        </div>
    </div>

    <div class="md:col-span-3">
        <div class="bg-white rounded-lg shadow-md border overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b">
                <h2 class="font-bold text-gray-700">Historial de Justificantes</h2>
            </div>
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-100 text-gray-600 uppercase text-[10px]">
                    <tr>
                        <th class="px-6 py-3">Folio</th>
                        <th class="px-6 py-3">Fecha</th>
                        <th class="px-6 py-3">Tipo</th>
                        <th class="px-6 py-3">Estatus</th>
                        <th class="px-6 py-3">Docente</th>
                        <th class="px-6 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($justificantes as $j)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-mono font-bold">#{{ str_pad($j->id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-6 py-4">{{ date('d/m/Y', strtotime($j->fecha)) }}</td>
                        <td class="px-6 py-4"><span class="font-semibold">{{ $j->tipo_falta }}</span></td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-full text-[10px] font-bold
                                {{ $j->status == 'PENDIENTE' ? 'bg-yellow-100 text-yellow-700' : ($j->status == 'ACEPTADO' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700') }}">
                                {{ $j->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($j->firma_docente)
                                <span class="text-green-600 text-xs font-bold"><i class="ph ph-check-circle"></i> Firmado</span>
                            @else
                                <span class="text-gray-400 text-xs italic">Pendiente</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex gap-2">
                                <a href="{{ asset('storage/' . $j->evidencia_path) }}" target="_blank" title="Ver Evidencia" class="text-blue-500 hover:text-blue-700">
                                    <i class="ph ph-eye text-xl"></i>
                                </a>
                                @if($j->status == 'ACEPTADO')
                                    <a href="{{ route('justificantes.pdf', $j->id) }}" target="_blank" title="Generar PDF Oficial" class="text-red-500 hover:text-red-700">
                                        <i class="ph ph-file-pdf text-xl"></i>
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-gray-400 italic">No has realizado ninguna solicitud aún.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
