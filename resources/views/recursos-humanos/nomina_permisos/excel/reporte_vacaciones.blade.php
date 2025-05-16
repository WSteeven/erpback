@php
    use App\Models\Empleado;
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de Vacaciones</title>

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
<table style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px;margin-top: 20px;">
    <tr>
        <div class="header">
            <table style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px;page-break-inside: avoid;">
                <tr>
                    <td style="width: 17%">
                        <img src="{{ public_path($configuracion['logo_claro']) }}" width="90" alt="logo empresa">
                    </td>
                    <td style="width: 83%; font-size:16px; font-weight:bold">
                        <div style="text-align: center">JPCONSTRUCRED C.LTDA</div>
                    </td>
                </tr>
                <tr>
                    <td style="width: 17%">
                        <div style="text-align: center"></div>
                    </td>
                    <td style="width:83%; font-size:12px">
                        <div style="text-align: center"><strong>REPORTE DE VACACIONES
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
{{--                                <tr>--}}
{{--                                    <td colspan="5" style="background-color: #DBDBDB; font-weight: bold; text-align: center">PERIODO {{ $reporte[0]->periodo->nombre}} </td>--}}
{{--                                    <td colspan="3" style="background-color: #eebd27; font-weight: bold; text-align: center">RANGO 1</td>--}}
{{--                                    <td colspan="3" style="background-color: #FFE699; font-weight: bold; text-align: center">RANGO 2</td>--}}
{{--                                </tr>--}}
                                <tr>
                                    <td style="background-color:#DBDBDB">EMPLEADO</td>
                                    <td style="background-color:#DBDBDB">IDENTIFICACION</td>
                                    <td style="background-color:#DBDBDB">CARGO</td>
                                    <td style="background-color:#DBDBDB">JEFE INMEDIATO</td>
                                    <td style="background-color:#DBDBDB">FECHA INGRESO</td>
                                    <td style="background-color:#DBDBDB">DIAS</td>
{{--                                    <td style="background-color:#DBDBDB">DIAS UTILIZADOS</td>--}}
                                    <td style="background-color:#DBDBDB">COMPLETADAS</td>
                                    <td style="background-color:#DBDBDB">OBSERVACION</td>
                                    <td style="background-color:#DBDBDB">PAGADAS</td>
                                    <td style="background-color:#DBDBDB">MES PAGO</td>
                                    <td style="background-color:#DBDBDB">FECHAS TOMADAS</td>
                                    <td style="background-color:#DBDBDB">DESGLOCE DIAS</td>
                                </tr>

                                @foreach ($reporte as $rpt)
                                    <tr>
                                        <td>{{Empleado::extraerNombresApellidos($rpt->empleado) }}</td>
                                        <td>{{$rpt->empleado->identificacion }}</td>
                                        <td>{{$rpt->empleado->cargo->nombre }}</td>
                                        <td>{{Empleado::extraerNombresApellidos(Empleado::find($rpt->empleado->jefe_id)) }}</td>
                                        <td>{{ $rpt->empleado->fecha_ingreso }}</td>
                                        <td>{{$rpt->dias}}</td>
{{--                                        <td>{{$rpt->detalles()->sum('dias_utilizados')}}</td>--}}
                                        <td style="text-align: center">{{$rpt->completadas?'SI':'NO'}}</td>
                                        <td>{{$rpt->observacion}}</td>
                                        <td style="text-align: center">{{$rpt->opto_pago?'SI':'NO'}}</td>
                                        <td>{{$rpt->mes_pago?: 'Vacaciones fueron tomadas'}}</td>
                                        <td>
                                            @foreach($rpt->detalles()->get() as $detalle)
                                                {{ $detalle->fecha_inicio }} al {{ $detalle->fecha_fin }}
                                                {!! $loop->last ? '' : '<br>' !!}
                                            @endforeach
                                        </td>
                                        <td style="text-align: left">
                                            @foreach($rpt->detalles()->get() as $detalle)
                                                {{ $detalle->dias_utilizados }}
                                                {!! $loop->last ? '' : '<br>' !!}
                                            @endforeach
                                        </td>

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
