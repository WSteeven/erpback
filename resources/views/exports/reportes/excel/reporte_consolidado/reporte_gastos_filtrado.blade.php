<!DOCTYPE html>
<html lang="en">
@php



 @endphp

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
            left: 0;
            right: 0;
            height: 80px;
            text-align: center;
            line-height: 35px;
        }

        .footer {
            position: fixed;
            bottom: -50px;
            left: 0;
            right: 0;
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
    @php
        $total = 0;
    @endphp
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
                                <div align="center"><strong>{{ $titulo }}</strong></div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table width="100%" border="1" align="left" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td bgcolor="#a9d08e" style="font-size:10px" width="3%">
                                            <div align="center"><strong>#</strong></div>
                                        </td>
                                        <td bgcolor="#a9d08e" style="font-size:10px" width="13%">
                                            <div align="center"><strong>NOMBRES Y APELLIDOS</strong></div>
                                        </td>
                                        <td bgcolor="#a9d08e" style="font-size:10px" width="4%">
                                            <div align="center"><strong>LUGAR</strong></div>
                                        </td>
                                        <td bgcolor="#a9d08e" style="font-size:10px" width="10%">
                                            <div align="center"><strong>FECHA</strong></div>
                                        </td>
                                        @if ($subtitulo == '' || $tipo_filtro != 3)
                                            <td bgcolor="#a9d08e" style="font-size:10px" width="20%">
                                                <div align="center"><strong>DESCRIPCI&Oacute;N DEL GASTO</strong></div>
                                            </td>
                                        @endif
                                        <td bgcolor="#a9d08e" style="font-size:10px" width="8%">
                                            <div align="center"><strong>#COMPROBANTE</strong></div>
                                        </td>
                                        <td bgcolor="#a9d08e" style="font-size:10px" width="8%">
                                            <div align="center"><strong>RUC</strong></div>
                                        </td>
                                        <td bgcolor="#a9d08e" style="font-size:10px" width="20%">
                                            <div align="center"><strong>OBSERVACI&Oacute;N</strong></div>
                                        </td>
                                        <td bgcolor="#a9d08e" style="font-size:10px" width="20%">
                                            <div align="center"><strong>COMENTARIO</strong></div>
                                        </td>
                                        <td bgcolor="#a9d08e" style="font-size:10px">
                                            <div align="center"><strong>CENTRO DE COSTO</strong></div>
                                        </td>
                                        <td bgcolor="#a9d08e" style="font-size:10px">
                                            <div align="center"><strong>SUBCENTRO DE COSTO</strong></div>
                                        </td>
                                        <td bgcolor="#a9d08e" style="font-size:10px" width="3%">
                                            <div align="center"><strong>AUTORIZADOR</strong></div>
                                        </td>
                                        @if ($subdetalle == 96)
                                            <td bgcolor="#a9d08e" style="font-size:8px" width="3%">
                                                <div align="center"><strong>KILOMETRAJE</strong></div>
                                            </td>
                                            <td bgcolor="#a9d08e" style="font-size:10px" width="20%">
                                                <div align="center"><strong>PLACA</strong></div>
                                            </td>
                                        @endif
                                        <td bgcolor="#a9d08e" style="font-size:10px" width="5%">
                                            <div align="center"><strong>MONTO</strong></div>
                                        </td>
                                    </tr>

                                    @foreach ($gastos as $gasto)
                                        @php
                                            $total = number_format($gasto['total'], 2) + $total;
                                        @endphp
                                        <tr>
                                            <td style="font-size:10px">
                                                <div align="left">{{ $gasto['num_registro'] }}
                                                </div>
                                            </td>
                                            <td style="font-size:10px">
                                                <div align="left">
                                                    {{ $gasto['usuario']->nombres . ' ' . $gasto['usuario']->apellidos }}
                                                </div>
                                            </td>
                                            <td style="font-size:10px">
                                                <div align="left">{{ $gasto['lugar'] }}
                                                </div>
                                            </td>
                                            <td style="font-size:10px">
                                                <div align="center">{{ date('d-m-Y', strtotime($gasto['fecha'])) }}</div>
                                            </td>
                                            @if ($subtitulo == '' || $tipo_filtro != 3)
                                                <td style="font-size:10px">
                                                    <div align="left">
                                                        {{ strtoupper($gasto['sub_detalle_desc']) }}
                                                    </div>
                                                </td>
                                            @endif
                                            <td style="font-size:10px">
                                                @if ($gasto['factura'] == '')
                                                    <div align="left">{{ $gasto['num_comprobante'] }}</div>
                                                @else
                                                    <div align="left">{{ $gasto['factura'] }}</div>
                                                @endif
                                            </td>
                                            <td style="font-size:10px">
                                                <div align="left">{{ $gasto['ruc'] }}</div>
                                            </td>
                                            <td style="font-size:10px">
                                                <div align="left">{{ $gasto['observacion'] }}</div>
                                            </td>
                                            <td style="font-size:10px">
                                                <div align="left">{{ $gasto['detalle_estado'] }}</div>
                                            </td>
                                            <td style="font-size:10px; word-wrap: break-word;">{{ $gasto['centro_costo'] }}</td>
                                            <td style="font-size:10px; word-wrap: break-word;">{{ $gasto['sub_centro_costo'] }}</td>
                                            <td style="font-size:10px" width="29%">
                                                <div align="left">
                                                    {{ $gasto['autorizador'] }}
                                                </div>
                                            </td>
                                            @if ($subdetalle == 96)
                                                <td style="font-size:10px">
                                                    <div align="right">{{ $gasto['kilometraje'] }}</div>
                                                </td>
                                                <td style="font-size:10px">
                                                    <div align="right">{{ $gasto['placa'] }}</div>
                                                </td>
                                            @endif
                                            <td style="font-size:10px">
                                                <div align="right">
                                                    {{ number_format($gasto['total'], 2, ',', '.') }}</div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        @if ($subtitulo == '')
                                            @if ($subdetalle == 96)
                                                <td colspan="13" style="font-size:10px" width="29%">
                                                    <div align="right"><strong>Total</strong></div>
                                                </td>
                                            @else
                                                <td colspan="11" style="font-size:10px" width="29%">
                                                    <div align="right"><strong>Total</strong></div>
                                                </td>
                                            @endif
                                        @else
                                            @if ($subdetalle == 96)
                                                <td colspan="12" style="font-size:10px" width="29%">
                                                    <div align="right"><strong>Total</strong></div>
                                                </td>
                                            @else
                                                <td colspan="11" style="font-size:10px" width="29%">
                                                    <div align="right"><strong>Total</strong></div>
                                                </td>
                                            @endif
                                        @endif


                                        <td style="font-size:10px" width="10%">
                                            <div align="right">
                                                <strong>{{ number_format($total, 2, ',', '.') }}</strong>
                                            </div>
                                        </td>
                                    </tr>
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
