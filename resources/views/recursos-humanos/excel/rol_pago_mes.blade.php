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
                            <div align="center"><strong>{{ $nombre }}
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
                                <table width="100%">

                                    <tr>
                                        <td height="55px;">
                                            <table width="100%" border="1" align="left" cellpadding="0"
                                                cellspacing="0" class="datos">
                                                <colgroup span="{{ $numcol_ingreso }}"></colgroup>
                                                <colgroup span="{{ $numcol_egreso }}"></colgroup>
                                                <tr class="encabezado-datos" style="text-align: center ">
                                                    <td rowspan="2"
                                                        style="text-align: center !important;
                                                background-color: #DBDBDB;">
                                                        ITEM</td>
                                                    <td
                                                        rowspan="2"style="text-align: center !important;
                                                background-color: #DBDBDB;">
                                                        EMPLEADO</td>
                                                    <td
                                                        rowspan="2"style="  text-align: center !important;
                                                background-color: #DBDBDB;">
                                                        CEDULA</td>
                                                    <td
                                                        rowspan="2"style="  text-align: center !important;
                                                background-color: #DBDBDB;">
                                                        CARGO</td>
                                                    <td
                                                        rowspan="2"style="  text-align: center !important;
                                                background-color: #DBDBDB;">
                                                        CIUDAD</td>
                                                    <td
                                                        rowspan="2"style="  text-align: center !important;
                                                background-color: #DBDBDB;">
                                                        SUELDO</td>
                                                    <td rowspan="2" style="background-color: #F8CBAD">DIAS TRABAJADOS
                                                    </td>
                                                    <th colspan="{{ $numcol_ingreso }}" scope="colgroup"
                                                        class="encabezado-ingresos"
                                                        style="text-align: center !important;
                                                background-color: #FFF2CC;">
                                                        INGRESOS</th>
                                                    <td rowspan="2" style="background-color: #FFE699">TOTAL INGRESOS
                                                    </td>
                                                    <th colspan="{{ $numcol_egreso }}" scope="colgroup"
                                                        class="encabezado-egresos"
                                                        style="text-align: center !important;
                                                background-color: #BDD7EE;">
                                                        EGRESOS</th>
                                                    <td rowspan="2" style="background-color: #CCCCFF">TOTAL EGRESOS
                                                    </td>
                                                    <td rowspan="2" style="background-color:#A9D08E">NETO A RECIBIR
                                                    </td>

                                                </tr>
                                                <tr class="encabezado-datos">
                                                    <td
                                                        scope="col"class="encabezado-ingresos"style="text-align: center !important;
                                                background-color: #FFF2CC;">
                                                        SUELDO GANADO</td>
                                                    <th
                                                        scope="col"class="encabezado-ingresos"style="text-align: center !important;
                                                background-color: #FFF2CC;">
                                                        DECIMO XII</th>
                                                    <th
                                                        scope="col"class="encabezado-ingresos"style="text-align: center !important;
                                                background-color: #FFF2CC;">
                                                        DECIMO XIV</th>
                                                    <th
                                                        scope="col"class="encabezado-ingresos"style="text-align: center !important;
                                                background-color:#FFF2CC;">
                                                        FONDOS DE RESERVA</th>
                                                    @if ($tiene_bonificacion)
                                                        <th scope="col"
                                                            class="encabezado-ingresos"style="text-align: center !important;
                                                background-color: #FFF2CC;">
                                                            BONIFICACION</th>
                                                    @endif
                                                    @if ($tiene_bono_recurente)
                                                        <th scope="col"
                                                            class="encabezado-ingresos"style="text-align: center !important;
                                                background-color:#FFF2CC;">
                                                            BONO RECURENTE</th>
                                                    @endif
                                                    @foreach ($columnas_ingresos as $ingreso)
                                                        <th
                                                            scope="col"class="encabezado-ingresos"style="text-align: center !important;
                                                background-color:#FFF2CC;">
                                                            {{ strtoupper($ingreso) }}</th>
                                                    @endforeach
                                                    <th
                                                        scope="col"class="encabezado-ingresos"style="text-align: center !important;
                                               background-color: #BDD7EE;">
                                                        IESS (9.45%)</th>
                                                    <th scope="col"class="encabezado-egresos"
                                                        style="text-align: center !important;
                                                background-color: #BDD7EE;">
                                                        PRESTAMO QUIROGRAFARIO</th>

                                                    <th scope="col"class="encabezado-egresos"
                                                        style="text-align: center !important;
                                                background-color: #BDD7EE;">
                                                        PRESTAMO HIPOTECARIO</th>
                                                    <th scope="col"class="encabezado-egresos"
                                                        style="text-align: center !important;
                                                background-color: #BDD7EE;">
                                                        PRESTAMO</th>
                                                    <th scope="col"class="encabezado-egresos"
                                                        style="text-align: center !important;
                                                background-color: #BDD7EE;">
                                                        EXT CONYUGAL</th>
                                                    <th scope="col"class="encabezado-egresos"
                                                        style="text-align: center !important;
                                                background-color: #BDD7EE;">
                                                        ANTICIPO</th>

                                                    @if ($tiene_supa)
                                                        <th scope="col"class="encabezado-egresos"
                                                            style="text-align: center !important;
                                                    background-color: #BDD7EE;">
                                                            SUPA</th>
                                                    @endif
                                                    @foreach ($columnas_egresos as $egreso)
                                                        <th scope="col"class="encabezado-egresos"
                                                            style="text-align: center !important;
                                                    background-color: #BDD7EE;">
                                                            {{ strtoupper($egreso) }}</th>
                                                    @endforeach
                                                </tr>
                                                @foreach ($roles_pago as $rol_pago)
                                                    @php
                                                        $sumColumns['prestamo_quirorafario'] += $rol_pago['prestamo_quirorafario'];
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $rol_pago['item'] }}</td>
                                                        <td>{{ $rol_pago['empleado_info'] }}</td>
                                                        <td>{{ $rol_pago['cedula'] }}</td>
                                                        <td>{{ $rol_pago['cargo'] }}</td>
                                                        <td>{{ $rol_pago['ciudad'] }}</td>
                                                        <td>{{ number_format($rol_pago['salario'], 2, ',', '.') }}</td>
                                                        <td>{{ $rol_pago['dias_laborados'] }}</td>
                                                        <td>{{ number_format($rol_pago['sueldo'], 2, ',', '.') }}</td>
                                                        <td>{{ number_format($rol_pago['decimo_tercero'], 2, ',', '.') }}
                                                        </td>
                                                        <td>{{ number_format($rol_pago['decimo_cuarto'], 2, ',', '.') }}
                                                        </td>
                                                        <td> {{ number_format($rol_pago['fondos_reserva'], 2, ',', '.') }}
                                                        </td>
                                                        @if ($tiene_bonificacion)
                                                            <td>{{ number_format($rol_pago['bonificacion'], 2, ',', '.') }}
                                                            </td>
                                                        @endif
                                                        @if ($tiene_bono_recurente)
                                                            <td>{{ number_format($rol_pago['bono_recurente'], 2, ',', '.') }}
                                                            </td>
                                                        @endif
                                                        @if ($cantidad_columna_ingresos > 0)
                                                            @if ($rol_pago['ingresos_cantidad_columna'] > 0)
                                                                @foreach ($colum_ingreso_value as $ingreso)
                                                                    @foreach ($ingreso as $ingreso_value)
                                                                        @if ($ingreso_value['id'] === $rol_pago['id'])
                                                                            <td>{{ number_format($ingreso_value['valor'], 2, ',', '.') }}
                                                                            </td>
                                                                        @endif
                                                                    @endforeach
                                                                @endforeach
                                                            @else
                                                                @for ($i = 0; $i <= $cantidad_columna_ingresos - 1; $i++)
                                                                    <td>0</td>
                                                                @endfor
                                                            @endif
                                                        @endif
                                                        <td>{{ number_format($rol_pago['total_ingreso'], 2, ',', '.') }}
                                                        </td>
                                                        <td> {{ number_format($rol_pago['iess'], 2, ',', '.') }}</td>
                                                        <td>{{ number_format($rol_pago['prestamo_quirorafario'], 2, ',', '.') }}
                                                        </td>
                                                        <td>{{ number_format($rol_pago['prestamo_hipotecario'], 2, ',', '.') }}
                                                        </td>
                                                        <td>{{ number_format($rol_pago['prestamo_empresarial'], 2, ',', '.') }}
                                                        </td>
                                                        <td>{{ number_format($rol_pago['extension_conyugal'], 2, ',', '.') }}
                                                        </td>
                                                        <td>{{ number_format($rol_pago['anticipo'], 2, ',', '.') }}
                                                        </td>

                                                        @if ($tiene_supa)
                                                            <td>{{ number_format($rol_pago['supa'], 2, ',', '.') }}
                                                            </td>
                                                        @endif
                                                        @if ($cantidad_columna_egresos > 0)
                                                            @if ($rol_pago['egresos_cantidad_columna'] > 0)
                                                                @foreach ($colum_egreso_value as $clave => $value)
                                                                    @foreach ($value as $subvalue)
                                                                        @if ($subvalue['id'] == $rol_pago['id'])
                                                                            @php
                                                                                array_push($carry_egreso, $clave);
                                                                                $size_array = count($columnas_egresos) - 1;
                                                                                $index = array_search($carry_egreso[$index_egreso], $columnas_egresos);
                                                                            @endphp
                                                                            @if ($index > $size_array)
                                                                                @for ($i = 0; $i < $index - 1; $i++)
                                                                                    <td>0</td>
                                                                                @endfor
                                                                            @else
                                                                                @for ($i = 0; $i < $index; $i++)
                                                                                    <td>0</td>
                                                                                @endfor
                                                                            @endif

                                                                            <td>{{ number_format($subvalue['valor'], 2, ',', '.') }}
                                                                            </td>

                                                                            @for ($i = 0; $i < $size_array - $index; $i++)
                                                                                <td>0</td>
                                                                            @endfor
                                                                            @php
                                                                                $index_egreso++;
                                                                            @endphp
                                                                        @endif
                                                                    @endforeach
                                                                @endforeach
                                                            @else
                                                                @for ($i = 0; $i < $cantidad_columna_egresos; $i++)
                                                                    <td>0</td>
                                                                @endfor
                                                            @endif
                                                        @endif
                                                        <td>{{ number_format($rol_pago['total_egreso'], 2, ',', '.') }}
                                                        </td>
                                                        <td>{{ $rol_pago['total'] }}</td>
                                                    </tr>
                                                @endforeach
                                                <tr style="background-color: #FFE699">
                                                    <td colspan="5" style="text-align: center">
                                                        <strong>TOTALES&nbsp;</strong>
                                                    </td>
                                                    <td> {{ number_format($sumatoria['salario'], 2, ',', '.') }}</td>
                                                    <td>&nbsp;</td>
                                                    <td> {{ number_format($sumatoria['sueldo'], 2, ',', '.') }}</td>
                                                    <td>{{ number_format($sumatoria['decimo_tercero'], 2, ',', '.') }}
                                                    </td>
                                                    <td>{{ number_format($sumatoria['decimo_cuarto'], 2, ',', '.') }}
                                                    </td>
                                                    <td>{{ number_format($sumatoria['fondos_reserva'], 2, ',', '.') }}
                                                    </td>
                                                    @foreach ($sumatoria_ingresos as $sumatoria_ingreso)
                                                        <td>{{ number_format($sumatoria_ingreso, 2, ',', '.') }}</td>
                                                    @endforeach
                                                    <td>{{ number_format($sumatoria['total_ingreso'], 2, ',', '.') }}
                                                    </td>
                                                    <td>{{ number_format($sumatoria['iess'], 2, ',', '.') }}</td>
                                                    <td>{{ number_format($sumColumns['prestamo_quirorafario'], 2, ',', '.') }}
                                                    </td>
                                                    <td>{{ number_format($sumatoria['prestamo_hipotecario'], 2, ',', '.') }}
                                                    </td>
                                                    <td>{{ number_format($sumatoria['prestamo_empresarial'], 2, ',', '.') }}
                                                    </td>
                                                    @if ($tiene_supa)
                                                        <td>{{ number_format($sumatoria['supa'], 2, ',', '.') }}</td>
                                                    @endif
                                                    <td>{{ number_format($sumatoria['extension_conyugal'], 2, ',', '.') }}
                                                    </td>
                                                    <td>{{ number_format($sumatoria['anticipo'], 2, ',', '.') }}</td>
                                                    @foreach ($sumatoria_egresos as $sumatoria_egreso)
                                                        <td>{{ number_format($sumatoria_egreso, 2, ',', '.') }}</td>
                                                    @endforeach
                                                    <td>{{ number_format($sumatoria['total_egreso'], 2, ',', '.') }}
                                                    </td>
                                                    <td>{{ number_format($sumatoria['total'], 2, ',', '.') }}</td>
                                                </tr>
                                            </table>

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
