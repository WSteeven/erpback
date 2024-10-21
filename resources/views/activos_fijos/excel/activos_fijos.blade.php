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
                <th rowspan="2" valign="center" bgcolor="#c5d9f1">{{ 'PRODUCTO' }}</th>
                <th rowspan="2" valign="center" bgcolor="#c5d9f1">{{ 'DESCRIPCIÓN' }}</th>
                <th rowspan="2" valign="center" bgcolor="#c5d9f1">{{ 'CLIENTE' }}</th>
                <th rowspan="2" valign="center" bgcolor="#c5d9f1">{{ 'SERIAL' }}</th>
                <th rowspan="2" valign="center" bgcolor="#c5d9f1">{{ 'CÓDIGO ACTIVO FIJO' }}</th>
                <th rowspan="1" colspan="5" valign="center" bgcolor="#c5d9f1">{{ 'EGRESOS' }}</th>
                <th rowspan="1" colspan="3" valign="center" bgcolor="#c5d9f1">{{ 'INGRESOS' }}</th>|
            </tr>

            <tr>
                <th rowspan="1" valign="center" bgcolor="#c5d9f1">{{ 'CUSTODIO' }}</th>
                <th rowspan="1" valign="center" bgcolor="#c5d9f1">{{ 'CANTIDAD EN STOCK' }}</th>
                <th rowspan="1" valign="center" bgcolor="#c5d9f1">{{ 'FECHA DE DESPACHO' }}</th>
                <th rowspan="1" valign="center" bgcolor="#c5d9f1">{{ 'CANTÓN' }}</th>
                <th rowspan="1" valign="center" bgcolor="#c5d9f1">{{ 'TRANSACCION EGRESO' }}</th>
                <th rowspan="1" valign="center" bgcolor="#c5d9f1">{{ 'FACTURA COMPRA' }}</th>
                <th rowspan="1" valign="center" bgcolor="#c5d9f1">{{ 'FECHA DE INGRESO' }}</th>
                <th rowspan="1" valign="center" bgcolor="#c5d9f1">{{ 'TRANSACCION INGRESO' }}</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($reporte as $producto)
                <tr>
                    <td valign="center">{{ $producto['producto'] }}</td>
                    <td valign="center">{{ $producto['descripcion'] }}</td>
                    <td valign="center">{{ $producto['cliente'] }}</td>
                    <td valign="center">{{ $producto['serial'] }}</td>
                    <td valign="center">{{ $producto['codigo_activo_fijo'] }}</td>
                    <td valign="center">{{ $producto['nombres'] . ' ' . $producto['apellidos'] }}
                    </td>
                    <td valign="center">{{ $producto['cantidad_stock'] }}</td>
                    <td valign="center">
                        {{ isset($producto['transaccion_egreso']) ? $producto['transaccion_egreso']['created_at'] : null }}
                    </td>
                    <td valign="center">{{ $producto['canton'] }}</td>
                    <td valign="center">
                        {{ isset($producto['transaccion_egreso']) ? $producto['transaccion_egreso']['id'] : null }}
                    </td>
                    <td valign="center">
                        {{ isset($producto['transaccion_ingreso']) ? $producto['transaccion_ingreso']['comprobante'] : null }}
                    </td>
                    <td valign="center">
                        {{ isset($producto['transaccion_ingreso']) ? $producto['transaccion_ingreso']['created_at'] : null }}
                    </td>
                    <td valign="center">
                        {{ isset($producto['transaccion_ingreso']) ? $producto['transaccion_ingreso']['id'] : null }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
