<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de Pedidos</title>
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
                            <div align="center">JPCONSTRUCRED C.LTDA</div>
                        </td>
                    </tr>
                    <tr>
                        <td width="17%">
                            <div align="center"></div>
                        </td>
                        <td width="83%" style="font-size:12px">
                            <div align="center"><strong>REPORTE DE PEDIDOS
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
                                        <td style="background-color: #DBDBDB;">ID PEDIDO</td>
                                        <td style="background-color: #DBDBDB;">FECHA DE SOLICITUD</td>
                                        <td style="background-color: #DBDBDB;">JUSTIFICACION</td>
                                        <td style="background-color: #DBDBDB;">DESCRIPCION</td>
                                        <td style="background-color: #DBDBDB;">SERIAL</td>
                                        <td style="background-color: #DBDBDB;">CATEGORIA</td>
                                        <td style="background-color: #DBDBDB;">CANTIDAD</td>
                                        <td style="background-color: #DBDBDB;">DESPACHADO</td>
                                        <td style="background-color: #DBDBDB;">SOLICITANTE</td>
                                        <td style="background-color:#DBDBDB">AUTORIZACION</td>
                                        <td style="background-color: #DBDBDB;">AUTORIZADOR</td>
                                        <td style="background-color:#DBDBDB">ESTADO</td>
                                        <td style="background-color:#DBDBDB">RESPONSABLE</td>
                                    </tr>

                                    @foreach ($reporte as $rpt)
                                        <tr>
                                            <td>{{ $rpt['pedido_id'] }}</td>
                                            <td>{{ $rpt['created_at'] }}</td>
                                            <td>{{ $rpt['justificacion'] }}</td>
                                            <td>{{ $rpt['descripcion'] }}</td>
                                            <td>{{ $rpt['serial'] }}</td>
                                            <td>{{ $rpt['categoria'] }}</td>
                                            <td>{{ $rpt['cantidad'] }}</td>
                                            <td>{{ $rpt['despachado'] }}</td>
                                            <td>{{ $rpt['solicitante'] }}</td>
                                            <td>{{ $rpt['autorizacion'] }}</td>
                                            <td>{{ $rpt['autorizador'] }}</td>
                                            <td>{{ $rpt['estado'] }}</td>
                                            <td>{{ $rpt['responsable'] }}</td>
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
