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
                                <div align="center"><strong>REPORTE SEMANAL DE GASTOS DEL
                                        {{ $fecha_inicio . ' AL ' . $fecha_fin }}</strong></div>
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
                                    @if (sizeof($datos_saldo_depositados_semana) > 0)
                                        @foreach ($datos_saldo_depositados_semana as $saldo_depositado_semana)
                                            <tr>
                                                <td style="font-size:10px">{{ $saldo_depositado_semana->fecha }}</td>
                                                <td style="font-size:10px">
                                                    {{ number_format($saldo_depositado_semana->saldo_depositado, 2, ',', '.') }}</td>
                                                <td style="font-size:10px">{{ $saldo_depositado_semana->descripcion }}</td>
                                                <td style="font-size:10px">{{ $saldo_depositado_semana->descripcion_saldo }}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="4" style="font-size:10px">No hay datos para mostrar</td>
                                        </tr>
                                    @endif

                                </table>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <table width="100%" border="1" cellspacing="0" bordercolor="#666666"
                    style="margin-top:' . $height . '%; ">
                    <tr>
                        <td>&nbsp;</td>
                        <td colspan="10" style="font-size:10px">
                            <div align="right"><strong>SALDO ANTERIOR:</strong></div>
                        </td>
                        <td style="font-size:10px">
                            <div align="center">' . number_format($sal_anterior, 2, ',', ' ') . '</div>
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td colspan="10" style="font-size:10px">
                            <div align="right"><strong>SALDO DEPOSITADO:&nbsp;</strong></div>
                        </td>
                        <td style="font-size:10px">
                            <div align="center">' . number_format($sal_dep_r, 2, ',', ' ') . '</div>
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td colspan="10" style="font-size:10px">
                            <div align="right"><strong>NUEVO SALDO:&nbsp;</strong></div>
                        </td>
                        <td style="font-size:10px">
                            <div align="center">' . number_format($nuevo_saldo, 2, ',', ' ') . '</div>
                        </td>
                    </tr>
                    <tr>
                        <td width="2%" bgcolor="#a9d08e">
                            <div align="center"><strong>N&deg;</strong></div>
                        </td>
                        <td width="5%" bgcolor="#a9d08e">
                            <div align="center"><strong>FECHA</strong></div>
                        </td>
                        <td width="8%" bgcolor="#a9d08e">
                            <div align="center"><strong>TAREA</strong></div>
                        </td>
                        <td width="10%" bgcolor="#a9d08e">
                            <div align="center"><strong># FACTURA</strong></div>
                        </td>
                        <td width="10%" bgcolor="#a9d08e">
                            <div align="center"><strong>RUC</strong></div>
                        </td>
                        <td width="10%" bgcolor="#a9d08e">
                            <div align="center"><strong>AUTORIZACION ESPECIAL</strong></div>
                        </td>
                        <td width="8%" bgcolor="#a9d08e">
                            <div align="center"><strong>DETALLE</strong></div>
                        </td>
                        <td width="8%" bgcolor="#a9d08e">
                            <div align="center"><strong>SUB DETALLE</strong></div>
                        </td>
                        <td width="24%" bgcolor="#a9d08e">
                            <div align="center"><strong>OBSERVACI&Oacute;N</strong></div>
                        </td>
                        <td width="3%" bgcolor="#a9d08e">
                            <div align="center"><strong>CANT.</strong></div>
                        </td>
                        <td width="7%" bgcolor="#a9d08e">
                            <div align="center"><strong>V. UNI.</strong></div>
                        </td>
                        <td width="5%" bgcolor="#a9d08e">
                            <div align="center"><strong>TOTAL</strong></div>
                        </td>
                    </tr>
                    <div class="footer">JP Construcred / Reporte Generado por el Usuario:
                        {{ $datos_usuario_logueado['nombres'] . ' ' . $datos_usuario_logueado['apellidos'] }}</div>
</body>

</html>
