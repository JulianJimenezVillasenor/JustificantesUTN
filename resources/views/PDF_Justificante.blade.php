<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        /* Dimensiones tipo Carta */
        @page { size: letter; margin: 0; }
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            background-color: #f4f4f4; 
            padding: 30px; 
            display: flex; 
            justify-content: center; 
        }

        .document-page {
            background: white;
            width: 700px;
            margin: 0 auto;
            padding: 40px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border: 1px solid #ddd;
            position: relative;
        }

        /* Encabezado  */
        .header {
            border-bottom: 3px solid #004d3d;
            padding-bottom: 15px;
            margin-bottom: 25px;
            width: 100%;
            position: relative; /* Para que el logo no desplace el centro */
        }

        .header-logo { 
            position: absolute; /* Sacamos el logo del flujo para que no afecte el centro */
            top: 0;
            left: 0;
        }

        .header-text { 
            width: 100%;
            text-align: center; /* Ahora el centro es el centro real de la página */
            color: #004d3d;
        }

        .header-text h1 { 
            margin: 0; 
            font-size: 19px; 
            text-transform: uppercase; 
            letter-spacing: 0.5px;
            font-weight: 900;
        }

        .header-text p { 
            margin: 1px 0; 
            font-size: 12px; 
            font-weight: 600; 
            line-height: 1.2;
        }

        /* Sección QR y Folio */
        .meta-info {
            position: absolute;
            top: 40px;
            right: 40px;
            text-align: center;
        }
        .folio-box {
            background: #004d3d;
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 14px;
            margin-top: 5px;
        }

        /* Cuerpo del justificante */
        .title {
            text-align: center;
            text-decoration: underline;
            font-size: 16px;
            font-weight: black;
            margin-bottom: 30px;
            color: #333;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }
        .data-table td {
            padding: 12px 15px;
            border: 1px solid #e0e0e0;
            font-size: 13px;
        }
        .label {
            background-color: #f9f9f9;
            font-weight: bold;
            color: #555;
            width: 30%;
        }

        /* Firmas */
        .signatures-container {
            margin-top: 60px;
            width: 100%;
            display: table;
        }
        .signature-slot {
            display: table-cell;
            width: 50%;
            text-align: center;
            vertical-align: bottom;
        }
        .signature-line {
            border-top: 1px solid #000;
            width: 80%;
            margin: 0 auto;
            padding-top: 8px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .signature-img {
            max-height: 70px;
            margin-bottom: -10px;
        }

        /* Marca de agua (Opcional) */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 80px;
            color: rgba(0, 77, 61, 0.05);
            font-weight: bold;
            z-index: 0;
            white-space: nowrap;
            pointer-events: none;
        }
    </style>
</head>
<body>

<div class="document-page">
    <div class="watermark">UT NAYARIT</div>

    <div class="header">
        <div class="header-logo">
            @php
                $logoPath = public_path('img/logo-ut.png');
                $logoData = file_exists($logoPath) ? base64_encode(file_get_contents($logoPath)) : '';
            @endphp
            @if($logoData)
                <img src="data:image/png;base64,{{ $logoData }}" width="100">
            @endif
        </div>

        <div class="header-text">
            <h1>Universidad Tecnológica de Nayarit</h1>
            <p>Secretaría Académica | Departamento de Servicios Escolares</p>
            <p>Subdirección de Tutorías</p>
        </div>
    </div>

    <div class="meta-info">
        {!! QrCode::size(70)->margin(1)->generate(route('validar.publico', $justificante->id)) !!}
        <div class="folio-box">FOLIO: #{{ str_pad($justificante->id, 5, '0', STR_PAD_LEFT) }}</div>
    </div>

    <div class="title">JUSTIFICANTE ACADÉMICO OFICIAL</div>

    <table class="data-table">
        <tr>
            <td class="label">NOMBRE DEL ALUMNO</td>
            <td style="text-transform: uppercase; font-weight: bold;">{{ $justificante->nombre_alumno }}</td>
        </tr>
        <tr>
            <td class="label">GRUPO / CARRERA</td>
            <td>{{ $justificante->grupo }} - Ingeniería en Desarrollo de Software</td>
        </tr>
        <tr>
            <td class="label">FECHA JUSTIFICADA</td>
            <td style="color: #d32f2f; font-weight: bold;">
                {{ \Carbon\Carbon::parse($justificante->fecha)->translatedFormat('l d \d\e F \d\e Y') }}
            </td>
        </tr>
        <tr>
            <td class="label">TIPO DE FALTA</td>
            <td>{{ $justificante->tipo_falta }}</td>
        </tr>
        <tr>
            <td class="label">MOTIVO / CAUSA</td>
            <td style="font-style: italic;">"{{ $justificante->motivo }}"</td>
        </tr>
        <tr>
            <td class="label">HORARIO</td>
            <td>{{ $justificante->horas ?? 'JORNADA COMPLETA' }}</td>
        </tr>
    </table>

    <p style="font-size: 11px; color: #666; text-align: justify; line-height: 1.4;">
        El presente documento avala la inasistencia del alumno arriba mencionado, habiendo presentado las evidencias correspondientes ante su tutor de grupo. Se solicita a los docentes de las asignaturas afectadas brindar las facilidades para la entrega de trabajos o realización de evaluaciones.
    </p>

    <div class="signatures-container">
        <div class="signature-slot">
            @php
                $firmaTutor = storage_path('app/public/firmas/tutor.png');
                $tutorData = file_exists($firmaTutor) ? base64_encode(file_get_contents($firmaTutor)) : null;
            @endphp
            @if($tutorData)
                <img src="data:image/png;base64,{{ $tutorData }}" class="signature-img"><br>
            @else
                <div style="height: 60px;"></div>
            @endif
            <div class="signature-line">Firma Tutor Académico<br><small>{{ $justificante->nombre_tutor }}</small></div>
        </div>

        <div class="signature-slot">
            @if($justificante->firma_docente)
                @php
                    $firmaDoc = storage_path('app/public/firmas/docente.png');
                    $docData = file_exists($firmaDoc) ? base64_encode(file_get_contents($firmaDoc)) : null;
                @endphp
                @if($docData)
                    <img src="data:image/png;base64,{{ $docData }}" class="signature-img"><br>
                @else
                    <div style="height: 60px;"></div>
                @endif
            @else
                <div style="height: 60px; border: 1px dashed #ccc; width: 80%; margin: 0 auto 10px auto; line-height: 60px; font-size: 9px; color: #ccc;">PENDIENTE DE FIRMA</div>
            @endif
            <div class="signature-line">Sello/Firma Docente Asignatura</div>
        </div>
    </div>

    <div style="margin-top: 50px; text-align: center; font-size: 9px; color: #aaa; border-top: 1px solid #eee; padding-top: 10px;">
        Este documento es una representación digital generada por el Sistema de Justificantes UTN. <br>
        La validez puede verificarse escaneando el código QR superior.
    </div>
</div>

</body>
</html>