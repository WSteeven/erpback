<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de Pago de comisiones</title>
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
                            <div align="center">{{ $config['razon_social'] }}</div>
                        </td>
                    </tr>
                    <tr>
                        <td width="17%">
                            <div align="center"></div>
                        </td>
                        <td width="83%" colspan="7" style="font-size:12px;text-align: center">
                            <div align="center"><strong>REPORTE DE {{ $reporte['nombre'] }}
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
                                        <td style="font-weight: bold">FECHA INICIO </td>
                                        <td style="font-weight: bold"> FECHA FIN</td>
                                        <td style="font-weight: bold"> VENDEDOR</td>
                                        <td style="font-weight: bold"> CANT. VENTAS</td>
                                        <td style="font-weight: bold"> CHARGEBACK </td>
                                        <td style="font-weight: bold"> VALOR A PAGAR</td>
                                    </tr>

                                    @foreach ($reporte['listadoEmpleados'] as $key => $listado)
                                        <tr>
                                            <td>{{ $key }}</td>
                                            <td>{{ $listado['fecha_inicio'] }}</td>
                                            <td>{{ $listado['fecha_fin'] }}</td>
                                            <td>{{ $listado['vendedor_info'] }}</td>
                                            <td>{{ $listado['ventas'] }}</td>
                                            <td>{{ $listado['valor'] }}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="5">TOTAL A PAGAR</td>
                                        <td colspan="6">{{ $listado->sum('valor') }}</td>
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
