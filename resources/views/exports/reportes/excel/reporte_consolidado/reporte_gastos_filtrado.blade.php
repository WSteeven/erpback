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
                            <div align="center">JEAN PATRICIO PAZMI&Ntilde;O BARROS</div>
                        </td>
                    </tr>
                    <tr>
                        <td width="17%">
                            <div align="center"></div>
                        </td>
                        <td width="83%" style="font-size:16px; font-weight:bold">
                            <div align="center">RUC:0702875618001</div>
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
                                    <tr class="row" style="width:auto">
                                        <td style="width: 100%">
                                            <div class="col-md-7" align="center"><b>{{ $titulo }}</b></div>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="col-md-7" align="center"><b>{{ $subtitulo }}</b></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td bgcolor="#a9d08e" style="font-size:10px" width="29%">
                                            <div align="center"><strong>Nombres y Apellidos</strong></div>
                                        </td>
                                        <td bgcolor="#a9d08e" style="font-size:10px" width="15%">
                                            <div align="center"><strong>Usuario</strong></div>
                                        </td>
                                        <td bgcolor="#a9d08e" style="font-size:10px" width="17%">
                                            <div align="center"><strong>Fecha</strong></div>
                                        </td>
                                        <td bgcolor="#a9d08e" style="font-size:10px" width="29%">
                                            <div align="center"><strong>Descripci&oacute;n</strong></div>
                                        </td>
                                        <td bgcolor="#a9d08e" style="font-size:10px" width="10%">
                                            <div align="center"><strong>Monto</strong></div>
                                        </td>
                                    </tr>
                                    @foreach ($gastos as $gasto)
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
                                            <td style="font-size:10px" width="17%">
                                                <div align="center">{{ $gasto['fecha'] }}</div>
                                            </td>
                                            <td style="font-size:10px" width="29%">
                                                <div align="left">{{ $gasto['detalle_estado'] }}</div>
                                            </td>
                                            <td style="font-size:10px" width="10%">
                                                <div align="right">
                                                    {{ number_format($gasto['total'], 2, ',', '.') }}</div>
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
