@extends('Plantilla')

@section('menu')
    <div class="flex items-center gap-4 px-4 py-1 bg-white/10 rounded-2xl border border-white/5 backdrop-blur-sm">
        <div class="flex flex-col items-end border-r border-white/20 pr-4 hidden sm:flex">
            <span class="text-[10px] uppercase font-bold tracking-widest text-emerald-300 leading-none">Sesión Activa</span>
            <span class="text-white text-xs font-semibold">
                {{ Auth::user()->name ?? 'Administrador' }}
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
    <div class="container mx-auto p-6 max-w-4xl">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold flex items-center gap-2">
                <a href="{{ route('admin.index') }}" class="text-gray-400 hover:text-blue-500 transition-colors">
                    <i class="ph ph-arrow-left"></i>
                </a>
                Editar Usuario: {{ $user->name }}
            </h1>
            <span class="px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-xs font-bold uppercase border">
                Rol: {{ $user->role }}
            </span>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm">
                <p class="font-bold mb-2">Por favor, corrija los siguientes errores:</p>
                <ul class="list-disc pl-5 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white p-6 rounded-lg shadow-md mb-6">
            <form action="{{ route('admin.updateUser', $user->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Nombre</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500"
                            required>
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500"
                            required>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Contraseña (Dejar en blanco para conservar actual)</label>
                    <input type="password" name="password" id="password"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500">
                </div>

                @if($user->role === 'alumno')
                    <h3 class="text-lg font-bold border-b pb-2 mt-8 mb-4 text-[#004d3d]">Detalles del Alumno</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="grupo" class="block text-sm font-medium text-gray-700">Grupo (Opcional)</label>
                            <input type="text" name="grupo" id="grupo" value="{{ old('grupo', $user->grupo) }}" placeholder="Ej. 51, 52, 51IA..." class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500">
                        </div>
                        <div>
                            <label for="tutor_id" class="block text-sm font-medium text-gray-700">Tutor Asignado</label>
                            <select name="tutor_id" id="tutor_id"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500">
                                <option value="">-- Sin Tutor Asignado --</option>
                                @php
                                    $tutores = \App\Models\User::where('role', 'tutor')->get();
                                @endphp
                                @foreach($tutores as $tutor)
                                    <option value="{{ $tutor->id }}" {{ (old('tutor_id', $user->tutor_id) == $tutor->id) ? 'selected' : '' }}>
                                        {{ $tutor->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Carga Horaria Perteneciente (Materias y Horarios)</label>
                        <div id="materias-container" class="space-y-3">
                            @php
                                $docentesList = \App\Models\User::where('role', 'docente')
                                    ->orWhere(function($query) {
                                        $query->where('role', 'tutor')->where('es_docente', true);
                                    })->select('id', 'name', 'role')->get();
                            @endphp

                            @foreach($carga_horaria as $index => $asignacion)
                                <div class="flex flex-col md:flex-row gap-3 p-3 border border-gray-200 rounded bg-gray-50 items-start md:items-center relative group" id="materia_row_{{ $index }}">
                                    <div class="flex-1 w-full">
                                        <label class="text-[10px] text-gray-500 font-bold uppercase mb-1 block">Docente</label>
                                        <select name="materias[{{ $index }}][docente_id]" class="w-full px-2 py-1.5 border border-gray-300 rounded shadow-sm focus:ring-indigo-500 text-sm bg-white" required>
                                            <option value="">-- Seleccionar Docente --</option>
                                            @foreach($docentesList as $docente)
                                                <option value="{{ $docente->id }}" {{ $asignacion->docente_id == $docente->id ? 'selected' : '' }}>
                                                    {{ $docente->name }} {{ $docente->role === 'tutor' ? '(Tutor)' : '' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="flex-1 w-full">
                                        <label class="text-[10px] text-gray-500 font-bold uppercase mb-1 block">Materia</label>
                                        <input type="text" name="materias[{{ $index }}][nombre]" value="{{ $asignacion->materia }}" placeholder="Nombre exacto" class="w-full px-2 py-1.5 border border-gray-300 rounded shadow-sm focus:ring-indigo-500 text-sm" required>
                                    </div>
                                    <div class="flex-[0.7] w-full relative">
                                        <label class="text-[10px] text-gray-500 font-bold uppercase mb-1 block">Horario</label>
                                        <div class="flex gap-2 items-center">
                                            <input type="text" name="materias[{{ $index }}][horario]" value="{{ $asignacion->horario ?? '' }}" placeholder="Ej. L-X-V 9:00" class="w-full px-2 py-1.5 border border-gray-300 rounded shadow-sm focus:ring-indigo-500 text-sm">
                                            <button type="button" onclick="eliminarMateria({{ $index }})" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-1 rounded transition-colors" title="Eliminar Fila">
                                                <i class="ph ph-trash text-lg"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" onclick="agregarMateria()" class="mt-3 bg-indigo-50 border border-indigo-200 text-indigo-700 hover:bg-indigo-100 font-semibold py-2 px-4 rounded text-sm flex items-center gap-2 transition-colors">
                            <i class="ph ph-plus-circle text-lg"></i> Añadir Nueva Materia
                        </button>
                    </div>
                @endif

                @if($user->role === 'tutor')
                    <h3 class="text-lg font-bold border-b pb-2 mt-8 mb-4 text-[#004d3d]">Detalles del Tutor</h3>
                    <div class="mb-4">
                        <label class="flex items-center bg-gray-50 p-4 border rounded-lg cursor-pointer hover:bg-gray-100 transition-colors">
                            <input type="checkbox" name="es_docente" id="es_docente" value="1" 
                                class="rounded border-gray-300 text-indigo-600 shadow-sm w-5 h-5 focus:ring-indigo-500"
                                {{ old('es_docente', $user->es_docente) ? 'checked' : '' }}>
                            <div class="ml-3">
                                <span class="block text-sm text-gray-900 font-bold">Este tutor también imparte clases</span>
                                <span class="block text-xs text-gray-500">Al activar esto, el tutor aparecerá en la lista de maestros disponibles para agendar materias a los alumnos, y se le habilitará el panel de firma como Docente.</span>
                            </div>
                        </label>
                    </div>
                @endif

                @if($user->role === 'tutor' || $user->role === 'docente')
                    <h3 class="text-lg font-bold border-b pb-2 mt-8 mb-4 text-[#004d3d]">Sello o Firma Institucional</h3>
                    <div class="mb-4 bg-gray-50 border border-gray-200 p-4 rounded-lg">
                        <label for="firma" class="block text-sm font-extrabold text-gray-700 mb-2">Subir / Actualizar Imagen (JPG/PNG)</label>
                        <input type="file" name="firma" id="firma" accept="image/png, image/jpeg" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer">
                        @if($user->firma_path)
                            <div class="mt-4 p-3 bg-white border border-gray-200 rounded inline-block shadow-sm">
                                <span class="text-[10px] text-gray-400 font-bold uppercase block mb-2 tracking-wider">Firma Actual:</span>
                                <img src="{{ asset('storage/' . $user->firma_path) }}" alt="Firma" class="h-16 object-contain pointer-events-none">
                            </div>
                        @else
                            <p class="text-[10px] mt-3 text-red-500 font-bold uppercase tracking-wider"><i class="ph ph-warning"></i> Sin sello ni firma registrada.</p>
                        @endif
                    </div>
                @endif

                <div class="mt-8 flex justify-end gap-3">
                    <a href="{{ route('admin.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 font-bold rounded hover:bg-gray-300 transition-colors">Cancelar</a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded shadow-md transition-colors">
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let materiaIndex = {{ count($carga_horaria) > 0 ? max(array_keys((array)$carga_horaria)) + 1 : 0 }};
        const docentesList = @json($docentesList ?? []);

        function agregarMateria() {
            const container = document.getElementById('materias-container');
            
            let opcionesDocentes = '<option value="">-- Seleccionar Docente --</option>';
            docentesList.forEach(d => {
                let badge = d.role === 'tutor' ? ' (Tutor)' : '';
                opcionesDocentes += `<option value="${d.id}">${d.name}${badge}</option>`;
            });

            const html = `
                <div class="flex flex-col md:flex-row gap-3 p-3 border border-gray-200 rounded bg-gray-50 items-start md:items-center relative group" id="materia_row_${materiaIndex}">
                    <div class="flex-1 w-full">
                        <label class="text-[10px] text-gray-500 font-bold uppercase mb-1 block">Docente</label>
                        <select name="materias[${materiaIndex}][docente_id]" class="w-full px-2 py-1.5 border border-gray-300 rounded shadow-sm focus:ring-indigo-500 text-sm bg-white" required>
                            ${opcionesDocentes}
                        </select>
                    </div>
                    <div class="flex-1 w-full">
                        <label class="text-[10px] text-gray-500 font-bold uppercase mb-1 block">Materia</label>
                        <input type="text" name="materias[${materiaIndex}][nombre]" placeholder="Nombre exacto" class="w-full px-2 py-1.5 border border-gray-300 rounded shadow-sm focus:ring-indigo-500 text-sm" required>
                    </div>
                    <div class="flex-[0.7] w-full relative">
                        <label class="text-[10px] text-gray-500 font-bold uppercase mb-1 block">Horario</label>
                        <div class="flex gap-2 items-center">
                            <input type="text" name="materias[${materiaIndex}][horario]" placeholder="Ej. L-X-V 9:00" class="w-full px-2 py-1.5 border border-gray-300 rounded shadow-sm focus:ring-indigo-500 text-sm">
                            <button type="button" onclick="eliminarMateria(${materiaIndex})" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-1 rounded transition-colors" title="Eliminar Fila">
                                <i class="ph ph-trash text-lg"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', html);
            materiaIndex++;
        }

        function eliminarMateria(index) {
            document.getElementById('materia_row_' + index).remove();
        }
    </script>
@endsection
