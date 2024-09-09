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
                {{-- <th valign="center" bgcolor="#daf1f3">{{ 'FECHA/HORA' }}</th> --}}
                <th valign="center" bgcolor="#daf1f3">{{ 'PRODUCTO' }}</th>
                <th valign="center" bgcolor="#daf1f3">{{ 'CLIENTE' }}</th>
                <th valign="center" bgcolor="#daf1f3">{{ 'TAREA' }}</th>
                <th valign="center" bgcolor="#daf1f3">{{ 'DESCRIPCIÓN' }}</th>
                <th valign="center" bgcolor="#daf1f3">{{ 'CANTIDAD ACTUAL' }}</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($reporte as $producto)
                <tr>
                    {{-- <td valign="center">{{ $producto['fecha_hora'] }}</td> --}}
                    <td valign="center">{{ $producto['detalle_producto'] }}</td>
                    <td valign="center">{{ $producto['cliente'] }}</td>
                    <td valign="center">{{ $producto['tarea'] }}</td>
                    <td valign="center">{{ $producto['titulo_tarea'] }}</td>
                    <td valign="center" bgcolor="#c6efce"><b>{{ $producto['cantidad_utilizada'] }}</b></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
