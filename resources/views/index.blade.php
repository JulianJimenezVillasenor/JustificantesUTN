@extends('Plantilla')

@section('menu')
    <ul>
        <li><a href="/login">Iniciar Sesion</a></li>
    </ul>
@endsection

@section('content')
    <div class="mb-8 text-center md:text-left">
        <h1 class="text-2xl font-bold text-gray-800"><center> Bienvenido al Sistema de Gestión de Justificantes - UT Nayarit </center></h1>
    </div>

    <div class="flex flex-wrap md:flex-nowrap gap-6">

        <div class="w-full md:w-2/3 order-1">
            <div id="mapa"></div>
            <div class="contenedor_descripcion mt-2">
                <p class="text-center font-semibold text-gray-700">Ubicación de la Universidad Tecnológica de Nayarit</p>
            </div>
        </div>

        <div class="w-full md:w-1/3 order-2 flex justify-center">
            <div class="fb-page"
                data-href="https://www.facebook.com/UTNAY"
                data-tabs="timeline"
                data-width="600"
                data-height="600"
                data-small-header="false"
                data-adapt-container-width="true"
                data-hide-cover="false"
                data-show-facepile="true">
                <blockquote cite="https://www.facebook.com/UTNAY" class="fb-xfbml-parse-ignore">
                    <a href="https://www.facebook.com/UTNAY">Universidad Tecnológica de Nayarit</a>
                </blockquote>
            </div>
        </div>

    </div>

    <style>
        #mapa {
            width: 95%;
            height: 500px;
            border-radius: 12px;
            border: 3px solid #004aad;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        }

        .fb-page {
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    </style>

    <script>
        //Funcion que permite inicializar y colocar el mapa
        function initMap(){
            //Coordenadas de la universidad
            var utn = {lat: 21.424081107914333, lng: -104.89841274317065}
            //Ubicar en el mapa la variable utn
            var map = new google.maps.Map(document.getElementById('mapa'),
                {zoom: 15, center: utn, mapTypeId: 'satellite'});
            //Posicionar el marcador en la UTN
            var marker = new google.maps.Marker({position: utn, map:map,
                title:"Universidad Tecnológica de Nayarit"});
            //Ventana de informacion al dar clic en el marcador
            var infoWindow = new google.maps.infoWindow({
                content: `
                    <strong>Universidad Tecnológica de Nayarit </strong><br>
                    Tepic, Nayarit<br>
                    TSU en Desarrollo de Software Multiplataforma
                    `
            });

            marker.addListener('click', function(){
                infoWindow.open(map, marker);
            });


        }//fin funcion initMap
    </script>

    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCZk5mbtYUfveRplhc8smGNe9KB3wry9Fk&callback=initMap">
    </script>
@endsection
