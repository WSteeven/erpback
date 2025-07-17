<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Alimentación</title>
</head>
<body>

    <h3 style="text-align:center; margin-bottom: 0;">REPORTE DE ALIMENTACIÓN DE GUARDIAS</h3>
    <p><strong>Guardia:</strong> {{ $total['guardia'] }}</p>
    <p><strong>Periodo:</strong> {{ $fecha_inicio }} al {{ $fecha_fin }}</p>

    <table border="1" cellspacing="0" cellpadding="5" style="width:100%; margin-top: 15px;">
        <thead style="background-color: #f0f0f0;">
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

    <br>
    <h4 style="text-align:right;">TOTAL ALIMENTACIÓN: ${{ number_format($total['monto_total'], 2, '.', '') }}</h4>

</body>
</html>
