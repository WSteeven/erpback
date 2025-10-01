<!DOCTYPE html>
<html lang="en">

@php
    use App\Models\Empleado;
    use App\Models\Tarea;
    use Src\Shared\Utils;

    $kmts_totales = 0;
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte Bitácoras Vehículos</title>

</head>

<body>
    <table
        style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px;margin-top: 20px;">
        <tr>
            <td colspan="2">
                <img src="{{ public_path($configuracion->logo_claro) }}" width="90" alt="Logo empresa">
            </td>
            <td colspan="6" style="font-size:16px; font-weight:bold">
                <div style="text-align: center">{{ $configuracion->razon_social }}</div>
            </td>
        </tr>
        <tr>
            <td colspan="8" style="font-size:12px; text-align: center">
                <strong>Reporte de Bitácoras de Vehículos</strong>
            </td>
        </tr>
        <tr>
            <td colspan="8" style="text-align: center">Desde {{ $fecha_inicio }} hasta {{$fecha_fin}}
            </td>
        </tr>

        <tr>
            <td></td>
        </tr>
        <tr>
            <td></td>
        </tr>
        {{-- INFORMACION DEL VEHICULO --}}
        <tr>
            <td style="background-color: #DBDBDB"><strong>N° BITACORA</strong></td>
            <td style="background-color: #DBDBDB"><strong>VEHICULO</strong></td>
            <td style="background-color: #DBDBDB"><strong>CONDUCTOR</strong></td>
            <td style="background-color: #DBDBDB"><strong>FECHA </strong></td>
            <td style="background-color: #DBDBDB"><strong>TAREAS</strong></td>
            <td style="background-color: #DBDBDB"><strong>% TANQUE INICIAL</strong></td>
            <td style="background-color: #DBDBDB"><strong>% TANQUE FINAL</strong></td>
            <td style="background-color: #DBDBDB"><strong>KM INICIAL</strong></td>
            <td style="background-color: #DBDBDB"><strong>KM FINAL</strong></td>
            <td style="background-color: #DBDBDB"><strong>KM RECORRIDOS</strong></td>
        </tr>
        @foreach($reporte as $bitacora)
            <tr>
                <td>{{ $bitacora->id }}</td>
                <td>{{ $bitacora->vehiculo->placa }}</td>
                <td>{{ Empleado::extraerNombresApellidos($bitacora->chofer) }}</td>
                <td>{{ $bitacora->fecha }}</td>
                <td>{{ $bitacora->tareas ? Tarea::whereIn('id', array_map('intval', Utils::convertirStringComasArray($bitacora->tareas)))->pluck('codigo_tarea') : null }}
                </td>
                <td>{{ $bitacora->tanque_inicio }}</td>
                <td>{{ $bitacora->tanque_final }}</td>
                <td>{{ $bitacora->km_inicial }}</td>
                <td>{{ $bitacora->km_final }}</td>
                <td>{{ $bitacora->km_final ? $bitacora->km_final - $bitacora->km_inicial : 0 }}</td>
            </tr>
            @php $kmts_totales += $bitacora->km_final ? $bitacora->km_final - $bitacora->km_inicial : 0 @endphp
        @endforeach
        <tr>
            <td colspan="7" style="text-align: right"><strong>TOTAL KILOMETROS RECORRIDOS</strong></td>
            <td>{{$kmts_totales}}</td>
        </tr>
        @if($kmts_totales > $umbral)
            <tr>
                <td colspan="7" style="text-align: right"><strong>KILOMETROS EXCEDIDOS EN BASE AL UMBRAL
                        ({{$umbral}}):</strong></td>
                <td style="background-color: #FFE699"><strong>{{$kmts_totales - $umbral}}</strong></td>
            </tr>
        @endif

    </table>


</body>

</html>
