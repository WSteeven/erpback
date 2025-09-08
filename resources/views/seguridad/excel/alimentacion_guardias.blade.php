<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reporte General de Alimentación</title>
</head>

<body>

    <h3 style="text-align:center; margin-bottom: 0;">REPORTE GENERAL DE ALIMENTACIÓN DE GUARDIAS</h3>
    <p><strong>Periodo:</strong> {{ $fecha_inicio ?? '-' }} al {{ $fecha_fin ?? '-' }}</p>

    @foreach(($detalle ?? []) as $guardia)
        <h4>Guardia: {{ $guardia['guardia'] ?? 'SIN NOMBRE' }}</h4>

        <table border="1" cellspacing="0" cellpadding="5" style="width:100%; margin-bottom: 10px;">
            <thead style="background-color: #f0f0f0;">
                <tr>
                    <th>Fecha</th>
                    <th>Zona</th>
                    <th>Jornadas</th>
                    <th>Valor ($)</th>
                </tr>
            </thead>
            <tbody>
                @foreach(($guardia['detalle'] ?? []) as $item)
                    <tr>
                        <td>{{ $item['fecha'] ?? '-' }}</td>
                        <td>{{ $item['zona'] ?? '-' }}</td>
                        <td>
                            {{ is_array($item['jornadas'] ?? null) ? implode(', ', $item['jornadas']) : '-' }}
                        </td>
                        <td>{{ number_format($item['monto'] ?? 0, 2, '.', '') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <p style="text-align:right;">
            <strong>TOTAL GUARDIA:
                ${{ number_format($guardia['monto_total'] ?? 0, 2, '.', '') }}
            </strong>
        </p>
        <br>
    @endforeach

    <h3 style="text-align:right;">
        TOTAL GENERAL: ${{ number_format($total['monto_total'] ?? 0, 2, '.', '') }}
    </h3>

</body>

</html>
