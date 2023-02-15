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

    <div class="header">
        <table
            style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px;page-break-inside: avoid;">
            <tr>
                <td width="17%">
                    <div align="center"></div>
                </td>
                <td width="83%" style="font-size:16px; font-weight:bold">
                    <div align="center">JEAN PATRICIO PAZMI&Ntilde;O BARROS</div>
                    <div align="center">RUC:0702875618001</div>
                </td>
            </tr>
        </table>
    </div>

    <table
        style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px;margin-top: 20px;">

        <tr>
            <td>
                <div align="center">
                    <table width="100%">
                        <tr>
                            <td bgcolor="#bfbfbf" style="font-size:12px">
                                <div align="center"><strong>REPORTE SEMANAL DE GASTOS DEL ' . $fecha_titulo_ini[2] . '-'
                                        . $fecha_titulo_ini[1] . '-' . $fecha_titulo_ini[0] . ' AL ' .
                                        $fecha_titulo_fin[2] . '-' . $fecha_titulo_fin[1] . '-' . $fecha_titulo_fin[0] .
                                        ' </strong></div>
                            </td>
                        </tr>
                        <tr>
                            <td bgcolor="#bfbfbf" style="font-size:14px">
                                <div align="center"><strong>' . $datos_usuario[0]->usuario . ' [' . $grupo_usuario .
                                        ']</strong></div>
                            </td>
                        </tr>
                        <tr>
                            <td>

                                <table width="100%" border="1" align="left" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td colspan="4" style="font-size:10px" bgcolor="#a9d08e"><strong>SALDOS
                                                DEPOSITADOS</strong></td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:10px"><strong>Fecha</strong></td>
                                        <td style="font-size:10px"><strong>Monto</strong></td>
                                        <td style="font-size:10px"><strong>Tipo Saldo</strong></td>
                                        <td style="font-size:10px"><strong>Descripción</strong></td>
                                    </tr>
                                    @if (sizeof($viaticos) > 0)
                                        @foreach ($viaticos as $viatico)
                                            <tr>
                                                <td style="font-size:10px">{{ $viatico->fecha }}</td>
                                                <td style="font-size:10px">
                                                    {{ number_format($viatico->saldo_depositado, 2, ',', '.') }}</td>
                                                <td style="font-size:10px">{{ $viatico->descripcion }}</td>
                                                <td style="font-size:10px">{{ $viatico->descripcion_saldo }}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="4" style="font-size:10px">No hay datos para mostrar</td>
                                        </tr>
                                    @endif

                                </table>

                                <div class="footer">JP Construcred / Reposte Generado por el Usuario:
                                    {{ $datos_usuario_logueado->name . ' ' . $datos_usuario_logueado->name }}</div>
</body>

</html>
