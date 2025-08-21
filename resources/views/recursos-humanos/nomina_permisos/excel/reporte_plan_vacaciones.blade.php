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
        @page {
            margin: 100px 25px;
        }

        .header {
            position: fixed;
            top: -55px;
            left: 0;
            right: 0;
            height: 80px;
            text-align: center;
            line-height: 35px;
        }
    </style>
</head>
<body>
<table
    style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px;margin-top: 20px;">
    <tr>
        <div class="header">
            <table
                style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px;page-break-inside: avoid;">
                <tr>
                    <td style="width: 17%">
                        <img src="{{ Utils::getImagePath($configuracion['logo_claro']) }}" width="90" alt="logo empresa">
                    </td>
                    <td style="width: 83%; font-size:16px; font-weight:bold">
                        <div style="text-align: center">{{$configuracion['razon_social']}}</div>
                    </td>
                </tr>
                <tr>
                    <td style="width: 17%">
                        <div style="text-align: center"></div>
                    </td>
                    <td style="width:83%; font-size:12px">
                        <div style="text-align: center"><strong>REPORTE DE PLANES DE VACACIONES
                            </strong>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </tr>
    <tr>
        <td>
            <div style="text-align: center">
                <table style="width: 100%">
                    <tr>
                        <td></td>
                    </tr>
                    <tr>
                        <td>
                            <table style="width: 100%; border: 3px solid #000000;">
                                <tr>
                                    <td colspan="5"></td>
                                    <td colspan="6" style="background-color: #DBDBDB; text-align: center">PLANIFICADAS</td>
                                    <td colspan="6" style="background-color: #e5e4e4; text-align: center">GOZADAS</td>
                                </tr>
                                <tr>
                                    <td colspan="5" style="background-color: #DBDBDB; font-weight: bold; text-align: center">PERIODO {{ $reporte[0]->periodo->nombre}} </td>
                                    <td colspan="3" style="background-color: #eebd27; font-weight: bold; text-align: center">RANGO 1</td>
                                    <td colspan="3" style="background-color: #FFE699; font-weight: bold; text-align: center">RANGO 2</td>
                                </tr>
                                <tr>
                                    <td style="background-color:#DBDBDB">EMPLEADO</td>
                                    <td style="background-color:#DBDBDB">IDENTIFICACION</td>
                                    <td style="background-color:#DBDBDB">CARGO</td>
                                    <td style="background-color:#DBDBDB">JEFE INMEDIATO</td>
                                    <td style="background-color:#DBDBDB">FECHA INGRESO</td>
{{--                                    <td style="background-color:#DBDBDB">PERIODO</td>--}}
                                    <td style="background-color:#eebd27">F. INICIO 1</td>
                                    <td style="background-color:#eebd27">F. FIN 1</td>
                                    <td style="background-color:#eebd27">DIAS</td>
                                    <td style="background-color:#FFE699">F. INICIO 2</td>
                                    <td style="background-color:#FFE699">F. FIN 2</td>
                                    <td style="background-color:#FFE699">DIAS</td>
                                </tr>

                                @foreach ($reporte as $rpt)
                                    <tr>
                                        <td>{{Empleado::extraerNombresApellidos($rpt->empleado) }}</td>
                                        <td>{{$rpt->empleado->identificacion }}</td>
                                        <td>{{$rpt->empleado->cargo->nombre }}</td>
                                        <td>{{Empleado::extraerNombresApellidos(Empleado::find($rpt->empleado->jefe_id)) }}</td>
                                        <td>{{ $rpt->empleado->fecha_ingreso }}</td>
{{--                                        <td>{{ $rpt->periodo->nombre }}</td>--}}
                                        @if($rpt->rangos==2)
                                            <td>{{ $rpt->fecha_inicio_primer_rango }}</td>
                                            <td>{{ $rpt->fecha_fin_primer_rango }}</td>
                                            <td>{{ PlanVacacionResource::obtenerCantidadDias($rpt->fecha_inicio_primer_rango, $rpt->fecha_fin_primer_rango) }}</td>
                                            <td>{{ $rpt->fecha_inicio_segundo_rango }}</td>
                                            <td>{{ $rpt->fecha_fin_segundo_rango }}</td>
                                            <td>{{ PlanVacacionResource::obtenerCantidadDias($rpt->fecha_inicio_segundo_rango , $rpt->fecha_fin_segundo_rango ) }}</td>

                                        @else
                                            <td>{{ $rpt->fecha_inicio }}</td>
                                            <td>{{ $rpt->fecha_fin }}</td>
                                            <td>{{ PlanVacacionResource::obtenerCantidadDias($rpt->fecha_inicio, $rpt->fecha_fin)  }}</td>
                                            <td colspan="3" style="text-align: center">NO APLICA</td>
                                        @endif
                                    </tr>
                                @endforeach
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>
</table>
</body>

</html>
