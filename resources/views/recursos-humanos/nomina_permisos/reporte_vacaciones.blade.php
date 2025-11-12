@php
    use App\Models\Empleado;
    use Src\Shared\Utils;
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de Vacaciones</title>

    <style>
        @page { margin: 90px 40px; }

        body {
            font-family: DejaVu Sans, Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #222;
        }

        .header {
            position: fixed;
            top: -70px;
            left: 0;
            right: 0;
            text-align: center;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
        }

        .header img {
            float: left;
            width: 70px;
        }

        .header .title {
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            margin-top: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 40px;
        }

        th, td {
            padding: 6px 8px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .text-center { text-align: center; }
    </style>
</head>
<body>

<div class="header">
    <img src="{{ Utils::urlToBase64(url($configuracion['logo_claro'])) }}" alt="logo">
    <div class="title">{{ $configuracion['razon_social'] }}</div>
    <div style="font-size: 12px; margin-top: 4px;">REPORTE DE VACACIONES</div>
</div>

<table>
    <thead>
        <tr>
            <th>Empleado</th>
            <th>Identificación</th>
            <th>Cargo</th>
            <th>Jefe inmediato</th>
            <th>Fecha ingreso</th>
            <th>Días</th>
            <th>Completadas</th>
            <th>Observación</th>
            <th>Pagadas</th>
            <th>Mes pago</th>
            <th>Fechas tomadas</th>
            <th>Desgloce días</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($reporte as $rpt)
            <tr>
                <td>{{ Empleado::extraerNombresApellidos($rpt->empleado) }}</td>
                <td>{{ $rpt->empleado->identificacion }}</td>
                <td>{{ $rpt->empleado->cargo->nombre }}</td>
                <td>{{ Empleado::extraerNombresApellidos(Empleado::find($rpt->empleado->jefe_id)) }}</td>
                <td>{{ $rpt->empleado->fecha_ingreso }}</td>
                <td class="text-center">{{ $rpt->dias }}</td>
                <td class="text-center">{{ $rpt->completadas ? 'SI' : 'NO' }}</td>
                <td>{{ $rpt->observacion }}</td>
                <td class="text-center">{{ $rpt->opto_pago ? 'SI' : 'NO' }}</td>
                <td>{{ $rpt->mes_pago ?: 'Vacaciones tomadas' }}</td>
                <td>
                    @foreach ($rpt->detalles()->get() as $detalle)
                        {{ $detalle->fecha_inicio }} al {{ $detalle->fecha_fin }}{!! !$loop->last ? '<br>' : '' !!}
                    @endforeach
                </td>
                <td class="text-center">
                    @foreach ($rpt->detalles()->get() as $detalle)
                        {{ $detalle->dias_utilizados }}{!! !$loop->last ? '<br>' : '' !!}
                    @endforeach
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
