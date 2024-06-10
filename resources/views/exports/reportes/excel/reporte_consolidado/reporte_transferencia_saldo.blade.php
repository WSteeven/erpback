<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte</title>
    <style>
        @page {
            margin: 100px 25px;
        }

        .header {
            position: fixed;
            top: -55px;
            left: 0px;
            right: 0px;
            height: 80px;
            text-align: center;
            line-height: 35px;
        }

        .footer {
            position: fixed;
            bottom: -50px;
            left: 0px;
            right: 0px;
            height: 50px;
            color: #333333;
            text-align: center;
            line-height: 35px;
            font-size: 10px;
            font-style: italic;
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
                        <td width="17%">
                            <div align="center"></div>
                        </td>
                        <td width="83%" style="font-size:16px; font-weight:bold">
                            <div align="center">JPCONSTRUCRED C.LTDA</div>
                        </td>
                    </tr>
                </table>
            </div>
        </tr>
        <tr>
            <td>
                <div align="center">
                    <table width="100%">
                        <tr>
                            <td bgcolor="#bfbfbf" style="font-size:12px">
                                <div align="center"><strong>REPORTE DE TRANSFERENCIAS
                                        {{ ' DEL ' . date('d-m-Y', strtotime($fecha_inicio)) . ' AL ' . date('d-m-Y', strtotime($fecha_fin)) }}</strong>
                                </div>
                            </td>
                        </tr>

                        @if ($empleado == null)
                            <tr>
                                <td>
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
                                </td>
                            </tr>
                        @endif
                        @if ($empleado != null)
                            <tr>
                                <td>
                                    <table width="100%" border="1" cellspacing="0" bordercolor="#666666" class="gastos">
                                        <tr>
                                            <td width="100%"
                                                style="font-size:16px; font-weight:bold margin-top: -6px;"
                                                colspan="7">
                                                <div class="col-md-7" align="center">Transferencias Enviadas</div>
                                            </td>
                                        </tr>
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
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <table width="100%" border="1" cellspacing="0" bordercolor="#666666" class="gastos">
                                        <tr>
                                            <td width="100%" style="font-size:16px; font-weight:bold margin-top: -6px;"
                                            colspan="7">
                                            <div class="col-md-7" align="center">Transferencias Recibidas</div>
                                        </td>
                                        </tr>
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
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
            </td>
        </tr>

    </table>


</body>

</html>
