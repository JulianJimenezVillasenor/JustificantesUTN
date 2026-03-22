<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Inasistencias</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Reporte de Inasistencias</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Tipo de Falta</th>
                <th>Fecha</th>
                <th>Motivo</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($justificantes as $justificante)
            <tr>
                <td>{{ $justificante->id }}</td>
                <td>{{ $justificante->user->name }}</td>
                <td>{{ $justificante->tipo_falta }}</td>
                <td>{{ $justificante->fecha }}</td>
                <td>{{ $justificante->motivo }}</td>
                <td>{{ $justificante->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
