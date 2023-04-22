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
                                <div align="center"><strong>REPORTE CONSOLIDADO
                                        {{ ' DEL ' . date('d-m-Y', strtotime($fecha_inicio)) . ' AL ' . date('d-m-Y', strtotime($fecha_fin)) }}</strong>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table width="100%" border="1" align="left" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td bgcolor="#a9d08e" style="font-size:10px" width="29%">
                                            <div align="center"><strong>Nombres y Apellidos</strong></div>
                                        </td>
                                        <td bgcolor="#a9d08e" style="font-size:10px" width="15%">
                                            <div align="center"><strong>Usuario</strong></div>
                                        </td>
                                        <td bgcolor="#a9d08e" style="font-size:10px" width="17%">
                                            <div align="center"><strong>Fecha Consolidado</strong></div>
                                        </td>
                                        <td bgcolor="#a9d08e" style="font-size:10px" width="29%">
                                            <div align="center"><strong>Descripci&oacute;n</strong></div>
                                        </td>
                                        <td bgcolor="#a9d08e" style="font-size:10px" width="10%">
                                            <div align="center"><strong>Monto</strong></div>
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
                                            <div align="left">{{ $usuario->name }}
                                            </div>
                                        </td>
                                        <td style="font-size:10px" width="17%">
                                            <div align="center">{{ date('d-m-Y', strtotime($fecha_anterior)) }}</div>
                                        </td>
                                        <td style="font-size:10px" width="29%">
                                            <div align="left">Saldo Inicial</div>
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
                                            <div align="left">{{ $usuario->name }}
                                            </div>
                                        </td>
                                        <td style="font-size:10px" width="17%">
                                            <div align="center">
                                                {{ date('d-m-Y', strtotime($fecha_inicio)) . ' ' . date('d-m-Y', strtotime($fecha_fin)) }}
                                            </div>
                                        </td>
                                        <td style="font-size:10px" width="29%">
                                            <div align="left">Acreditaciones</div>
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
                                            <div align="left">{{ $usuario->name }}
                                            </div>
                                        </td>
                                        <td style="font-size:10px" width="17%">
                                            <div align="center">
                                                {{ date('d-m-Y', strtotime($fecha_inicio)) . ' ' . date('d-m-Y', strtotime($fecha_fin)) }}
                                            </div>
                                        </td>
                                        <td style="font-size:10px" width="29%">
                                            <div align="left">Transferencias Enviadas</div>
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
                                            <div align="left">{{ $usuario->name }}
                                            </div>
                                        </td>
                                        <td style="font-size:10px" width="17%">
                                            <div align="center">
                                                {{ date('d-m-Y', strtotime($fecha_inicio)) . ' ' . date('d-m-Y', strtotime($fecha_fin)) }}
                                            </div>
                                        </td>
                                        <td style="font-size:10px" width="29%">
                                            <div align="left">Transferencias Recibidas</div>
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
                                            <div align="left">{{ $usuario->name }}
                                            </div>
                                        </td>
                                        <td style="font-size:10px" width="17%">
                                            <div align="center">
                                                {{ date('d-m-Y', strtotime($fecha_inicio)) . ' ' . date('d-m-Y', strtotime($fecha_fin)) }}
                                            </div>
                                        </td>
                                        <td style="font-size:10px" width="29%">
                                            <div align="left">Gastos</div>
                                        </td>
                                        <td style="font-size:10px" width="10%">
                                            <div align="right">
                                                {{ number_format($gastos, 2, ',', '.') }}</div>
                                        </td>
                                    </tr>
                                    <!--Fin Gastos-->
                                    <!--Saldo Final-->
                                    <tr>
                                        <td colspan="4" style="font-size:10px">
                                            <div align="right"><strong>TOTAL:</strong></div>
                                        </td>
                                        <td style="font-size:10px" align="center">
                                            <div align="right" style="margin-right:20px;">
                                                {{ number_format($nuevo_saldo, 2, ',', ' ') }}</div>
                                        </td>
                                    </tr>
                                    <!--Fin Saldo Final-->
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table width="100%" border="1" cellspacing="0" bordercolor="#666666"
                                    style="margin-top:8 ">
                                    <tr>
                                        <td width="100%" style="font-size:16px; font-weight:bold margin-top: -6px;"
                                            colspan="12">
                                            <div class="col-md-7" align="center">Detalle de Gastos</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td bgcolor="#a9d08e" width="100%">
                                            <div align="center"><strong>N&deg;</strong></div>
                                        </td>
                                        <td bgcolor="#a9d08e" width="100%">
                                            <div align="center"><strong>FECHA</strong></div>
                                        </td>
                                        <td bgcolor="#a9d08e">
                                            <div align="center"><strong>TAREA</strong></div>
                                        </td>
                                        <td bgcolor="#a9d08e" width="100%">
                                            <div align="center"><strong># FACTURA</strong></div>
                                        </td>
                                        <td bgcolor="#a9d08e" width="100%">
                                            <div align="center"><strong>RUC</strong></div>
                                        </td>
                                        <td bgcolor="#a9d08e">
                                            <div align="center" width="100%"><strong>AUTORIZACION ESPECIAL</strong>
                                            </div>
                                        </td>
                                        <td bgcolor="#a9d08e" width="100%">
                                            <div align="center"><strong>DETALLE</strong></div>
                                        </td>
                                        <td bgcolor="#a9d08e" width="100%">
                                            <div align="center"><strong>SUB DETALLE</strong></div>
                                        </td>
                                        <td bgcolor="#a9d08e" width="100%">
                                            <div align="center"><strong>OBSERVACI&Oacute;N</strong></div>
                                        </td>
                                        <td bgcolor="#a9d08e">
                                            <div align="center"><strong>CANT.</strong></div>
                                        </td>
                                        <td bgcolor="#a9d08e">
                                            <div align="center"><strong>V. UNI.</strong></div>
                                        </td>
                                        <td bgcolor="#a9d08e">
                                            <div align="center"><strong>TOTAL</strong></div>
                                        </td>
                                    </tr>
                                    @if (sizeof($gastos_reporte) == 0)
                                        <tr>
                                            <td colspan="12">
                                                <div align="center">NO HAY FONDOS ROTATIVOS APROBADOS</div>
                                            </td>
                                        </tr>
                                    @else
                                        @foreach ($gastos_reporte as $dato)
                                            <tr>
                                                <td style="font-size:10px">
                                                    <div align="center">{{ $dato->id }}</div>
                                                </td>
                                                <td style="font-size:10px">
                                                    <div align="center">{{ $dato->fecha }}</div>
                                                </td>
                                                <td style="font-size:10px">
                                                    <div align="center">
                                                        {{ $dato->tarea != null ? $dato->tareacodigo_tarea : 'Sin Tarea' }}
                                                    </div>
                                                </td>
                                                <td style="font-size:10px">
                                                    <div align="center">{{ $dato->factura }}</div>
                                                </td>
                                                <td style="font-size:10px">
                                                    <div align="center">{{ $dato->ruc }}</div>
                                                </td>
                                                <td style="font-size:10px">
                                                    <div align="center">
                                                        {{ $dato->aut_especial_user->nombres . '' . $dato->aut_especial_user->apellidos }}
                                                    </div>
                                                </td>
                                                <td style="font-size:10px">
                                                    <div align="center">{{ $dato->detalle_info->descripcion }}</div>
                                                </td>
                                                <td style="font-size:10px">
                                                    <div align="center">
                                                        @foreach ($dato->sub_detalle_info as $sub_detalle)
                                                            {{ $sub_detalle->descripcion }}
                                                            @if (!$loop->last)
                                                                ,
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </td>
                                                <td style="font-size:10px">
                                                    <div align="center">{{ $dato->observacion }}</div>
                                                </td>
                                                <td style="font-size:10px">
                                                    <div align="center">{{ $dato->cantidad }}</div>
                                                </td>
                                                <td style="font-size:10px">
                                                    <div align="center">
                                                        {{ number_format($dato->valor_u, 2, ',', '.') }}</div>
                                                </td>
                                                <td style="font-size:10px">
                                                    <div align="center">{{ number_format($dato->total, 2, ',', '.') }}
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td colspan="10" style="font-size:10px">
                                                <div align="right"><strong>TOTAL DE GASTOS:&nbsp;</strong></div>
                                            </td>
                                            <td style="font-size:10px">
                                                <div align="center">{{ number_format($sub_total, 2, ',', ' ') }}</div>
                                            </td>
                                        </tr>
                                    @endif
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table width="100%" border="1" cellspacing="0" bordercolor="#666666"
                                    class="gastos">
                                    <tr>
                                        <td width="100%" style="font-size:16px; font-weight:bold margin-top: -6px;"
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
                                        @foreach ($transferencias_enviadas as $transferencia_enviada)
                                            <tr>
                                                <td style="font-size:10px">
                                                    <div align="center">
                                                        {{ $transferencia_enviada->fecha }}
                                                    </div>
                                                </td>
                                                <td style="font-size:10px">
                                                    <div align="center">
                                                        {{ $transferencia_enviada->usuario_envia->nombres . ' ' . $transferencia_enviada->usuario_envia->apellidos }}
                                                    </div>
                                                </td>
                                                <td style="font-size:10px">
                                                    <div align="center">
                                                        {{ $transferencia_enviada->usuario_recibe->nombres . ' ' . $transferencia_enviada->usuario_recibe->apellidos }}
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
                                                <div align="right"><strong>TOTAL DE TRANSFERENCIAS
                                                        ENVIADAS:&nbsp;</strong></div>
                                            </td>
                                            <td style="font-size:10px">
                                                <div align="center">{{ number_format($transferencia, 2, ',', ' ') }}
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table width="100%" border="1" cellspacing="0" bordercolor="#666666"
                                    class="gastos">
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
                                            <div align="center"><strong># Cuenta</strong></div>
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
                                                        {{ $transferencia_recibida_data->usuario_envia->nombres . ' ' . $transferencia_recibida_data->usuario_envia->apellidos }}
                                                    </div>
                                                </td>
                                                <td style="font-size:10px">
                                                    <div align="center">
                                                        {{ $transferencia_recibida_data->usuario_recibe->nombres . ' ' . $transferencia_recibida_data->usuario_recibe->apellidos }}
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
                                                <div align="right"><strong>TOTAL DE TRANSFERENCIAS
                                                        RECIBIDAS:&nbsp;</strong></div>
                                            </td>
                                            <td style="font-size:10px">
                                                <div align="center">
                                                    {{ number_format($transferencia_recibida, 2, ',', '.') }}</div>
                                            </td>
                                        </tr>
                                    @endif
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
