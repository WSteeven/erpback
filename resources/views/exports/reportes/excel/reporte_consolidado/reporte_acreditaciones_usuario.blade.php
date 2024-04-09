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
                    <tr>
                        <td width="17%">
                            <div align="center"></div>
                        </td>
                        <td width="83%" bgcolor="#bfbfbf" style="font-size:12px">
                            <div align="center"><strong>REPORTE DE ACREDITACIONES
                                    {{ ' DEL ' . date('d-m-Y', strtotime($fecha_inicio)) . ' AL ' . date('d-m-Y', strtotime($fecha_fin)) }}</strong>
                            </div>
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
                            <td>
                                <table width="100%">
                                    <tr>
                                        @if (isset($usuario->nombres))
                                            <td bgcolor="#bfbfbf" style="font-size:12px">
                                                <div align="center">
                                                    <strong>{{ $usuario->nombres . ' ' . $usuario->apellidos }} </strong>
                                                </div>
                                            </td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <td height="55px;">
                                            <table width="100%" border="1" align="left" cellpadding="0" cellspacing="0">
                                                <tr>
                                                    <td bgcolor="#a9d08e" style="font-size:10px" width="10%">
                                                        <div align="center"><strong>#</strong></div>
                                                    </td>

                                                    <td bgcolor="#a9d08e" style="font-size:10px" width="29%">
                                                        <div align="center"><strong>NOMBRES Y APELLIDOS</strong></div>
                                                    </td>
                                                    <td bgcolor="#a9d08e" style="font-size:10px" width="15%">
                                                        <div align="center"><strong>LUGAR</strong></div>
                                                    </td>
                                                    <td bgcolor="#a9d08e" style="font-size:10px" width="17%">
                                                        <div align="center"><strong>FECHA</strong></div>
                                                    </td>
                                                    <td bgcolor="#a9d08e" style="font-size:10px" width="29%">
                                                        <div align="center"><strong>DESCRIPCI&Oacute;N</strong></div>
                                                    </td>
                                                    <td bgcolor="#a9d08e" style="font-size:10px" width="10%">
                                                        <div align="center"><strong>MONTO</strong></div>
                                                    </td>
                                                </tr>
                                                @foreach ($acreditaciones as $acreditacion)
                                                    <tr>
                                                        <td style="font-size:10px" width="6%">
                                                            <div align="left">
                                                                {{ $acreditacion['item'] }}
                                                            </div>
                                                        </td>
                                                        <td style="font-size:10px" width="29%">
                                                            <div align="left">
                                                                {{ $acreditacion['empleado']->nombres . ' ' . $acreditacion['empleado']->apellidos }}
                                                            </div>
                                                        </td>
                                                        <td style="font-size:10px" width="15%">
                                                            <div align="left">{{ $acreditacion['empleado']->canton->canton }}
                                                            </div>
                                                        </td>
                                                        <td style="font-size:10px" width="17%">
                                                            <div align="center">
                                                                {{ date('d-m-Y', strtotime($acreditacion['fecha'])) }}</div>
                                                        </td>
                                                        <td style="font-size:10px" width="29%">
                                                            <div align="left">{{ $acreditacion['descripcion_acreditacion'] }}
                                                            </div>
                                                        </td>
                                                        <td style="font-size:10px" width="10%">
                                                            <div align="right">
                                                                {{ number_format($acreditacion['monto'], 2, ',', '.') }}</div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                <tr>
                                                    <td>Total</td>
                                                    <td style="font-size:10px" width="10%" colspan="6">
                                                        <div align="right">
                                                            {{ number_format($total, 2, ',', '.') }}</div>
                                                    </td>
                                                </tr>

                                            </table>
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
