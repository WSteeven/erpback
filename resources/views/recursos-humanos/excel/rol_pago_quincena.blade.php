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
    @php
        $numcol_ingreso = $cantidad_columna_ingresos + 4;
        $numcol_egreso = $cantidad_columna_egresos + 6;
        $tiene_supa = $sumatoria['supa'] > 0;
        $tiene_bonificacion = $sumatoria['bonificacion'] > 0;
        $tiene_bono_recurente = $sumatoria['bono_recurente'] > 0;
        $carry_ingreso = [];
        $index_ingreso = 0;
        $carry_egreso = [];
        $index_egreso = 0;
        if ($tiene_bono_recurente) {
            $numcol_ingreso = $cantidad_columna_ingresos + 4;
        }
        if ($tiene_bonificacion) {
            $numcol_ingreso = $cantidad_columna_ingresos + 4;
        }
        if ($tiene_bonificacion && $tiene_bono_recurente) {
            $numcol_ingreso = $cantidad_columna_ingresos + 5;
        }

        if ($tiene_supa) {
            $numcol_egreso = $cantidad_columna_egresos + 7;
        }
        $sumColumns = [
            'salario' => 0,
            'sueldo' => 0,
            'decimo_tercero' => 0,
            'decimo_cuarto' => 0,
            'fondos_reserva' => 0,
            'iess' => 0,
            'anticipo' => 0,
            'bonificacion' => 0,
            'bono_recurente' => 0,
            'total_ingreso' => 0,
            'prestamo_quirorafario' => 0,
            'prestamo_hipotecario' => 0,
            'extension_conyugal' => 0,
            'prestamo_empresarial' => 0,
            'supa' => 0,
            'total_egreso' => 0,
            'total' => 0,
        ];
    @endphp
    <table
        style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px;margin-top: 20px;">
        <tr>
            <div class="header">
                <table
                    style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px;page-break-inside: avoid;">
                    <tr>
                        <td width="17%">
                            <div align="center"></div>
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
                            <div align="center"><strong>ROL DE PAGOS {{ $periodo }}
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
                                            ITEM</td>
                                        <td
                                            style="text-align: center !important;
                                background-color: #DBDBDB;">
                                            EMPLEADO</td>
                                        <td
                                            style="  text-align: center !important;
                                background-color: #DBDBDB;">
                                            CEDULA</td>
                                        <td
                                            style="  text-align: center !important;
                                background-color: #DBDBDB;">
                                            CARGO</td>
                                        <td
                                            style="  text-align: center !important;
                                background-color: #DBDBDB;">
                                            CIUDAD</td>
                                        <td style="background-color:#A9D08E">NETO A RECIBIR
                                        </td>
                                    </tr>

                                    @foreach ($roles_pago as $rol_pago)
                                        <tr>
                                            <td>{{ $rol_pago['item'] }}</td>
                                            <td>{{ $rol_pago['empleado_info'] }}</td>
                                            <td>{{ $rol_pago['cedula'] }}</td>
                                            <td>{{ $rol_pago['cargo'] }}</td>
                                            <td>{{ $rol_pago['ciudad'] }}</td>
                                            <td>{{ $rol_pago['total'] }}</td>
                                        </tr>
                                    @endforeach
                                    <tr style="background-color: #FFE699">
                                        <td colspan="5" style="text-align: center">
                                            <strong>TOTALES&nbsp;</strong>
                                        </td>
                                        <td>{{ number_format($sumatoria['total'], 2, ',', '.') }}</td>
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
