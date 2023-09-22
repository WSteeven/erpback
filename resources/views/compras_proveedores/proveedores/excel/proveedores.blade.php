<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de Proveedores</title>
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
    {{-- @php
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
    @endphp --}}
    <table
        style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px;margin-top: 20px;">
        <tr>
            <div class="header">
                <table
                    style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px;page-break-inside: avoid;">
                    <tr>
                        <td width="17%">
                            src="{{ 'data:image/png;base64,' . base64_encode(file_get_contents('img/logo.png')) }}"
                    width="90">
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
                            <div align="center"><strong>REPORTE DE PROVEEDORES
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
                                            RUC</td>
                                        <td
                                            style="text-align: center !important;
                                background-color: #DBDBDB;">
                                            RAZON SOCIAL</td>
                                        <td
                                            style="  text-align: center !important;
                                background-color: #DBDBDB;">
                                            CIUDAD</td>
                                        <td
                                            style="  text-align: center !important;
                                background-color: #DBDBDB;">
                                            DIRECCION</td>
                                        <td
                                            style="  text-align: center !important;
                                background-color: #DBDBDB;">
                                            CELULAR</td>
                                        <td style="background-color:#DBDBDB">ESTADO</td>
                                        <td style="background-color:#DBDBDB">CALIFICACION</td>
                                        <td style="background-color:#DBDBDB">CATEGORIAS</td>
                                        <td style="background-color:#DBDBDB">DEPT. CALIFICADORES</td>
                                    </tr>

                                    @foreach ($reporte as $rpt)
                                        <tr>
                                            <td>{{ $rpt['ruc'] }}</td>
                                            <td>{{ $rpt['razon_social'] }}</td>
                                            <td>{{ $rpt['ciudad'] }}</td>
                                            <td>{{ $rpt['direccion'] }}</td>
                                            <td>{{ $rpt['celular'] }}</td>
                                            <td>{{ $rpt['estado_calificado'] }}</td>
                                            <td>{{ $rpt['calificacion'] }}</td>
                                            <td>{{ $rpt['categorias'] }}</td>
                                            <td>{{ $rpt['departamentos'] }}</td>
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
