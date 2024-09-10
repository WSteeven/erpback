<!DOCTYPE html>
<html lang="en">
@php
    $logo_principal = 'data:image/png;base64,' . base64_encode(file_get_contents(public_path() . $configuracion['logo_claro']));
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte diario de bitácoras de vehículos</title>
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
        <td>Vehículo</td>
        <td>Custodio</td>
        <td>Bitácora</td>
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
        <td>Vehículo</td>
        <td>Custodio</td>
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
<img src="{{ $logo_principal }}" alt="logo" width="100" height="100" /> <br> <br>
<small>Este mensaje de correo electrónico es generado automáticamente. Por favor, no lo responda.</small>
</body>

</html>
