<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de Pago</title>
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
                        <img src="{{ public_path($config['logo_claro']) }}" width="90">
                    </td>
                    <td width="83%" colspan="7" style="font-size:16px; font-weight:bold; text-align: center">
                        <div align="center">JPCONSTRUCRED C.LTDA</div>
                    </td>
                </tr>
                <tr>
                    <td width="17%">
                        <div align="center"></div>
                    </td>
                    <td width="83%" colspan="7" style="font-size:12px;text-align: center">
                        <div align="center"><strong>REPORTE DE VALORES A PAGAR
                            </strong>
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
                        <td></td>
                    </tr>
                    <tr>
                        <td>
                            <table width="100%" style="border: 3px solid #000000;">
                                <tr>
                                    <td style="font-weight: bold" >MES </td>
                                    <td style="font-weight: bold"> PAGO DE COMISIONES</td>
                                    <td style="font-weight: bold"> BMC</td>
                                    <td style="font-weight: bold"> BTC</td>
                                    <td style="font-weight: bold"> CHARGEBACK </td>
                                    <td style="font-weight: bold"> INGRESOS</td>
                                    <td style="font-weight: bold"> EGRESOS</td>
                                    <td style="font-weight: bold">VALOR A PAGAR </td>
                                  </tr>

                                  @foreach($reportes as $key => $reporte)
                                  <tr >
                                      <td >{{ $key }}</td>
                                      <td >{{ $reporte['total_comisiones'] }}</td>
                                      <td >{{ $reporte['bmc'] }}</td>
                                      <td >{{ $reporte['btc'] }}</td>
                                      <td >{{ $reporte['chargebacks'] }}</td>
                                      <td >{{ $reporte['ingresos'] }}</td>
                                      <td >{{ $reporte['egresos'] }}</td>
                                      <td >{{ $reporte['total_a_pagar'] }} </td>
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
