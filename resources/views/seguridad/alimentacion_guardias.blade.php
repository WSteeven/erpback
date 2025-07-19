<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Alimentación</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: center; }
        th { background-color: #f0f0f0; }
        h3, h4 { text-align: center; margin: 0; }
        .total { font-weight: bold; text-align: right; padding-top: 15px; }
    </style>
</head>
<body>

    <h3>REPORTE DE ALIMENTACIÓN DE GUARDIAS PDF</h3>
    <p><strong>Guardia:</strong> {{ $guardia }}</p>
    <p><strong>Periodo:</strong> {{ $fecha_inicio }} al {{ $fecha_fin }}</p>

    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Zona</th>
                <th>Jornadas</th>
                <th>Valor ($)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($detalle as $r)
                <tr>
                    <td>{{ $r['fecha'] }}</td>
                    <td>{{ $r['zona'] ?? '-' }}</td>
                    <td>{{ implode(', ', $r['jornadas']) }}</td>
                    <td>{{ number_format($r['monto'], 2, '.', '') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        TOTAL ALIMENTACIÓN: <strong>${{ number_format($monto_total, 2, '.', '') }}</strong>
    </div>

</body>
</html>
