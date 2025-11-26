<html>
{{-- Este es el reporte de saldo consolidado para un empleado --}}
@php
    use Src\Shared\Utils;
    $fecha = new Datetime();
    $num_registro = 1;
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>REPORTE CONSOLIDADO</title>
    <style>
        @page {
            margin: 2px 15px 5px 15px;
        }

        body {
            background-image: url({{ Utils::urlToBase64(url($configuracion->logo_marca_agua)) }});
            background-size: 50% auto;
            background-repeat: no-repeat;
            background-position: center;
        }

        /** Definir las reglas del encabezado **/
        header {
            position: fixed;
            top: 0cm;
            left: 0cm;
            right: 0cm;
            height: 2cm;

            /** Estilos extra personales **/
            text-align: center;
            line-height: 1.5cm;
        }

        /** Definir las reglas del pie de página **/
        footer {
            position: fixed;
            bottom: 10px;
            left: 0cm;
            right: 0cm;
            height: 2cm;

            /** Estilos extra personales **/
            text-align: center;
            color: #000000;
            line-height: 1.5cm;
        }

        footer .page:after {
            content: counter(page);
        }

        main {
            position: relative;
            top: 80px;
            left: 0cm;
            right: 0cm;
            margin-bottom: 7cm;
            font-size: 12px;
        }

        div {
            color: #000000 !important;
        }

        h1 {
            text-align: center;
            text-transform: uppercase;
        }

        .firma {
            table-layout: fixed;
            width: 100%;
            line-height: normal;
            font-size: 12px;
            /* position: inherit; */
            /* top: 140px; */
        }


        .row {
            width: 100%;
        }
    </style>
</head>


<body>
<header>
    <table
        style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:18px;">
        <tr class="row" style="width:auto">
            <td style="width: 10%;">
                <div class="col-md-3"><img src="{{ Utils::urlToBase64(url($configuracion->logo_claro)) }}" width="90" alt="logo"></div>
            </td>
            <td style="width: 100%">
                <div class="col-md-7" align="center"><b style="font-size: 75%">REPORTE CONSOLIDADO
                        {{ ' DEL ' . $fecha_inicio . ' AL ' . $fecha_fin }}</b>
                </div>
            </td>
        </tr>
    </table>
    <hr>
</header>
<footer>
    <table style="width: 100%;">
        <tr>
            <td style="line-height: normal;">
                <div style="margin: 0%; margin-bottom: 0px; margin-top: 0px;" align="center">{{ $copyright }}
                </div>
                <div style="margin: 0%; margin-bottom: 0px; margin-top: 0px;" align="center">Generado por el
                    Usuario:
                    {{ auth('sanctum')->user()->empleado->nombres }}
                    {{ auth('sanctum')->user()->empleado->apellidos }} el
                    {{ $fecha->format('d-m-Y H:i') }}
                </div>
            </td>
        </tr>
    </table>
