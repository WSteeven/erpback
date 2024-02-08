<!DOCTYPE html>
<html lang="en">
@php
    $suma = 0;
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de Ventas realizadas</title>
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
                            <div align="center"><strong>REPORTE DE VENTAS REALIZADAS EN EL PERIODO</strong></div>
                        </td>
                    </tr>
                    <tr></tr>
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
                                        <td style="font-weight: bold">N <span
                                                style="color:#000000; font-family:'Calibri'; font-size:11pt">°</span>
                                        </td>
                                        <td style="font-weight: bold"> CIUDAD</td>
                                        <td style="font-weight: bold"> VENDEDOR</td>
                                        <td style="font-weight: bold"> TIPO VENDEDOR</td>
                                        <td style="font-weight: bold"> CODIGO DE ORDEN </td>
                                        <td style="font-weight: bold"> NOMBRE CLIENTE</td>
                                        <td style="font-weight: bold"> CEDULA</td>
                                        <td style="font-weight: bold"> VENTA</td>
                                        <td style="font-weight: bold"> FECHA DE INGRESO</td>
                                        <td style="font-weight: bold"> FECHA DE ACTIVACION</td>
                                        <td style="font-weight: bold"> PLAN DE INTERNET</td>
                                        <td style="font-weight: bold">FORMA PAGO</td>
                                        <td style="font-weight: bold">PRECIO </td>
                                        <td style="font-weight: bold">ORDEN INTERNA </td>
                                        <td style="font-weight: bold">COMISIONA</td>
                                    </tr>
                                    @foreach ($reporte as $key => $reporte)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $reporte['ciudad'] }}</td>
                                            <td>{{ $reporte['vendedor'] }}</td>
                                            <td>{{ $reporte['tipo_vendedor'] }}</td>
                                            <td>{{ $reporte['codigo_orden'] }}</td>
                                            <td>{{ $reporte['cliente'] }}</td>
                                            <td>{{ $reporte['identificacion_cliente'] }}</td>
                                            <td>{{ $reporte['venta'] }}</td>
                                            <td>{{ $reporte['fecha_ingreso'] }}</td>
                                            <td>{{ $reporte['fecha_activacion'] }}</td>
                                            <td>{{ $reporte['plan'] }} </td>
                                            <td>{{ $reporte['forma_pago'] }} </td>
                                            <td>&nbsp;$ {{ $reporte['precio'] }} </td>
                                            <td>{{ $reporte['orden_interna'] }}</td>
                                            <td>{{ $reporte['comisiona'] ? 'SI' : 'NO' }}</td>
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
