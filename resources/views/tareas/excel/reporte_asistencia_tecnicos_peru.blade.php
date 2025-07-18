<!DOCTYPE html>
<html lang="es">
@php
    use Src\Shared\Utils;
       $fecha = new Datetime();
@endphp

<head>
    <meta charset="UTF-8">
    <title>Reporte de Asistencia</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }


        .encabezado img {
            height: 60px;
        }


        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        th, td {
            border: 1px solid #000;
            padding: 5px 10px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
    </style>
</head>
<body>

<table
    style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:18px;">
    <tr class="row" style="width:auto">
        <td colspan="6" style="width: 100%; text-align: center;">
            <b style="font-size: 75%;">{{$configuracion->razon_social}}</b>
        </td>
    </tr>
    <tr class="row" style="width:auto">
        <td colspan="6" style="width: 100%; text-align: center;">
            <b style="font-size: 75%;">{{$configuracion->ruc}}</b>
        </td>
    </tr>
    <tr class="row" style="width:auto">
        <td style="width: 10%;">
            <div class="col-md-3"><img src="{{ Utils::getImagePath($configuracion->logo_claro) }}" width="90"
                                       alt="logo"></div>
        </td>
        <td colspan="5" style="width: 100%; text-align: center;">
            <b style="font-size: 75%;">{{$titulo}}</b>
        </td>
    </tr>
</table>

<table>
    <thead>
    <tr>
        <th style="font-weight: bold">FECHA</th>
        <th style="font-weight: bold">EMPLEADO</th>
        <th style="font-weight: bold">HORA DE ENTRADA</th>
        <th style="font-weight: bold">HORA DE INICIO DE REFRIGERIO</th>
        <th style="font-weight: bold">HORA FIN REFRIGERIO</th>
        <th style="font-weight: bold">HORA DE SALIDA</th>
    </tr>
    </thead>
    <tbody>
    @foreach($registros as $registro)
        <tr>
            <td>{{ $registro['fecha'] }}</td>
            <td>{{ $registro['empleado'] }}</td>
            <td>{{ $registro['entrada'] ?? '-' }}</td>
            <td>{{ $registro['almuerzo_inicio'] ?? '-' }}</td>
            <td>{{ $registro['almuerzo_fin'] ?? '-' }}</td>
            <td>{{ $registro['salida'] ?? '-' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

</body>
</html>
