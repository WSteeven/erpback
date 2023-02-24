<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <table
        style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px;page-break-inside: avoid;">

        <tr height="26">
            <td height="26" align="left" valign="top">
                <table width="100%" border="0">
                    <tr>
                        <td width="17%">
                            <div align="center"><img width="100" height="64" src="../img/logo/logo_reporte.svg" />
                            </div>
                        </td>
                        <td width="83%" style="font-size:16px; font-weight:bold">
                            <div align="center">JEAN PATRICIO PAZMI&Ntilde;O BARROS<br />
                                RUC:0702875618001</div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr height="29">
            <td height="15">
                <div align="center">
                    <table width="100%">
                        <tr>
                            <td bgcolor="#bfbfbf" style="font-size:12px">
                                <div align="center"><strong>REPORTE SALDO ACTUAL </strong></div>
                            </td>
                        </tr>
                        <tr>
                            <td height="55px;">
                                <table width="100%" border="1" align="left" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td bgcolor="#a9d08e" style="font-size:10px">
                                            <div align="center"><strong>Item</strong></div>
                                        </td>
                                        <td bgcolor="#a9d08e" style="font-size:10px">
                                            <div align="center"><strong>Nombres y Apellidos</strong></div>
                                        </td>
                                        <td bgcolor="#a9d08e" style="font-size:10px">
                                            <div align="center"><strong>Usuario</strong></div>
                                        </td>
                                        <td bgcolor="#a9d08e" style="font-size:10px">
                                            <div align="center"><strong>Monto</strong></div>
                                        </td>
                                    </tr>
                                    @foreach ($saldos as $saldo)
                                        <tr>
                                            <td style="font-size:10px">
                                                <div align="left" style="margin-left:20px;"></div></td>
                                            <td style="font-size:10px">
                                                <div align="left" style="margin-left:20px;">
                                                   {{-- $saldo->usuario_info->nombres.''.$saldo->usuario_info->apellidos --}}
                                                </div>
                                            </td>
                                            <td style="font-size:10px">
                                                <div align="left" style="margin-left:20px;">
                                                   {{ $saldo->usuario_info->name}}
                                                </div>
                                            </td>
                                            <td style="font-size:10px">
                                                <div align="right" style="margin-right:20px;">
                                                    {{ number_format($saldo->saldo_actual, 2, ',', '.') }}
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach

                                </table>
                            </td>
                        </tr>


                    </table>
            </td>
        </tr>

    </table>
</body>

</html>
