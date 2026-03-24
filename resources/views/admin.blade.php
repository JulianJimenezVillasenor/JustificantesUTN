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
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-6">Panel de Administrador</h1>

        <!-- Alertas de Éxito o Error -->
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm">
                <p class="font-bold">{{ session('success') }}</p>
            </div>
        @endif

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

        <!-- Crear Usuario -->
        <div class="bg-white p-6 rounded-lg shadow-md mb-6">
            <h2 class="text-xl font-semibold mb-4">Crear Nuevo Usuario</h2>
            <form action="{{ route('admin.createUser') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Nombre</label>
                    <input type="text" name="name" id="name"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        required>
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        required>
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
                    <input type="password" name="password" id="password"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        required>
                </div>
                <div class="mb-4">
                    <label for="role" class="block text-sm font-medium text-gray-700">Rol</label>
                    <select name="role" id="role"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        required onchange="mostrarCamposAlumno()">
                        <option value="alumno">Alumno</option>
                        <option value="tutor">Tutor</option>
                        <option value="docente">Docente</option>
                    </select>
                </div>

                <div id="campos-firma" style="display: none;" class="mb-4">
                    <label for="firma" class="block text-sm font-medium text-gray-700">Firma o Sello (Opcional - Imagen
                        JPG/PNG)</label>
                    <input type="file" name="firma" id="firma" accept="image/png, image/jpeg"
                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                </div>

                <!-- Campos adicionales para Alumno -->
                <div id="campos-alumno" style="display: none;">
                    <div class="mb-4">
                        <label for="grupo" class="block text-sm font-medium text-gray-700">Grupo (Opcional)</label>
                        <input type="text" name="grupo" id="grupo" placeholder="Ej. A, B, C..."
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500">
                    </div>
                    <div class="mb-4">
                        <label for="tutor_id" class="block text-sm font-medium text-gray-700">Tutor Asignado</label>
                        <select name="tutor_id" id="tutor_id"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">-- Seleccionar Tutor --</option>
                            @php
                                $tutores = \App\Models\User::where('role', 'tutor')->get();
                            @endphp
                            @foreach($tutores as $tutor)
                                <option value="{{ $tutor->id }}">{{ $tutor->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Carga Horaria (Materias y
                            Horarios)</label>
                        <div id="materias-container" class="space-y-3">
                            <!-- Filas dinámicas generadas por JS -->
                        </div>
                        <button type="button" onclick="agregarMateria()"
                            class="mt-3 bg-indigo-50 border border-indigo-200 text-indigo-700 hover:bg-indigo-100 font-semibold py-2 px-4 rounded text-sm flex items-center gap-2 transition-colors">
                            <i class="ph ph-plus-circle text-lg"></i> Añadir Materia
                        </button>
                    </div>
                </div>

                <!-- Campos adicionales para Tutor -->
                <div id="campos-tutor" style="display: none;" class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="es_docente" id="es_docente" value="1"
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-offset-0 focus:ring-indigo-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700 font-bold">Este tutor también imparte clases (Asignarlo como
                            Docente)</span>
                    </label>
                </div>

                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Crear
                    Usuario</button>
            </form>
        </div>

        <!-- Generar Reporte -->
        <div class="bg-white p-6 rounded-lg shadow-md mb-6">
            <h2 class="text-xl font-semibold mb-4">Generar Reporte de Inasistencias</h2>
            <a href="{{ route('admin.generateReport') }}"
                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded inline-block">Generar y
                Descargar
                Reporte</a>
        </div>

        <!-- Lista de Usuarios Recientes -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4">Gestión de Usuarios Registrados</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm border-collapse mb-4">
                    <thead class="bg-gray-100 text-gray-600 uppercase text-[10px]">
                        <tr>
                            <th class="px-4 py-2 border-b">ID</th>
                            <th class="px-4 py-2 border-b">Nombre</th>
                            <th class="px-4 py-2 border-b">Email</th>
                            <th class="px-4 py-2 border-b">Rol</th>
                            <th class="px-4 py-2 border-b">Registro</th>
                            <th class="px-4 py-2 border-b text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($usuarios_recientes as $usuario)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-2 border-b">{{ $usuario->id }}</td>
                                <td class="px-4 py-2 border-b font-medium text-gray-900">{{ $usuario->name }}</td>
                                <td class="px-4 py-2 border-b text-gray-600">{{ $usuario->email }}</td>
                                <td class="px-4 py-2 border-b">
                                    <span
                                        class="px-2 py-1 rounded-full text-[10px] font-bold bg-blue-100 text-blue-700 uppercase">
                                        {{ $usuario->role }}
                                    </span>
                                </td>
                                <td class="px-4 py-2 border-b text-gray-500">{{ $usuario->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-4 py-2 border-b text-center">
                                    <div class="flex items-center gap-2 justify-center">
                                        <a href="{{ route('admin.editUser', $usuario->id) }}"
                                            class="text-blue-600 hover:text-blue-800 font-bold p-1 rounded hover:bg-blue-50 transition-colors"
                                            title="Editar Usuario">
                                            <i class="ph ph-pencil-simple text-lg"></i>
                                        </a>
                                        @if(auth()->id() !== $usuario->id)
                                            <form action="{{ route('admin.destroyUser', $usuario->id) }}" method="POST"
                                                onsubmit="return confirm('¿Estás seguro de que deseas eliminar este usuario de forma permanente? Esta acción borrará también sus justificantes y registros relacionados asociados.');"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-600 hover:text-red-800 font-bold p-1 rounded hover:bg-red-50 transition-colors"
                                                    title="Eliminar Usuario">
                                                    <i class="ph ph-trash text-lg"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-4">
                    {{ $usuarios_recientes->links() }}
                </div>
            </div>
        </div>
    </div>

    <script>
        function mostrarCamposAlumno() {
            const rol = document.getElementById('role').value;
            const camposAlumno = document.getElementById('campos-alumno');
            const camposTutor = document.getElementById('campos-tutor');
            const camposFirma = document.getElementById('campos-firma');

            camposAlumno.style.display = (rol === 'alumno') ? 'block' : 'none';
            camposTutor.style.display = (rol === 'tutor') ? 'block' : 'none';
            if (camposFirma) camposFirma.style.display = (rol === 'tutor' || rol === 'docente') ? 'block' : 'none';
        }

        // --- Lógica del Constructor de Materias ---
        let materiaIndex = 0;
        @php
            $docentesList = \App\Models\User::where('role', 'docente')->orWhere(function ($q) {
                $q->where('role', 'tutor')->where('es_docente', true);
            })->select('id', 'name', 'role')->get();
        @endphp
            const docentesList = @json($docentesList);
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
                                    <button type="button" onclick="eliminarMateria(${materiaIndex})" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-1 rounded transition-colors" title="Eliminar Objeto">
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
        // Inicializar el sistema si hay errores de validación (redibujar materias... no implementado para Olds en esta versión base, pero agregamos 1 materia default si está vacío)
              window.onload = function() {
            mo  strarCamposAlumno();
            if(document.getElementById('materias-container').children.length === 0) {
                agregarMateria(); // Arrancar con al menos 1
            }
    };
            </script>
@endsection