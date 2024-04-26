<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de Gastos</title>
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
    @php
        $total = 0;
    @endphp
    <table
        style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px;margin-top: 20px;">
        <tr>
            <td>
                <div class="header">
                    <table
                        style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px;page-break-inside: avoid;">
                        <tr>
                            <td style="font-size:12px">
                                <div align="center" ><strong>REPORTE AUTORIZACIONES CON
                                        ESTADO
                                        {{ $tipo_reporte->descripcion . ' DEL ' . $fecha_inicio . ' AL ' . $fecha_fin }}
                                    </strong>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size:12px">
                                <div align="center" >
                                    <strong>{{ $usuario->nombres . ' ' . $usuario->apellidos }}</strong>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>

        <tr>
            <td>
                <div align="center">
                    <table width="100%" border="1" align="left" cellpadding="0" cellspacing="0">
                        <tr>
                            <td bgcolor="#a9d08e" style="font-size:10px" width="3%">
                                <div align="center"><strong>#</strong></div>
                            </td>
                            <td bgcolor="#a9d08e" style="font-size:10px">
                                <div align="center"><strong>EMPLEADO</strong></div>
                            </td>
                            <td bgcolor="#a9d08e" style="font-size:10px" width="6%">
                                <div align="center"><strong>LUGAR</strong></div>
                            </td>
                            <td bgcolor="#a9d08e" style="font-size:10px" width="5%">
                                <div align="center"><strong>FECHA</strong></div>
                            </td>
                            <td bgcolor="#a9d08e" style="font-size:10px" width="5%">
                                <div align="center"><strong>FECHA DE AUTORIZACION</strong></div>
                            </td>
                            <td bgcolor="#a9d08e" style="font-size:10px" width="8%">
                                <div align="center"><strong>#COMPROBANTE</strong></div>
                            </td>
                            <td bgcolor="#a9d08e" style="font-size:10px" width="13%">
                                <div align="center"><strong>OBSERVACI&Oacute;N</strong></div>
                            </td>
                            <td bgcolor="#a9d08e" style="font-size:10px">
                                <div align="center"><strong>COMENTARIO</strong></div>
                            </td>
                            <td bgcolor="#a9d08e" style="font-size:10px">
                                <div align="center"><strong>CENTRO DE COSTO</strong></div>
                            </td>
                            <td bgcolor="#a9d08e" style="font-size:10px">
                                <div align="center"><strong>SUBCENTRO DE COSTO</strong></div>
                            </td>
                            <td bgcolor="#a9d08e" style="font-size:10px" width="5%">
                                <div align="center"><strong>MONTO</strong></div>
                            </td>
                        </tr>

                        @foreach ($datos_reporte as $gasto)
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

                                <td style="font-size:10px">
                                    <div align="center">{{ date('d-m-Y', strtotime($gasto['fecha_autorizacion'])) }}
                                    </div>
                                </td>
                                <td style="font-size:10px">
                                    <div align="left">{{ $gasto['factura'] }}</div>
                                </td>
                                <td style="font-size:10px">
                                    <div align="left">{{ $gasto['observacion'] }}</div>
                                </td>
                                <td style="font-size:10px">
                                    <div align="left">{{ $gasto['detalle_estado'] }}</div>
                                </td>
                                <td style="font-size:10px">{{ $gasto['centro_costo'] }}</td>
                                <td style="font-size:10px">{{ $gasto['sub_centro_costo'] }}</td>
                                <td style="font-size:10px">
                                    <div align="right">
                                        {{ number_format($gasto['total'], 2, ',', '.') }}</div>
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="10" style="font-size:10px" width="29%">
                                <div align="right"><strong>Total</strong></div>
                            </td>
                            <td style="font-size:10px" width="10%">
                                <div align="right">
                                    <strong>{{ number_format($total, 2, ',', '.') }}</strong>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>

    </table>


</body>

</html>
