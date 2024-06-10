<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Datos Completos de Proveedores</title>
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
                            <div align="center"><strong>DATOS DE PROVEEDORES
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
                                            TIPO</td>
                                        <td
                                            style="text-align: center !important;
                                background-color: #DBDBDB;">
                                            RUC</td>
                                        <td
                                            style="text-align: center !important;
                                background-color: #DBDBDB;">
                                            RAZON SOCIAL</td>
                                        <td
                                            style="  text-align: center !important;
                                background-color: #DBDBDB;">
                                            NOMBRE COMERCIAL</td>
                                        <td
                                            style="  text-align: center !important;
                                background-color: #DBDBDB;">
                                            TELEFONOS</td>
                                        <td
                                            style="  text-align: center !important;
                                background-color: #DBDBDB;">
                                            DIRECCION</td>
                                        <td style="background-color:#DBDBDB">EMAIL</td>
                                        <td style="background-color:#DBDBDB">ES_CLIENTE</td>
                                        <td style="background-color:#DBDBDB">CUENTAS POR COBRAR</td>
                                        <td style="background-color:#DBDBDB">ES_PROVEEDOR</td>
                                        <td style="background-color:#DBDBDB">CUENTAS POR PAGAR</td>
                                        <td style="background-color:#DBDBDB">CANTON</td>
                                        <td style="background-color:#DBDBDB">ES_CONTRIBUYENTE_ESPECIAL</td>
                                    </tr>

                                    @foreach ($reporte as $rpt)
                                        <tr>
                                            <td>{{ $rpt['tipo'] }}</td>
                                            <td>{{ $rpt['ruc'] }}</td>
                                            <td>{{ $rpt['razon_social'] }}</td>
                                            <td>{{ $rpt['nombre_comercial'] }}</td>
                                            <td>{{ $rpt['telefonos'] }}</td>
                                            <td>{{ $rpt['direccion'] }}</td>
                                            <td>{{ $rpt['email'] }}</td>
                                            <td>{{ $rpt['es_cliente'] }}</td>
                                            <td>CLIENTE COMERCIALES</td>
                                            <td>{{ $rpt['es_proveedor'] }}</td>
                                            <td>PROVEEDORES</td>
                                            <td>{{ $rpt['canton'] }}</td>
                                            <td>{{ $rpt['contribuyente_especial'] }}</td>
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
