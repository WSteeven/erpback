<!DOCTYPE html>
<html lang="en">
@php
    $suma = 0;
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de Pago de comisiones</title>
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
                            <img src="{{ public_path($config['logo_claro']) }}" width="90">
                        </td>
                        <td width="83%" colspan="5" style="font-size:16px; font-weight:bold; text-align: center">
                            <div align="center">{{ $config['razon_social'] }}</div>
                        </td>
                    </tr>
                    <tr>
                        <td width="17%">
                            <div align="center"></div>
                        </td>
                        <td width="83%" colspan="5" style="font-size:12px;text-align: center">
                            <div align="center"><strong>REPORTE DE {{ $reporte['nombre'] }}</strong></div>
                        </td>
                    </tr>
                    <tr></tr>
                    <tr>
                        <td colspan="5" style="text-align:right"><strong> ESTADO DE PAGO:</strong></td>
                        <td>{{ $reporte['estado'] }} </td>
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
                                            <td>{{ $listado['fecha_inicio'] }}</td>
                                            <td>{{ $listado['fecha_fin'] }}</td>
                                            <td>{{ $listado['vendedor_info'] }}</td>
                                            <td>{{ $listado['ventas'] }}</td>
                                            <td>{{ $listado['chargeback'] }}</td>
                                            <td>{{ $listado['valor'] }}</td>
                                        </tr>
                                        @php
                                            $suma += $listado['valor'];
                                        @endphp
                                    @endforeach
                                    <tr>
                                        <td style="font-weight: bold; text-align: center;" colspan="5">TOTAL A PAGAR
                                        </td>
                                        <td style="font-weight: bold"> {{ $suma }}
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
