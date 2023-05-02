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
                                <div align="center"><strong>REPORTE SEMANAL DE GASTOS DEL
                                        {{ $fecha_inicio . ' AL ' . $fecha_fin }}</strong></div>
                            </td>
                        </tr>
                        <tr>
                            <td bgcolor="#bfbfbf" style="font-size:14px">
                                <div align="center">
                                    <strong>{{ $datos_usuario_logueado['apellidos'] . ' ' . $datos_usuario_logueado['nombres'] }}</strong>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table width="100%" border="1" align="left" cellpadding="0" cellspacing="0" class="saldos_depositados">
                                    <tr>
                                        <td colspan="4" style="font-size:10px" bgcolor="#a9d08e"><strong>SALDOS
                                                DEPOSITADOS</strong></td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:10px" width="8%"><strong>Fecha</strong></td>
                                        <td style="font-size:10px"width="7%"><strong>Monto</strong></td>
                                        <td style="font-size:10px"width="9%"><strong>Tipo Saldo</strong></td>
                                        <td style="font-size:10px" width="80%"><strong>Descripción</strong></td>
                                    </tr>
                                    @if (sizeof($datos_saldo_depositados_semana) > 0)
                                        @foreach ($datos_saldo_depositados_semana as $dato)
                                            <tr>
                                                <td style="font-size:10px">{{  date("d-m-Y", strtotime(  $dato->fecha)) }}</td>
                                                <td style="font-size:10px">
                                                    {{ number_format($dato->monto, 2, ',', '.') }}</td>
                                                <td style="font-size:10px">{{$dato->tipo_fondo!=null? $dato->tipo_fondo->descripcion:'' }}
                                                </td>
                                                <td style="font-size:10px">{{ $dato->descripcion_saldo }}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td style="font-size:10px" colspan="4">NO SE REALIZARON DEPOSITOS.</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td style="font-size:10px" colspan="3"><div align="right"><strong>SALDO ANTERIOR:&nbsp;</strong></div><strong></strong></td>
                                        <td style="font-size:10px"> <div align="right"> {{ number_format($saldo_anterior, 2, ',', ' ') }} </div></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" style="font-size:10px">
                                            <div align="right"><strong>SALDO DEPOSITADO:&nbsp;</strong></div>
                                        </td>
                                        <td style="font-size:10px">
                                            <div align="right"> {{ number_format($acreditaciones, 2, ',', ' ') }} </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" style="font-size:10px">
                                            <div align="right"><strong>TRANSFERENCIAS REALIZADAS:&nbsp;</strong></div>
                                        </td>
                                        <td style="font-size:10px">
                                            <div align="right"> {{ number_format($transferencia, 2, ',', ' ') }} </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" style="font-size:10px">
                                            <div align="right"><strong>GASTOS REALIZADOS:&nbsp;</strong></div>
                                        </td>
                                        <td style="font-size:10px">
                                            <div align="right"> {{ number_format($gastos_realizados, 2, ',', ' ') }} </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" style="font-size:10px">
                                            <div align="right"><strong>SALDO ACTUAL:&nbsp;</strong></div>
                                        </td>
                                        <td style="font-size:10px">
                                            <div align="right"> {{ number_format($ultimo_saldo, 2, ',', ' ') }} </div>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table width="100%" border="1" cellspacing="0" bordercolor="#666666"
                                    style="margin-top:8 ">
                                    <tr>
                                        <td bgcolor="#a9d08e" width="100%">
                                            <div align="center"><strong>N&deg;</strong></div>
                                        </td>
                                        <td  bgcolor="#a9d08e" width="100%">
                                            <div align="center"><strong>FECHA</strong></div>
                                        </td>
                                        <td bgcolor="#a9d08e">
                                            <div align="center"><strong>TAREA</strong></div>
                                        </td>
                                        <td  bgcolor="#a9d08e" width="100%">
                                            <div align="center"><strong># FACTURA</strong></div>
                                        </td>
                                        <td  bgcolor="#a9d08e" width="100%">
                                            <div align="center"><strong>RUC</strong></div>
                                        </td>
                                        <td bgcolor="#a9d08e">
                                            <div align="center" width="100%"><strong>AUTORIZACION ESPECIAL</strong></div>
                                        </td>
                                        <td bgcolor="#a9d08e" width="100%">
                                            <div align="center"><strong>DETALLE</strong></div>
                                        </td>
                                        <td  bgcolor="#a9d08e" width="100%">
                                            <div align="center"><strong>SUB DETALLE</strong></div>
                                        </td>
                                        <td  bgcolor="#a9d08e" width="100%">
                                            <div align="center"><strong>OBSERVACI&Oacute;N</strong></div>
                                        </td>
                                        <td bgcolor="#a9d08e">
                                            <div align="center"><strong>CANT.</strong></div>
                                        </td>
                                        <td  bgcolor="#a9d08e">
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
                                            @php
                                                $sub_total = $sub_total + (float) $dato->total;
                                            @endphp
                                            <tr>
                                                <td style="font-size:10px">
                                                    <div align="center">{{ $dato->id }}</div>
                                                </td>
                                                <td style="font-size:10px">
                                                    <div align="center">{{ $dato->fecha }}</div>
                                                </td>
                                                <td style="font-size:10px">
                                                    <div align="center"> {{ $dato->tarea != null ? $dato->tareacodigo_tarea : 'Sin Tarea' }}</div>
                                                </td>
                                                <td style="font-size:10px">
                                                    <div align="center">{{ $dato->factura }}</div>
                                                </td>
                                                <td style="font-size:10px">
                                                    <div align="center">{{ $dato->ruc.' .' }}</div>
                                                </td>
                                                <td style="font-size:10px">
                                                    <div align="center">   {{ $dato->aut_especial_user->nombres . '' . $dato->aut_especial_user->apellidos }}</div>
                                                </td>
                                                <td style="font-size:10px">
                                                    <div align="center">{{$dato->detalle_info->descripcion}}</div>
                                                </td>
                                                <td style="font-size:10px">
                                                    <div align="center">  @foreach($dato->sub_detalle_info as $sub_detalle)
                                                        {{ $sub_detalle->descripcion }}
                                                        @if (!$loop->last)
                                                           ,
                                                        @endif
                                                     @endforeach</div>
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
                                    @endif
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table width="100%" border="1" cellspacing="0"
                                    bordercolor="#666666"style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px;margin-top: 40px;">
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td colspan="10" style="font-size:10px">
                                            <div align="right"><strong>SUB TOTAL:&nbsp;</strong></div>
                                        </td>
                                        <td style="font-size:10px">
                                            <div align="center">{{ number_format($sub_total, 2, ',', ' ') }}</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td colspan="10" style="font-size:10px">
                                            <div align="right"><strong>TOTAL:&nbsp;</strong></div>
                                        </td>
                                        <td style="font-size:10px">
                                            <div align="center">
                                                {{ number_format($ultimo_saldo, 2, ',', ' ') }}
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                    </table>
                </div>
            </td>
        </tr>

    </table>


</body>

</html>
