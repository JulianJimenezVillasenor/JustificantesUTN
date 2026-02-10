@extends('Plantilla')

@section('menu')
    <ul>
        <li><a href="/">Inicio</a></li>
    </ul>
@endsection

@section('content')
<div class="flex flex-col items-center justify-center min-h-[80vh]">
    <div class="w-full max-w-md bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
        <div class="bg-[#004d3d] p-8 text-center">
            <div class="inline-block p-3 bg-white/10 rounded-full mb-4">
                <i class="ph ph-user-circle text-5xl text-white"></i>
            </div>
            <h2 class="text-2xl font-bold text-white uppercase tracking-wider">Acceso al Sistema</h2>
            <p id="feedback" class="text-green-100 text-xs mt-2">Ingresa tus credenciales institucionales</p>
        </div>

        <div class="p-8">
            <form id="formLogin" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Correo / Matrícula</label>
                    <input type="email" name="email" required class="w-full px-4 py-3 border rounded-lg bg-gray-50 outline-none focus:ring-2 focus:ring-[#006d5b]">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Contraseña</label>
                    <input type="password" name="password" required class="w-full px-4 py-3 border rounded-lg bg-gray-50 outline-none focus:ring-2 focus:ring-[#006d5b]">
                </div>

                <button type="submit" id="btnEntrar" class="w-full bg-[#004d3d] text-white font-bold py-3 rounded-lg hover:bg-[#00362b] transition-all">
                    ENTRAR AL PANEL
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('formLogin').addEventListener('submit', async (e) => {
    e.preventDefault();
    const btn = document.getElementById('btnEntrar');
    const feedback = document.getElementById('feedback');

    btn.innerText = 'VALIDANDO...';
    btn.disabled = true;

    const response = await fetch("{{ route('login.check') }}", {
        method: 'POST',
        body: new FormData(e.target),
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    });

    const data = await response.json();

    if (data.success) {
        feedback.innerText = "¡Bienvenido " + data.role + "!";
        window.location.href = data.redirect;
    } else {
        alert(data.message);
        btn.innerText = 'ENTRAR AL PANEL';
        btn.disabled = false;
    }
});
</script>
@endsection
