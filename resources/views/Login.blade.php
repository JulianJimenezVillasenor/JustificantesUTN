@extends('Plantilla')

@section('menu')
    <ul>
        <li><a href="/">Inicio</a></li>
    </ul>
@endsection

@section('content')
<div class="flex flex-col items-center justify-center min-h-[85vh] px-4">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden border border-gray-100">
        <div class="bg-gradient-to-br from-[#004d3d] to-[#006d5b] p-10 text-center relative">
            <div class="inline-block p-4 bg-white/20 backdrop-blur-md rounded-full mb-4 shadow-inner">
                <i class="ph ph-shield-check text-5xl text-white"></i>
            </div>
            <h2 class="text-2xl font-black text-white uppercase tracking-tighter">UT Nayarit</h2>
            <p id="feedback" class="text-green-100 text-xs font-medium opacity-80">Sistema de Control de Justificantes</p>
        </div>

        <div class="p-8">
            <form id="formLogin" class="space-y-5">
                @csrf
                <div class="relative">
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1 ml-1">Credencial Institucional</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <i class="ph ph-envelope-simple text-xl"></i>
                        </span>
                        <input type="email" name="email" required placeholder="ejemplo@utnayarit.edu.mx"
                               class="w-full pl-10 pr-4 py-3 border-2 border-gray-100 rounded-xl outline-none focus:border-[#004d3d] transition-all text-sm">
                    </div>
                </div>

                <div class="relative">
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1 ml-1">Contraseña</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <i class="ph ph-lock-key text-xl"></i>
                        </span>
                        <input type="password" name="password" required placeholder="••••••••"
                               class="w-full pl-10 pr-4 py-3 border-2 border-gray-100 rounded-xl outline-none focus:border-[#004d3d] transition-all text-sm">
                    </div>
                </div>

                <button type="submit" id="btnEntrar" class="w-full bg-[#004d3d] hover:bg-[#00362b] text-white font-bold py-4 rounded-xl shadow-lg shadow-green-900/20 transition-all flex items-center justify-center gap-2 group">
                    <span>ACCEDER AHORA</span>
                    <i class="ph ph-arrow-right font-bold group-hover:translate-x-1 transition-transform"></i>
                </button>
            </form>

            <div class="mt-8 text-center">
                <p class="text-[10px] text-gray-400 uppercase font-bold italic">¿Problemas con tu acceso? Contacta a Soporte Técnico</p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.getElementById('formLogin').addEventListener('submit', async (e) => {
    e.preventDefault();
    const btn = document.getElementById('btnEntrar');
    const feedback = document.getElementById('feedback');

    // Estado de carga visual
    btn.innerHTML = `<i class="ph ph-circle-notch animate-spin text-xl"></i> VALIDANDO...`;
    btn.disabled = true;

    try {
        const response = await fetch("{{ route('login.check') }}", {
            method: 'POST',
            body: new FormData(e.target),
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });

        const data = await response.json();

        if (data.success) {
            feedback.innerText = "¡Bienvenido de nuevo!";
            // Animación de éxito antes de redirigir
            Swal.fire({
                icon: 'success',
                title: 'Acceso Correcto',
                text: 'Redirigiendo al panel...',
                timer: 1500,
                showConfirmButton: false
            }).then(() => {
                window.location.href = data.redirect;
            });
        } else {
            throw new Error(data.message);
        }
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error de Acceso',
            text: error.message || 'Credenciales incorrectas',
            confirmButtonColor: '#004d3d'
        });
        btn.innerHTML = `<span>ACCEDER AHORA</span> <i class="ph ph-arrow-right font-bold"></i>`;
        btn.disabled = false;
    }
});
</script>
@endsection
