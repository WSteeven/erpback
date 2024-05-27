<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Prefacturas</title>
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
                            <img src="{{ public_path($configuracion['logo_claro']) }}" width="90">
                        </td>
                        <td width="83%" style="font-size:16px; font-weight:bold">
                            <div align="center">{{ $configuracion->razon_social }}</div>
                        </td>
                    </tr>
                    <tr>
                        <td width="17%">
                            <div align="center"></div>
                        </td>
                        <td width="83%" style="font-size:12px">
                            <div align="center"><strong>REPORTE DE PREFACTURAS
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
                                <table width="100%" border="1" align="left" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td
                                            style="text-align: center !important;
                                background-color: #DBDBDB;">
                                            CODIGO</td>
                                        <td
                                            style="text-align: center !important;
                                background-color: #DBDBDB;">
                                            SOLICITANTE</td>
                                        <td style="background-color:#DBDBDB">CLIENTE</td>
                                        <td style="background-color:#DBDBDB">DESCRIPCION</td>
                                        <td style="background-color:#DBDBDB">ESTADO</td>
                                        <td style="background-color:#DBDBDB">FECHA</td>
                                        <td style="background-color:#DBDBDB">ELEMENTO</td>
                                        <td style="background-color:#DBDBDB">SUBTOTAL</td>
                                        <td style="background-color:#DBDBDB">IVA</td>
                                        <td style="background-color:#DBDBDB">DESCUENTO</td>
                                        <td style="background-color:#DBDBDB">TOTAL</td>
                                    </tr>

                                    @foreach ($reporte as $rpt)
                                        @foreach ($rpt->listadoProductos($rpt['id']) as $item)
                                            <tr>
                                                <td>{{ $rpt['codigo'] }} </td>
                                                <td>{{ $rpt['solicitante']['nombres'] }}
                                                    {{ $rpt['solicitante']['apellidos'] }}</td>
                                                <td>{{ $rpt['cliente']['empresa']['razon_social'] ?? '' }}</td>
                                                <td>{{ $rpt['descripcion'] }}</td>
                                                <td>{{ $rpt['estado']['nombre'] ?? '' }}</td>
                                                <td>{{ $rpt['created_at'] }}</td>
                                                <td>{{ strtoupper($item['descripcion']) }}</td>
                                                <td>{{ round($rpt->detalles()->sum('subtotal'), 2) }}</td>
                                                <td>{{ round($rpt->detalles()->sum('iva'), 2) }}</td>
                                                <td>{{ round($rpt->detalles()->sum('descuento'), 2) }}</td>
                                                <td>{{ round($rpt->detalles()->sum('total'), 2) }}</td>
                                            </tr>
                                        @endforeach
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
