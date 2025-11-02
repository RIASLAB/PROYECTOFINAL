<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Citas</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h1 { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #333; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>📅 Listado de Citas - Veterinaria</h1>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Mascota</th>
                <th>Motivo</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Observaciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($citas as $cita)
                <tr>
                    <td>{{ $cita->id }}</td>
                    <td>{{ $cita->mascota->nombre ?? 'Sin nombre' }}</td>
                    <td>{{ $cita->motivo }}</td>
                    <td>{{ $cita->fecha }}</td>
                    <td>{{ $cita->hora }}</td>
                    <td>{{ $cita->observaciones }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align:center;">No hay citas registradas</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