</footer>
<main>
    <table
        style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px;margin-top: 20px;">
        <tr height="29">
            <td height="15">
                <div align="center">
                    <table width="100%">
                        <tr>
                            <td height="55px;">
                                <table width="100%" border="1" align="left" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td bgcolor="#a9d08e" style="font-size:10px" width="29%">
                                            <div align="center"><strong>NOMBRES Y APELLIDOS</strong></div>
                                        </td>
                                        <td bgcolor="#a9d08e" style="font-size:10px" width="15%">
                                            <div align="center"><strong>LUGAR</strong></div>
                                        </td>
                                        <td bgcolor="#a9d08e" style="font-size:10px" width="17%">
                                            <div align="center"><strong>FECHA CONSOLIDADO</strong></div>
                                        </td>
                                        <td bgcolor="#a9d08e" style="font-size:10px" width="29%">
                                            <div align="center"><strong>DESCRIPCI&Oacute;N</strong></div>
                                        </td>
                                        <td bgcolor="#a9d08e" style="font-size:10px" width="10%">
                                            <div align="center"><strong>MONTO</strong></div>
                                        </td>
                                    </tr>
                                    <!--Saldo Inicial-->
                                    <tr>
                                        <td style="font-size:10px" width="29%">
                                            <div align="left">
                                                {{ $empleado->nombres . ' ' . $empleado->apellidos }}
                                            </div>
                                        </td>
                                        <td style="font-size:10px" width="15%">
                                            <div align="left">{{ $empleado->canton->canton }}
                                            </div>
                                        </td>
                                        <td style="font-size:10px" width="17%">
                                            <div align="center">{{ date('d-m-Y', strtotime($fecha_anterior)) }}
                                            </div>
                                        </td>
                                        <td style="font-size:10px" width="29%">
                                            <div align="left">Saldo Inicial (+)</div>
                                        </td>
                                        <td style="font-size:10px" width="10%">
                                            <div align="right">
                                                {{ number_format($saldo_anterior, 2, ',', '.') }}</div>
                                        </td>
                                    </tr>
                                    <!--Fin Saldo Inicial-->
                                    <!--Acreditaciones-->
                                    <tr>
                                        <td style="font-size:10px" width="29%">
                                            <div align="left">
                                                {{ $empleado->nombres . ' ' . $empleado->apellidos }}
                                            </div>
                                        </td>
                                        <td style="font-size:10px" width="15%">
                                            <div align="left">{{ $empleado->canton->canton }}
                                            </div>
                                        </td>
                                        <td style="font-size:10px" width="17%">
                                            <div align="center">
                                                {{ date('d-m-Y', strtotime($fecha_inicio)) . ' ' . date('d-m-Y', strtotime($fecha_fin)) }}
                                            </div>
                                        </td>
                                        <td style="font-size:10px" width="29%">
                                            <div align="left">Acreditaciones (+)</div>
                                        </td>
                                        <td style="font-size:10px" width="10%">
                                            <div align="right">
                                                {{ number_format($acreditaciones, 2, ',', '.') }}</div>
                                        </td>
                                    </tr>

                                    <!--Fin Acreditaciones-->
                                    <!--Transferencias-->
                                    <tr>
                                        <td style="font-size:10px" width="29%">
                                            <div align="left">
                                                {{ $empleado->nombres . ' ' . $empleado->apellidos }}
                                            </div>
                                        </td>
                                        <td style="font-size:10px" width="15%">
                                            <div align="left">{{ $empleado->canton->canton }}
                                            </div>
                                        </td>
                                        <td style="font-size:10px" width="17%">
                                            <div align="center">
                                                {{ date('d-m-Y', strtotime($fecha_inicio)) . ' ' . date('d-m-Y', strtotime($fecha_fin)) }}
                                            </div>
                                        </td>
                                        <td style="font-size:10px" width="29%">
                                            <div align="left">Transferencias Enviadas (-)</div>
                                        </td>
                                        <td style="font-size:10px" width="10%">
                                            <div align="right">
                                                {{ number_format($transferencia, 2, ',', '.') }}</div>
                                        </td>
                                    </tr>
                                    <!--Fin Transferencias-->
                                    <!--transferencias recibidas-->
                                    <tr>
                                        <td style="font-size:10px" width="29%">
                                            <div align="left">
                                                {{ $empleado->nombres . ' ' . $empleado->apellidos }}
                                            </div>
                                        </td>
                                        <td style="font-size:10px" width="15%">
                                            <div align="left">{{ $empleado->canton->canton }}
                                            </div>
                                        </td>
                                        <td style="font-size:10px" width="17%">
                                            <div align="center">
                                                {{ date('d-m-Y', strtotime($fecha_inicio)) . ' ' . date('d-m-Y', strtotime($fecha_fin)) }}
                                            </div>
                                        </td>
                                        <td style="font-size:10px" width="29%">
                                            <div align="left">Transferencias Recibidas (+)</div>
                                        </td>
                                        <td style="font-size:10px" width="10%">
                                            <div align="right">
                                                {{ number_format($transferencia_recibida, 2, ',', '.') }}</div>
                                        </td>
                                    </tr>
                                    <!--Gastos-->
                                    <tr>
                                        <td style="font-size:10px" width="29%">
                                            <div align="left">
                                                {{ $empleado->nombres . ' ' . $empleado->apellidos }}
                                            </div>
                                        </td>
                                        <td style="font-size:10px" width="15%">
                                            <div align="left">{{ $empleado->canton->canton }}
                                            </div>
                                        </td>
                                        <td style="font-size:10px" width="17%">
                                            <div align="center">
                                                {{ date('d-m-Y', strtotime($fecha_inicio)) . ' ' . date('d-m-Y', strtotime($fecha_fin)) }}
                                            </div>
                                        </td>
                                        <td style="font-size:10px" width="29%">
                                            <div align="left">Gastos (-)</div>
                                        </td>
                                        <td style="font-size:10px" width="10%">
                                            <div align="right">
                                                {{ number_format($gastos, 2, ',', '.') }}</div>
                                        </td>
                                    </tr>
                                    <!--Fin Gastos-->
                                    <!--Gastos aprobados fuera del mes-->
                                    {{--                                    <tr>--}}
                                    {{--                                        <td style="font-size:10px" width="29%">--}}
                                    {{--                                            <div align="left">--}}
                                    {{--                                                {{ $empleado->nombres . ' ' . $empleado->apellidos }}--}}
                                    {{--                                            </div>--}}
                                    {{--                                        </td>--}}
                                    {{--                                        <td style="font-size:10px" width="15%">--}}
                                    {{--                                            <div align="left">{{ $empleado->canton->canton }}--}}
                                    {{--                                            </div>--}}
                                    {{--                                        </td>--}}
                                    {{--                                        <td style="font-size:10px" width="17%">--}}
                                    {{--                                            <div align="center">--}}
                                    {{--                                                {{ date('d-m-Y', strtotime($fecha_inicio)) . ' ' . date('d-m-Y', strtotime($fecha_fin)) }}--}}
                                    {{--                                            </div>--}}
                                    {{--                                        </td>--}}
                                    {{--                                        <td style="font-size:10px" width="29%">--}}
                                    {{--                                            <div align="left">Gastos aprobados posteriormente(-)</div>--}}
                                    {{--                                        </td>--}}
                                    {{--                                        <td style="font-size:10px" width="10%">--}}
                                    {{--                                            <div align="right">--}}
                                    {{--                                                {{ number_format($gastos_aprobados_fuera_mes, 2, ',', '.') }}</div>--}}
                                    {{--                                        </td>--}}
                                    {{--                                    </tr>--}}
                                    <!--Fin Gastos Aprobados fuera del mes-->
                                    <!-- Ajuste de saldos Ingreso -->
                                    <tr>
                                        <td style="font-size:10px" width="29%">
                                            <div align="left">
                                                {{ $empleado->nombres . ' ' . $empleado->apellidos }}
                                            </div>
                                        </td>
                                        <td style="font-size:10px" width="15%">
                                            <div align="left">{{ $empleado->canton->canton }}
                                            </div>
                                        </td>
                                        <td style="font-size:10px" width="17%">
                                            <div align="center">
                                                {{ date('d-m-Y', strtotime($fecha_inicio)) . ' ' . date('d-m-Y', strtotime($fecha_fin)) }}
                                            </div>
                                        </td>
                                        <td style="font-size:10px" width="29%">
                                            <div align="left">Ajuste de Saldo Ingreso (+)</div>
                                        </td>
                                        <td style="font-size:10px" width="10%">
                                            <div align="right">
                                                {{ number_format($ajuste_saldo_ingreso, 2, ',', '.') }}</div>
                                        </td>
                                    </tr>
                                    <!-- Ajuste de saldos Egreso -->
                                    <tr>
                                        <td style="font-size:10px" width="29%">
                                            <div align="left">
                                                {{ $empleado->nombres . ' ' . $empleado->apellidos }}
                                            </div>
                                        </td>
                                        <td style="font-size:10px" width="15%">
                                            <div align="left">{{ $empleado->canton->canton }}
                                            </div>
                                        </td>
                                        <td style="font-size:10px" width="17%">
                                            <div align="center">
                                                {{ date('d-m-Y', strtotime($fecha_inicio)) . ' ' . date('d-m-Y', strtotime($fecha_fin)) }}
                                            </div>
                                        </td>
                                        <td style="font-size:10px" width="29%">
                                            <div align="left">Ajuste de Saldo Egreso (-)</div>
                                        </td>
                                        <td style="font-size:10px" width="10%">
                                            <div align="right">
                                                {{ number_format($ajuste_saldo_egreso, 2, ',', '.') }}</div>
                                        </td>
                                    </tr>
                                    <!--Saldo Final-->
                                    <tr>
                                        <td colspan="4" style="font-size:10px">
                                            <div align="right"><strong>TOTAL:</strong></div>
                                        </td>
                                        <td style="font-size:10px" align="center">
                                            <div align="right" style="margin-right:20px;">
                                                {{ number_format($total_suma, 2, ',', ' ') }}</div>
                                        </td>
                                    </tr>
                                    <!--Fin Saldo Final-->
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>
    <p
        style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:75%; font-weight:bold; margin-top: -6px;">
    <div class="col-md-7" align="center"><b>Detalle de Gastos</b></div>
    </p>
    <table width="100%" border="1" cellspacing="0" bordercolor="#666666" class="gastos">
        <tr>
            <td width="5%" bgcolor="#a9d08e">
                <div align="center"><strong>N&deg;</strong></div>
            </td>
            <td width="15%" bgcolor="#a9d08e">
                <div align="center"><strong>FECHA</strong></div>
            </td>
            <td width="17%" bgcolor="#a9d08e">
                <div align="center"><strong>PROYECTO</strong></div>
            </td>
            <td width="17%" bgcolor="#a9d08e">
                <div align="center"><strong>TAREA</strong></div>
            </td>
            <td width="20%" bgcolor="#a9d08e">
                <div align="center"><strong># FACTURA</strong></div>
            </td>
            <td width="20%" bgcolor="#a9d08e">
                <div align="center"><strong>RUC</strong></div>
            </td>
            <td width="35%" bgcolor="#a9d08e">
                <div align="center"><strong>AUTORIZACION ESPECIAL</strong></div>
            </td>
            <td width="25%" bgcolor="#a9d08e">
                <div align="center"><strong>DETALLE</strong></div>
            </td>
            <td width="25%" bgcolor="#a9d08e">
                <div align="center"><strong>SUB DETALLE</strong></div>
            </td>
            <td width="24%" bgcolor="#a9d08e">
                <div align="center"><strong>OBSERVACI&Oacute;N</strong></div>
            </td>
            <td bgcolor="#a9d08e" style="font-size:10px">
                <div align="center"><strong>CENTRO DE COSTO</strong></div>
            </td>
            <td bgcolor="#a9d08e" style="font-size:10px">
                <div align="center"><strong>SUBCENTRO DE COSTO</strong></div>
            </td>
            <td width="10%" bgcolor="#a9d08e">
                <div align="center"><strong>CANT.</strong></div>
            </td>
            <td width="10%" bgcolor="#a9d08e">
                <div align="center"><strong>V. UNI.</strong></div>
            </td>
            <td width="10%" bgcolor="#a9d08e">
                <div align="center"><strong>TOTAL</strong></div>
            </td>
        </tr>
        @if (sizeof($gastos_reporte) == 0)
            <tr>
                <td colspan="13">
                    <div align="center">NO HAY FONDOS ROTATIVOS APROBADOS</div>
                </td>
            </tr>
        @else
            @foreach ($gastos_reporte as $dato)
                @php
                    $sub_total = $sub_total + (float) $dato['total'];
                @endphp
                <tr>
                    <td style="font-size:10px">
                        <div align="center">{{ $dato['num_registro'] }}</div>
                    </td>
                    <td style="font-size:10px">
                        <div align="center">{{ date('d-m-Y', strtotime($dato['fecha'])) }}</div>
                    </td>
                    <td style="font-size:10px">
                        <div align="center">
                            {{ $dato['proyecto'] != null ? $dato['proyecto']['codigo_proyecto'] . ' - ' . $dato['proyecto']['nombre'] : 'Sin Proyecto' }}
                        </div>
                    </td>
                    <td style="font-size:10px">
                        <div align="center">
                            {{ $dato['tarea'] != null ? $dato['tarea']['codigo_tarea'] : 'Sin Tarea' }}
                        </div>
                    </td>
                    <td style="font-size:10px">
                        <div align="center">{{ $dato['factura'] }}</div>
                    </td>
                    <td style="font-size:10px">
                        <div align="center">{{ $dato['ruc'] }}</div>
                    </td>
                    <td style="font-size:10px">
                        <div align="center">
                            {{ $dato['autorizador'] }}
                        </div>
                    </td>
                    <td style="font-size:10px">
                        <div align="center">{{ $dato['detalle'] }}</div>
                    </td>
                    <td style="font-size:10px">
                        <div align="center">
                            @foreach ($dato['sub_detalle'] as $sub_detalle)
                                {{ $sub_detalle->descripcion }}
                                @if (!$loop->last)
                                    ,
                                @endif
                            @endforeach
                        </div>
                    </td>
                    <td style="font-size:10px;word-wrap: break-word;">
                        <div align="center">{{ $dato['observacion'] }}</div>
                    </td>
                    <td style="font-size:10px">{{ $dato['centro_costo'] }}</td>
                    <td style="font-size:10px">{{ $dato['sub_centro_costo'] }}</td>
                    <td style="font-size:10px">
                        <div align="center">{{ $dato['cantidad'] }}</div>
                    </td>
                    <td style="font-size:10px">
                        <div align="center">
                            {{ number_format($dato['valor_u'], 2, ',', '.') }}</div>
                    </td>
                    <td style="font-size:10px">
                        <div align="center">{{ number_format($dato['total'], 2, ',', '.') }}
                        </div>
                    </td>
                </tr>
            @endforeach
        @endif
        <tr>
            <td>&nbsp;</td>
            <td colspan="13" style="font-size:10px">
                <div align="right"><strong>TOTAL DE GASTOS:&nbsp;</strong></div>
            </td>
            <td style="font-size:10px">
                <div align="center">{{ number_format($sub_total, 2, ',', ' ') }}</div>
            </td>
        </tr>
    </table>
    <br>
    {{-- inicio registros_fuera_mes --}}
    <p
        style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:75%; font-weight:bold; margin-top: -6px;">
    <div class="col-md-7" align="center"><b>Registros fuera de mes</b></div>
    </p>
    <table width="100%" border="1" cellspacing="0" bordercolor="#666666" class="gastos">
        <tr>
            <td width="15%" bgcolor="#a9d08e">
                <div align="center"><strong>FECHA REGISTRO</strong></div>
            </td>
            <td width="17%" bgcolor="#a9d08e">
                <div align="center"><strong>EMPLEADO</strong></div>
            </td>
            <td width="20%" bgcolor="#a9d08e">
                <div align="center"><strong>ID REGISTRO</strong></div>
            </td>
            <td width="20%" bgcolor="#a9d08e">
                <div align="center"><strong>TIPO TRANSACCION </strong></div>
            </td>
            <td width="20%" bgcolor="#a9d08e">
                <div align="center"><strong>TIPO</strong></div>
            </td>
            <td width="20%" bgcolor="#a9d08e">
                <div align="center"><strong>FECHA APROBACION</strong></div>
            </td>
            <td width="35%" bgcolor="#a9d08e">
                <div align="center"><strong>VALOR</strong></div>
            </td>
        </tr>
        @if (sizeof($registros_fuera_mes) == 0)
            <tr>
                <td colspan="7">
                    <div align="center">NO HAY REGISTROS FUERA DE MES</div>
                </td>
            </tr>
        @else
            @foreach ($registros_fuera_mes as $registro)
                <tr>
                    <td style="font-size:10px">
                        <div align="center">
                            {{ $registro->fecha }}
                        </div>
                    </td>
                    <td style="font-size:10px">
                        <div align="center">
                            {{ $registro->empleado->nombres . ' ' . $registro->empleado->apellidos }}
                        </div>
                    </td>
                    <td style="font-size:10px">
                        <div align="center">
                            {{ $registro->saldoable_id }}
                        </div>
                    </td>
                    <td style="font-size:10px">
                        <div align="center">
                            {{ class_basename($registro->saldoable_type) }}
                        </div>
                    </td>
                    <td style="font-size:10px">
                        <div align="center">
                            {{ $registro->tipo_saldo }}
                        </div>
                    </td>
                    <td style="font-size:10px">
                        <div align="center">
                            {{ $registro->created_at }}
                        </div>
                    </td>
                    <td style="font-size:10px">
                        <div align="center">
                            {{ $registro->saldo_depositado }}
                        </div>
                    </td>
                </tr>
            @endforeach
            <tr>
                <td>&nbsp;</td>
                <td colspan="5" style="font-size:10px">
                    <div align="right"><strong>TOTAL:&nbsp;</strong></div>
                </td>
                <td style="font-size:10px">
                    <div align="center">{{ number_format($gastos_aprobados_fuera_mes, 2, ',', ' ') }}</div>
                </td>
            </tr>
        @endif
    </table>
    <br>
    {{-- fin registros_fuera_mes --}}
    <p
        style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:75%; font-weight:bold; margin-top: -6px;">
    <div class="col-md-7" align="center"><b>Transferencias Enviadas</b></div>
    </p>
    <table width="100%" border="1" cellspacing="0" bordercolor="#666666" class="gastos">
        <tr>
            <td width="15%" bgcolor="#a9d08e">
                <div align="center"><strong>FECHA</strong></div>
            </td>
            <td width="17%" bgcolor="#a9d08e">
                <div align="center"><strong>REMITENTE</strong></div>
            </td>
            <td width="20%" bgcolor="#a9d08e">
                <div align="center"><strong>DESTINATARIO</strong></div>
            </td>
            <td width="20%" bgcolor="#a9d08e">
                <div align="center"><strong>MONTO</strong></div>
            </td>
            <td width="20%" bgcolor="#a9d08e">
                <div align="center"><strong># Cuenta</strong></div>
            </td>
            <td width="35%" bgcolor="#a9d08e">
                <div align="center"><strong>MOTIVO</strong></div>
            </td>
            <td width="24%" bgcolor="#a9d08e">
                <div align="center"><strong>OBSERVACI&Oacute;N</strong></div>
            </td>
        </tr>
        @if (sizeof($transferencias_enviadas) == 0)
            <tr>
                <td colspan="7">
                    <div align="center">NO HAY TRANSFERENCIAS ENVIADAS</div>
                </td>
            </tr>
        @else
            @foreach ($transferencias_enviadas as $transferencia_enviada)
                <tr>
                    <td style="font-size:10px">
                        <div align="center">
                            {{ $transferencia_enviada->fecha }}
                        </div>
                    </td>
                    <td style="font-size:10px">
                        <div align="center">
                            {{ $transferencia_enviada->empleadoEnvia->nombres . ' ' . $transferencia_enviada->empleadoEnvia->apellidos }}
                        </div>
                    </td>
                    <td style="font-size:10px">
                        <div align="center">
                            {{ $transferencia_enviada->empleadoRecibe->nombres . ' ' . $transferencia_enviada->empleadoRecibe->apellidos }}
                        </div>
                    </td>
                    <td style="font-size:10px">
                        <div align="center">
                            {{ $transferencia_enviada->monto }}
                        </div>
                    </td>
                    <td style="font-size:10px">
                        <div align="center">
                            {{ $transferencia_enviada->cuenta }}
                        </div>
                    </td>
                    <td style="font-size:10px">
                        <div align="center">
                            {{ $transferencia_enviada->motivo }}
                        </div>
                    </td>
                    <td style="font-size:10px">
                        <div align="center">
                            {{ $transferencia_enviada->observacion }}
                        </div>
                    </td>
                </tr>
            @endforeach
            <tr>
                <td>&nbsp;</td>
                <td colspan="5" style="font-size:10px">
                    <div align="right"><strong>TOTAL DE TRANSFERENCIAS ENVIADAS:&nbsp;</strong></div>
                </td>
                <td style="font-size:10px">
                    <div align="center">{{ number_format($transferencia, 2, ',', ' ') }}</div>
                </td>
            </tr>
        @endif
    </table>
    <br>
    <p
        style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:75%; font-weight:bold; margin-top: -6px;">
    <div class="col-md-7" align="center"><b>Ajuste de Ingreso</b></div>
    </p>
    <table width="100%" border="1" cellspacing="0" bordercolor="#666666" class="gastos">
        <tr>
            <td width="15%" bgcolor="#a9d08e">
                <div align="center"><strong>FECHA</strong></div>
            </td>
            <td width="17%" bgcolor="#a9d08e">
                <div align="center"><strong>SOLICITANTE</strong></div>
            </td>
            <td width="20%" bgcolor="#a9d08e">
                <div align="center"><strong>DESTINATARIO</strong></div>
            </td>
            <td width="20%" bgcolor="#a9d08e">
                <div align="center"><strong>MOTIVO</strong></div>
            </td>
            <td width="20%" bgcolor="#a9d08e">
                <div align="center"><strong>DESCRICPCION</strong></div>
            </td>
            <td width="20%" bgcolor="#a9d08e">
                <div align="center"><strong>MONTO</strong></div>
            </td>
        </tr>
        @if (sizeof($ajuste_saldo_ingreso_reporte) == 0)
            <tr>
                <td colspan="7">
                    <div align="center">NO HAY AJUSTE DE INGRESO</div>
                </td>
            </tr>
        @else
            @foreach ($ajuste_saldo_ingreso_reporte as $ajuste_saldo_ingreso_data)
                <tr>
                    <td style="font-size:10px">
                        <div align="center">
                            {{ $ajuste_saldo_ingreso_data['fecha'] }}
                        </div>
                    </td>
                    <td style="font-size:10px">
                        <div align="center">
                            {{ $ajuste_saldo_ingreso_data['solicitante'] }}
                        </div>
                    </td>
                    <td style="font-size:10px">
                        <div align="center">
                            {{ $ajuste_saldo_ingreso_data['destinatario'] }}
                        </div>
                    </td>
                    <td style="font-size:10px">
                        <div align="center">
                            {{ $ajuste_saldo_ingreso_data['motivo'] }}
                        </div>
                    </td>
                    <td style="font-size:10px">
                        <div align="center">
                            {{ $ajuste_saldo_ingreso_data['descripcion'] }}
                        </div>
                    </td>
                    <td style="font-size:10px">
                        <div align="center">
                            {{ $ajuste_saldo_ingreso_data['monto'] }}
                        </div>
                    </td>
                </tr>
            @endforeach
            <tr>
                <td>&nbsp;</td>
                <td colspan="5" style="font-size:10px">
                    <div align="right"><strong>TOTAL DE AJUSTE INGRESO:&nbsp;</strong></div>
                </td>
                <td style="font-size:10px">
                    <div align="center">{{ number_format($ajuste_saldo_ingreso, 2, ',', ' ') }}</div>
                </td>
            </tr>
        @endif
    </table>
    <br>
    <p
        style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:75%; font-weight:bold; margin-top: -6px;">
    <div class="col-md-7" align="center"><b>Ajuste de Egreso</b></div>
    </p>
    <table width="100%" border="1" cellspacing="0" bordercolor="#666666" class="gastos">
        <tr>
            <td width="15%" bgcolor="#a9d08e">
                <div align="center"><strong>FECHA</strong></div>
            </td>
            <td width="17%" bgcolor="#a9d08e">
                <div align="center"><strong>SOLICITANTE</strong></div>
            </td>
            <td width="20%" bgcolor="#a9d08e">
                <div align="center"><strong>DESTINATARIO</strong></div>
            </td>
            <td width="20%" bgcolor="#a9d08e">
                <div align="center"><strong>MOTIVO</strong></div>
            </td>
            <td width="20%" bgcolor="#a9d08e">
                <div align="center"><strong>DESCRICPCION</strong></div>
            </td>
            <td width="20%" bgcolor="#a9d08e">
                <div align="center"><strong>MONTO</strong></div>
            </td>
        </tr>
        @if (sizeof($ajuste_saldo_egreso_reporte) == 0)
            <tr>
                <td colspan="6">
                    <div align="center">NO HAY AJUSTE DE EGRESO</div>
                </td>
            </tr>
        @else
            @foreach ($ajuste_saldo_egreso_reporte as $ajuste_saldo_egreso_data)
                <tr>
                    <td style="font-size:10px">
                        <div align="center">
                            {{ $ajuste_saldo_egreso_data['fecha'] }}
                        </div>
                    </td>
                    <td style="font-size:10px">
                        <div align="center">
                            {{ $ajuste_saldo_egreso_data['solicitante'] }}
                        </div>
                    </td>
                    <td style="font-size:10px">
                        <div align="center">
                            {{ $ajuste_saldo_egreso_data['destinatario'] }}
                        </div>
                    </td>
                    <td style="font-size:10px">
                        <div align="center">
                            {{ $ajuste_saldo_egreso_data['motivo'] }}
                        </div>
                    </td>
                    <td style="font-size:10px">
                        <div align="center">
                            {{ $ajuste_saldo_egreso_data['descripcion'] }}
                        </div>
                    </td>
                    <td style="font-size:10px">
                        <div align="center">
                            {{ $ajuste_saldo_egreso_data['monto'] }}
                        </div>
                    </td>
                </tr>
            @endforeach
            <tr>
                <td>&nbsp;</td>
                <td colspan="4" style="font-size:10px">
                    <div align="right"><strong>TOTAL DE AJUSTE EGRESO:&nbsp;</strong></div>
                </td>
                <td style="font-size:10px">
                    <div align="center">{{ number_format($ajuste_saldo_egreso, 2, ',', ' ') }}</div>
                </td>
            </tr>
        @endif
    </table>
    <br>
    <p
        style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:75%; font-weight:bold; margin-top: -6px;">
    <div class="col-md-7" align="center"><b>Transferencias Recibidas</b></div>
    </p>
    <table width="100%" border="1" cellspacing="0" bordercolor="#666666" class="gastos">
        <tr>
            <td width="15%" bgcolor="#a9d08e">
                <div align="center"><strong>FECHA</strong></div>
            </td>
            <td width="17%" bgcolor="#a9d08e">
                <div align="center"><strong>REMITENTE</strong></div>
            </td>
            <td width="20%" bgcolor="#a9d08e">
                <div align="center"><strong>DESTINATARIO</strong></div>
            </td>
            <td width="20%" bgcolor="#a9d08e">
                <div align="center"><strong>MONTO</strong></div>
            </td>
            <td width="20%" bgcolor="#a9d08e">
                <div align="center"><strong>#COMPROBANTE</strong></div>
            </td>
            <td width="35%" bgcolor="#a9d08e">
                <div align="center"><strong>MOTIVO</strong></div>
            </td>
            <td width="24%" bgcolor="#a9d08e">
                <div align="center"><strong>OBSERVACI&Oacute;N</strong></div>
            </td>
        </tr>
        @if (sizeof($transferencias_recibidas) == 0)
            <tr>
                <td colspan="7">
                    <div align="center">NO HAY TRANSFERENCIAS RECIBIDAS</div>
                </td>
            </tr>
        @else
            @foreach ($transferencias_recibidas as $transferencia_recibida_data)
                <tr>
                    <td style="font-size:10px">
                        <div align="center">
                            {{ $transferencia_recibida_data->fecha }}
                        </div>
                    </td>
                    <td style="font-size:10px">
                        <div align="center">
                            {{ $transferencia_recibida_data->empleadoEnvia->nombres . ' ' . $transferencia_recibida_data->empleadoEnvia->apellidos }}
                        </div>
                    </td>
                    <td style="font-size:10px">
                        <div align="center">
                            {{ $transferencia_recibida_data->empleadoRecibe->nombres . ' ' . $transferencia_recibida_data->empleadoRecibe->apellidos }}
                        </div>
                    </td>
                    <td style="font-size:10px">
                        <div align="center">
                            {{ $transferencia_recibida_data->monto }}
                        </div>
                    </td>
                    <td style="font-size:10px">
                        <div align="center">
                            {{ $transferencia_recibida_data->cuenta }}
                        </div>
                    </td>
                    <td style="font-size:10px">
                        <div align="center">
                            {{ $transferencia_recibida_data->motivo }}
                        </div>
                    </td>
                    <td style="font-size:10px">
                        <div align="center">
                            {{ $transferencia_recibida_data->observacion }}
                        </div>
                    </td>
                </tr>
            @endforeach
            <tr>
                <td>&nbsp;</td>
                <td colspan="5" style="font-size:10px">
                    <div align="right"><strong>TOTAL DE TRANSFERENCIAS RECIBIDAS:&nbsp;</strong></div>
                </td>
                <td style="font-size:10px">
                    <div align="center">{{ number_format($transferencia_recibida, 2, ',', '.') }}</div>
                </td>
            </tr>
        @endif
    </table>
</main>
<script type="text/php">
    if (isset($pdf)) {
            $text = "Pág {PAGE_NUM} de {PAGE_COUNT}";
            $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
            $pdf->page_text(10, 785, $text, $font, 12);
    }
</script>
</body>

</html>
