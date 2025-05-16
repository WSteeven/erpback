<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>

<body>
    <table
        style="color:#000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px;margin-top: 20px;">
        <thead>
            <tr>
                <th rowspan="1" valign="center" bgcolor="#daf1f3">{{ 'FECHA/HORA' }}</th>
                <th rowspan="1" valign="center" bgcolor="#daf1f3">{{ 'PRODUCTO' }}</th>
                <th rowspan="1" valign="center" bgcolor="#daf1f3">{{ 'CLIENTE' }}</th>
                <th rowspan="1" valign="center" bgcolor="#daf1f3">{{ 'EVENTO' }}</th>
                <th rowspan="1" valign="center" bgcolor="#daf1f3">{{ 'EMPLEADO EJECUTOR' }}</th>
                <th rowspan="1" valign="center" bgcolor="#daf1f3">{{ 'TRANSACCIÓN' }}</th>
                <th rowspan="1" valign="center" bgcolor="#daf1f3">{{ 'DESCRIPCIÓN' }}</th>
                <th rowspan="1" valign="center" bgcolor="#daf1f3">{{ 'MOVIMIENTO' }}</th>
                <th rowspan="1" valign="center" bgcolor="#daf1f3">{{ 'CANTIDAD ANTERIOR' }}</th>
                <th rowspan="1" valign="center" bgcolor="#daf1f3">{{ 'CANTIDAD ACTUAL' }}</th>
                <th rowspan="1" valign="center" bgcolor="#daf1f3">{{ 'CANTIDAD AFECTADA (Diferencia)' }}</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($reporte as $producto)
                <tr>
                    <td valign="center">{{ \Carbon\Carbon::parse($producto['fecha_hora'])->format('Y-m-d') }}</td>
                    <td valign="center">{{ $producto['detalle_producto'] }}</td>
                    <td valign="center">{{ $producto['cliente'] }}</td>
                    <td valign="center">{{ $producto['evento'] }}</td>
                    <td valign="center">{{ $producto['empleado'] }}</td>
                    <td valign="center">
                        {{ isset($producto['transaccion']) ? $producto['transaccion'] . ' ' . $producto['entidad_id'] : '' }}
                    </td>
                    <td valign="center">{{ isset($producto['descripcion']) ? $producto['descripcion'] : '' }}</td>
                    <td valign="center">{{ $producto['movimiento'] }}</td>
                    <td valign="center">{{ $producto['cantidad_anterior'] }}</td>
                    <td valign="center" bgcolor="#c6efce"><b>{{ $producto['cantidad_actual'] }}</b></td>
                    <td valign="center">{{ $producto['cantidad_afectada'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
