@extends('Plantilla')

@section('menu')
<div class="flex items-center gap-4 px-4 py-1 bg-white/10 rounded-2xl border border-white/5 backdrop-blur-sm">
    <div class="flex flex-col items-end border-r border-white/20 pr-4 hidden sm:flex">
        <span class="text-[10px] uppercase font-bold tracking-widest text-emerald-300 leading-none">Sesión Activa</span>
        <span class="text-white text-xs font-semibold">
            <!--{{ Auth::user('admin')->name ?? 'Jiménez Villaseñor Julián' }}-->
            {{ Auth::user('admin')->name ?? 'Administrador' }}
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

    <!-- Crear Usuario -->
    <div class="bg-white p-6 rounded-lg shadow-md mb-6">
        <h2 class="text-xl font-semibold mb-4">Crear Nuevo Usuario</h2>
        <form action="{{ route('admin.createUser') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Nombre</label>
                <input type="text" name="name" id="name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
                <input type="password" name="password" id="password" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
            </div>
            <div class="mb-4">
                <label for="role" class="block text-sm font-medium text-gray-700">Rol</label>
                <select name="role" id="role" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required onchange="mostrarCamposAlumno()">
                    <option value="alumno">Alumno</option>
                    <option value="tutor">Tutor</option>
                    <option value="docente">Docente</option>
                </select>
            </div>

            <!-- Campos adicionales para Alumno -->
            <div id="campos-alumno" style="display: none;">
                <div class="mb-4">
                    <label for="tutor_id" class="block text-sm font-medium text-gray-700">Tutor Asignado</label>
                    <select name="tutor_id" id="tutor_id" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
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
                    <label class="block text-sm font-medium text-gray-700">Docentes Asignados</label>
                    <div class="mt-2 space-y-2 border border-gray-300 rounded-md p-3 max-h-48 overflow-y-auto">
                        @php
                            $docentes = \App\Models\User::where('role', 'docente')->get();
                        @endphp
                        @foreach($docentes as $docente)
                            <div class="flex items-center">
                                <input type="checkbox" name="docentes[]" id="docente_{{ $docente->id }}" value="{{ $docente->id }}" class="rounded">
                                <label for="docente_{{ $docente->id }}" class="ml-2 text-sm text-gray-700">{{ $docente->name }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Crear Usuario</button>
        </form>
    </div>

    <!-- Generar Reporte -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold mb-4">Generar Reporte de Inasistencias</h2>
        <a href="{{ route('admin.generateReport') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Generar y Descargar Reporte</a>
    </div>
</div>

<script>
function mostrarCamposAlumno() {
    const rol = document.getElementById('role').value;
    const camposAlumno = document.getElementById('campos-alumno');
    if (rol === 'alumno') {
        camposAlumno.style.display = 'block';
    } else {
        camposAlumno.style.display = 'none';
    }
}
</script>
@endsection
