<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reporte Institucional de Inasistencias</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
            font-size: 12px;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #004d3d;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .header h1 {
            color: #004d3d;
            margin: 0;
            font-size: 22px;
            text-transform: uppercase;
        }

        .header p {
            margin: 5px 0 0 0;
            font-size: 11px;
            color: #555;
        }

        .summary-box {
            background-color: #f8fbfa;
            border: 1px solid #d1e2df;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        .summary-box h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            color: #004d3d;
        }

        .stats-table {
            width: 100%;
            margin-bottom: 0;
        }

        .stats-table td {
            padding: 5px;
            text-align: center;
            font-weight: bold;
            border: none;
        }

        .stats-label {
            color: #555;
            font-size: 11px;
        }

        .stats-val {
            font-size: 18px;
            color: #004d3d;
        }

        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .main-table th,
        .main-table td {
            border: 1px solid #ddd;
            padding: 8px 10px;
            text-align: left;
        }

        .main-table th {
            background-color: #004d3d;
            color: #fff;
            font-size: 11px;
            text-transform: uppercase;
        }

        .main-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .status-badge {
            font-weight: bold;
            font-size: 10px;
        }

        .status-ACEPTADO {
            color: #059669;
        }

        .status-RECHAZADO {
            color: #dc2626;
        }

        .status-PENDIENTE {
            color: #d97706;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #777;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Universidad Tecnológica de Nayarit</h1>
        <p>Reporte Oficial de Inasistencias de Alumnos</p>
        <p>Fecha de Generación: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    @php
        $total = $justificantes->count();
        $aceptados = $justificantes->where('status', 'ACEPTADO')->count();
        $pendientes = $justificantes->where('status', 'PENDIENTE')->count();
        $rechazados = $justificantes->where('status', 'RECHAZADO')->count();
    @endphp

    <div class="summary-box">
        <h3>Resumen de Estados</h3>
        <table class="stats-table">
            <tr>
                <td>
                    <div class="stats-label">TOTAL EMITIDOS</div>
                    <div class="stats-val">{{ $total }}</div>
                </td>
                <td>
                    <div class="stats-label" style="color: #059669;">ACEPTADOS</div>
                    <div class="stats-val" style="color: #059669;">{{ $aceptados }}</div>
                </td>
                <td>
                    <div class="stats-label" style="color: #d97706;">PENDIENTES</div>
                    <div class="stats-val" style="color: #d97706;">{{ $pendientes }}</div>
                </td>
                <td>
                    <div class="stats-label" style="color: #dc2626;">RECHAZADOS</div>
                    <div class="stats-val" style="color: #dc2626;">{{ $rechazados }}</div>
                </td>
            </tr>
        </table>
    </div>

    <table class="main-table">
        <thead>
            <tr>
                <th width="8%">Folio</th>
                <th width="22%">Alumno</th>
                <th width="15%">Tipo de Falta</th>
                <th width="15%">Fecha Inasist.</th>
                <th width="30%">Motivo</th>
                <th width="10%">Estado</th>
            </tr>
        </thead>
        <tbody>
            @forelse($justificantes as $justificante)
                <tr>
                    <td>#{{ str_pad($justificante->id, 4, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ optional($justificante->user)->name ?? 'Alumno Desconocido' }}</td>
                    <td>{{ $justificante->tipo_falta }}</td>
                    <td>{{ \Carbon\Carbon::parse($justificante->fecha)->format('d/m/Y') }}</td>
                    <td>{{ \Illuminate\Support\Str::limit($justificante->motivo, 40) }}</td>
                    <td>
                        <span class="status-badge status-{{ $justificante->status }}">
                            {{ $justificante->status }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 20px;">No hay reportes de inasistencia registrados.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Documento generado automáticamente por el Sistema de Justificantes UTN.
    </div>
</body>

</html>