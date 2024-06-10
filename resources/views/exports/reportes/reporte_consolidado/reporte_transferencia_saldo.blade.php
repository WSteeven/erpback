<html>
@php
    $fecha = new Datetime();
    $logo_principal = 'data:image/png;base64,' . base64_encode(file_get_contents(public_path() . $configuracion['logo_claro']));
    $logo_watermark = 'data:image/png;base64,' . base64_encode(file_get_contents(public_path() . $configuracion['logo_marca_agua']));
@endphp
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte Consolidado</title>
    <style>
        @page {
            margin: 2px 15px 5px 15px;
        }

        body {
            background-image: url({{ $logo_watermark }});
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
                    <div class="col-md-3"><img
                            src="{{$logo_principal }}"
                            width="90"></div>
                </td>
                <td style="width: 100%">
                    <div class="col-md-7" align="center"><b style="font-size: 75%">REPORTE TRANSFERENCIA DE SALDOS
                            {{ ' DEL ' . date('d-m-Y', strtotime($fecha_inicio)) . ' AL ' . date('d-m-Y', strtotime($fecha_fin)) }}</b>
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
                    <div style="margin: 0%; margin-bottom: 0px; margin-top: 0px;" align="center">{{ $copyright }}</div>
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
        @if ($empleado == null)
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
                @if ($transferencia_total == 0)
                    <tr>
                        <td colspan="7">
                            <div align="center">NO HAY TRANSFERENCIAS </div>
                        </td>
                    </tr>
                @else
                    @foreach ($transferencias as $transferencia_data)
                        <tr>
                            <td style="font-size:10px">
                                <div align="center">
                                    {{ $transferencia_data->fecha }}
                                </div>
                            </td>
                            <td style="font-size:10px">
                                <div align="center">
                                    {{ $transferencia_data->empleadoEnvia->nombres . ' ' . $transferencia_data->empleadoEnvia->apellidos }}
                                </div>
                            </td>
                            <td style="font-size:10px">
                                <div align="center">
                                    {{ $transferencia_data->empleadoRecibe->nombres . ' ' . $transferencia_data->empleadoRecibe->apellidos }}
                                </div>
                            </td>
                            <td style="font-size:10px">
                                <div align="center">
                                    {{ $transferencia_data->monto }}
                                </div>
                            </td>
                            <td style="font-size:10px">
                                <div align="center">
                                    {{ $transferencia_data->cuenta }}
                                </div>
                            </td>
                            <td style="font-size:10px">
                                <div align="center">
                                    {{ $transferencia_data->motivo }}
                                </div>
                            </td>
                            <td style="font-size:10px">
                                <div align="center">
                                    {{ $transferencia_data->observacion }}
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td>&nbsp;</td>
                        <td colspan="5" style="font-size:10px">
                            <div align="right"><strong>TOTAL DE TRANSFERENCIAS :&nbsp;</strong></div>
                        </td>
                        <td style="font-size:10px">
                            <div align="center">{{ number_format($transferencia_total, 2, ',', '.') }}</div>
                        </td>
                    </tr>
                @endif
            </table>
        @endif
        @if ($empleado != null)
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
                    @foreach ($transferencias_enviadas as $transferencia_enviada_data)
                        <tr>
                            <td style="font-size:10px">
                                <div align="center">
                                    {{ $transferencia_enviada_data->fecha }}
                                </div>
                            </td>
                            <td style="font-size:10px">
                                <div align="center">
                                    {{ $transferencia_enviada_data->empleadoEnvia->nombres . ' ' . $transferencia_enviada_data->empleadoEnvia->apellidos }}
                                </div>
                            </td>
                            <td style="font-size:10px">
                                <div align="center">
                                    {{ $transferencia_enviada_data->empleadoRecibe->nombres . ' ' . $transferencia_enviada_data->empleadoRecibe->apellidos }}
                                </div>
                            </td>
                            <td style="font-size:10px">
                                <div align="center">
                                    {{ $transferencia_enviada_data->monto }}
                                </div>
                            </td>
                            <td style="font-size:10px">
                                <div align="center">
                                    {{ $transferencia_enviada_data->cuenta }}
                                </div>
                            </td>
                            <td style="font-size:10px">
                                <div align="center">
                                    {{ $transferencia_enviada_data->motivo }}
                                </div>
                            </td>
                            <td style="font-size:10px">
                                <div align="center">
                                    {{ $transferencia_enviada_data->observacion }}
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
                            <div align="center">{{ number_format($transferencia_enviada, 2, ',', ' ') }}</div>
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
        @endif


    </main>
</body>

</html>
