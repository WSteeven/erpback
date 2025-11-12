@php
    use App\Http\Resources\RecursosHumanos\NominaPrestamos\PlanVacacionResource;
    use App\Models\Empleado;
    use Src\Shared\Utils;
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de Planes de Vacaciones</title>

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
            margin-top: 60px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 6px 8px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .section-title {
            background-color: #f9f9f9;
            font-weight: bold;
            text-align: center;
        }

        .bg-yellow {
            background-color: #fff3cd;
        }

        .bg-lightyellow {
            background-color: #fff8e1;
        }

        .text-center { text-align: center; }
    </style>
</head>
<body>

<div class="header">
    <img src="{{ Utils::urlToBase64(url($configuracion['logo_claro'])) }}" alt="logo">
    <div class="title">{{ $configuracion['razon_social'] }}</div>
    <div style="font-size: 12px; margin-top: 4px;">REPORTE DE PLANES DE VACACIONES</div>
</div>

<table>
    <thead>
        <tr>
            <th colspan="5"></th>
            <th colspan="3" class="section-title bg-yellow">RANGO 1 (PLANIFICADAS)</th>
            <th colspan="3" class="section-title bg-lightyellow">RANGO 2 (GOZADAS)</th>
        </tr>
        <tr>
            <th>Empleado</th>
            <th>Identificación</th>
            <th>Cargo</th>
            <th>Jefe inmediato</th>
            <th>Fecha ingreso</th>
            <th class="bg-yellow">F. Inicio 1</th>
            <th class="bg-yellow">F. Fin 1</th>
            <th class="bg-yellow">Días</th>
            <th class="bg-lightyellow">F. Inicio 2</th>
            <th class="bg-lightyellow">F. Fin 2</th>
            <th class="bg-lightyellow">Días</th>
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

                @if ($rpt->rangos == 2)
                    <td>{{ $rpt->fecha_inicio_primer_rango }}</td>
                    <td>{{ $rpt->fecha_fin_primer_rango }}</td>
                    <td class="text-center">
                        {{ PlanVacacionResource::obtenerCantidadDias($rpt->fecha_inicio_primer_rango, $rpt->fecha_fin_primer_rango) }}
                    </td>
                    <td>{{ $rpt->fecha_inicio_segundo_rango }}</td>
                    <td>{{ $rpt->fecha_fin_segundo_rango }}</td>
                    <td class="text-center">
                        {{ PlanVacacionResource::obtenerCantidadDias($rpt->fecha_inicio_segundo_rango, $rpt->fecha_fin_segundo_rango) }}
                    </td>
                @else
                    <td>{{ $rpt->fecha_inicio }}</td>
                    <td>{{ $rpt->fecha_fin }}</td>
                    <td class="text-center">
                        {{ PlanVacacionResource::obtenerCantidadDias($rpt->fecha_inicio, $rpt->fecha_fin) }}
                    </td>
                    <td colspan="3" class="text-center">No aplica</td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
