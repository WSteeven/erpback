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
                        <td width="100%">
                            <div align="center"></div>
                        </td>
                        <td width="100%" style="font-size:16px; font-weight:bold">
                            <div align="center">JEAN PATRICIO PAZMI&Ntilde;O BARROS</div>
                        </td>
                    </tr>
                    <tr>
                        <td width="100%">
                            <div align="center"></div>
                        </td>
                        <td width="100%" style="font-size:16px; font-weight:bold">
                            <div align="center">RUC:0702875618001</div>
                        </td>
                    </tr>
                    <tr>
                        <td width="100%"bgcolor="#bfbfbf" style="font-size:12px">
                            <div align="center">REPORTE DE SALDO ACTUAL</div>
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
                                <table width="100%" border="1" align="left" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td bgcolor="#a9d08e" style="font-size:10px"  width="100%">
                                            <div align="center"><strong>Item</strong></div>
                                        </td>
                                        <td bgcolor="#a9d08e" style="font-size:10px" width="100%">
                                            <div align="center"><strong>Nombres y Apellidos</strong></div>
                                        </td>
                                         <td bgcolor="#a9d08e" style="font-size:10px">
                                            <div align="center" width="100%"><strong>Cargo</strong></div>
                                        </td>
                                        <td bgcolor="#a9d08e" style="font-size:10px">
                                            <div align="center"><strong>Localidad</strong></div>
                                        </td>
                                        <td bgcolor="#a9d08e" style="font-size:10px" width="100%">
                                            <div align="center"><strong>Usuario</strong></div>
                                        </td>
                                        <td bgcolor="#a9d08e" style="font-size:10px" width="100%">
                                            <div align="center"><strong>Monto</strong></div>
                                        </td>
                                    </tr>
                                    @foreach ($saldos as $saldo)
                                    <tr>
                                        <td style="font-size:10px">
                                            <div align="left" style="margin-left:20px;"> {{$saldo['item']}}</div>
                                        </td>
                                        <td style="font-size:10px">
                                            <div align="left" style="margin-left:20px;">
                                                {{ $saldo['empleado']->nombres.' '.$saldo['empleado']->apellidos }}
                                            </div>
                                        </td>
                                          <td style="font-size:10px">
                                            <div align="left" style="margin-left:20px;">
                                               {{$saldo['cargo']}}
                                            </div>
                                        </td>
                                        <td style="font-size:10px">
                                            <div align="left" style="margin-left:20px;">
                                            {{$saldo['localidad']}}
                                            </div>
                                        </td>
                                        <td style="font-size:10px">
                                            <div align="left" style="margin-left:20px;">
                                                {{ $saldo['empleado_info']->name}}
                                            </div>
                                        </td>
                                        <td style="font-size:10px">
                                            <div align="right" style="margin-right:20px;">
                                                {{ number_format($saldo['saldo_actual'], 2, ',', '.') }}
                                            </div>
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
