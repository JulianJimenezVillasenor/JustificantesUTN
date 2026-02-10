<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UT Nayarit - Sistema de Justificantes</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="estilos/index.css">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link rel="icon" href="imagenes/icono.ico">
</head>
<body class="bg-gray-100 font-sans">
    <div id="fb-root"></div>
        <script async defer crossorigin="anonymous"
            src="https://connect.facebook.net/es_LA/sdk.js#xfbml=1&version=v24.0&appId=APP_ID">
        </script>
    <header class="bg-[#004d3d] text-white p-4 flex justify-between items-center shadow-md">
        <div class="flex items-center gap-4">
            <span class="font-bold text-lg">UT Nayarit | Sistema de Justificantes</span>
        </div>
        <nav class="main-nav">
            @yield('menu')

        </nav>
    </header>

    <main class="container mx-auto py-8 px-4">
        @yield('content')
    </main>


</body>
</html>
