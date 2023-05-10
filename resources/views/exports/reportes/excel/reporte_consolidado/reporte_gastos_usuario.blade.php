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
                                <div align="center"><strong>{{ $usuario }}</strong></div>
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
                                        <td bgcolor="#a9d08e" style="font-size:10px" width="13%">
                                            <div align="center"><strong>Fecha</strong></div>
                                        </td>
                                        <td bgcolor="#a9d08e" style="font-size:10px" width="15%">
                                            <div align="center"><strong>Descripcion del Gasto</strong></div>
                                        </td>
                                        <td bgcolor="#a9d08e" style="font-size:10px" width="29%">
                                            <div align="center"><strong>Comentario&oacute;n</strong></div>
                                        </td>
                                        <td bgcolor="#a9d08e" style="font-size:10px" width="15%">
                                            <div align="center"><strong>Autorizador</strong></div>
                                        </td>
                                        <td bgcolor="#a9d08e" style="font-size:10px" width="10%">
                                            <div align="center"><strong>Monto</strong></div>
                                        </td>
                                    </tr>
                                    @foreach ($gastos as $gasto)
                                        @php
                                            $total = number_format($gasto['total'], 2) + $total;
                                        @endphp
                                        <tr>
                                            <td style="font-size:10px" width="29%">
                                                <div align="left">
                                                    {{ $gasto['usuario']->nombres . ' ' . $gasto['usuario']->apellidos }}
                                                </div>
                                            </td>
                                            <td style="font-size:10px" width="15%">
                                                <div align="left">{{ $gasto['empleado_info']->name }}
                                                </div>
                                            </td>
                                            <td style="font-size:10px" width="13%">
                                                <div align="center">{{ date('d-m-Y', strtotime($gasto['fecha'])) }}
                                                </div>
                                            </td>
                                            <td style="font-size:10px" width="29%">
                                                <div align="left">
                                                    {{ $gasto['sub_detalle_desc'] }}
                                                </div>
                                            </td>
                                            <td style="font-size:10px" width="29%">
                                                <div align="left">{{ $gasto['detalle_estado'] }}</div>
                                            </td>
                                            <td style="font-size:10px" width="29%">
                                                <div align="left">
                                                    {{ $gasto['autorizador'] }}
                                                </div>
                                            </td>
                                            <td style="font-size:10px" width="10%">
                                                <div align="right">
                                                    {{ number_format($gasto['total'], 2, ',', '.') }}</div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="6" style="font-size:10px" width="29%">
                                            <div align="right"><strong>Total</strong></div>
                                        </td>
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
