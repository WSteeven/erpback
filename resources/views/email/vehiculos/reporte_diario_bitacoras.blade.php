<!DOCTYPE html>
<html lang="en">
@php
    use Src\Shared\Utils;
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte diario de bitácoras de vehículos</title>

    <style>
        table {
            width: 50%;
            border-collapse: collapse;
            border: 1px solid #000;
        }

        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>

</head>

<body>
<h2>{{$configuracion['razon_social']}}</h2>
<p> Estimado Administrador de Vehículos, a continuación el reporte de las bitácoras realizadas y no realizadas para
    el {{$fecha}}. </p>
<br>
<br>
<h3>Vehículos que si han registrado bitácoras</h3>
<table>
    <tr>
        <th>Vehículo</th>
        <th>Custodio</th>
        <th>Bitácora</th>
    </tr>
    @foreach($registros_realizados as $index=> $vehiculo)
        <tr>
            <td>{{$vehiculo['vehiculo']->placa}}</td>
            <td>{{$vehiculo['vehiculo']->custodio->nombres}} {{$vehiculo['vehiculo']->custodio->apellidos}}</td>
            <td>{{$vehiculo['bitacora']->id}}</td>
        </tr>
    @endforeach
</table>
<br>
<br>
<h3>Lista de vehículos que no han registrado bitácoras</h3>
<table>
    <tr>
        <th>Vehículo</th>
        <th>Custodio</th>
    </tr>
    @foreach($vehiculos_sin_bitacora as $index =>$vehiculo)
        <tr>
            <td>{{$vehiculo->placa}}</td>
            <td>{{$vehiculo->custodio->nombres}} {{$vehiculo->custodio->apellidos}}</td>
        </tr>
    @endforeach
</table>
<br>
<br>
<p><img src="{{ Utils::urlToBase64(url($configuracion->logo_claro)) }}" alt="logo" width="100" height="100"/></p> <br> <br>
<small>Este mensaje de correo electrónico es generado automáticamente. Por favor, no lo responda.</small>
</body>

</html>
