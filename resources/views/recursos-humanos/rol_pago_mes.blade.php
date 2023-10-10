<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rol de pagos</title>
    <style>
        @page {
            margin: 2px 15px 5px 15px;
        }

        body {
            /* background-image: url({{ 'data:image/png;base64,' . base64_encode(file_get_contents('img/logoBN10.png')) }}); */
            background-image: url({{ 'data:image/png;base64,' . base64_encode(file_get_contents('img/logoBN10.png')) }});
            background-repeat: no-repeat;
            background-position: center;
        }

        .contenido {
            position: relative;
            top: 80px;
            left: 0cm;
            right: 0cm;
            margin-bottom: 4.3cm;
            font-size: 20px;
        }

        /** Definir las reglas del encabezado **/
        header {
            position: fixed;
            top: 0cm;
            left: 0cm;
            right: 0cm;
            height: 2cm;

            /** Estilos extra personales **/
            text-align: center;
            line-height: 1.5cm;
        }

        /** Definir las reglas del pie de página **/
        footer {
            position: fixed;
            bottom: 5px;
            left: 0cm;
            right: 0cm;
            height: 2cm;

            /** Estilos extra personales **/
            text-align: center;
            color: #000000;
            line-height: 1.5cm;
        }

        footer .page:after {
            content: counter(page);
        }

        div {
            color: #000000 !important;
        }

        h1 {
            text-align: center;
            text-transform: uppercase;
        }

        table.datos {
            font-size: 8pt;
            width: 100%;
            border-collapse: collapse;
        }

        table.datos th,
        table.datos td {
            border: 1px solid black;
            padding: 8px;
        }

        .encabezado-datos {
            text-align: center !important;
            background-color: #DBDBDB;
        }

        .encabezado-ingresos {
            background-color: #FFF2CC;

        }

        .encabezado-egresos {
            background-color: #BDD7EE;
        }

        .row {
            width: 100%;
        }

        .firma {
            table-layout: fixed;
            width: 100%;
            line-height: normal;
            font-size: 7pt;
            padding-top: 7%;
        }
    </style>
</head>
@php
    $fecha = new Datetime();
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

<body>
    <header>
        <table
            style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:18px;">
            <tr class="row" style="width:auto">
                <td style="width: 10%;">
                    <div class="col-md-3"><img
                            src="{{ 'data:image/png;base64,' . base64_encode(file_get_contents('img/logo.png')) }}"
                            width="90"></div>
                </td>
                <td style="width: 100%">
                    <div class="col-md-7" align="center"><b style="font-size: 75%">ROL GENERAL</b>
                    </div>
                </td>
            </tr>
        </table>
        <hr>
    </header>
    <footer>
        <table style="width: 100%;">
            <tr>
                <td style="line-height: normal;">
                    <div style="margin: 0%; margin-bottom: 0px; margin-top: 0px;" align="center">Esta informacion es
                        propiedad de JPCONSTRUCRED C.LTDA. - Prohibida su divulgacion
                    </div>
                    <div style="margin: 0%; margin-bottom: 0px; margin-top: 0px;" align="center">Generado por el
                        Usuario:
                        {{ auth('sanctum')->user()->empleado->nombres }}
                        {{ auth('sanctum')->user()->empleado->apellidos }} el
                        {{ $fecha->format('d-m-Y H:i') }}
                    </div>
                </td>
            </tr>
        </table>
    </footer>
    <div class="contenido">
        <p
            style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12; font-weight:bold; margin-top: -6px;">
        <div align="center"><strong>{{ $nombre }}
            </strong></div>
        </p>
        <br>
        <table width="100%" border="1" align="left" cellpadding="0" cellspacing="0" class="datos">
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
                <!--<td
                    rowspan="2"style="  text-align: center !important;
                                                background-color: #DBDBDB;">
                    CARGO</td> -->
                <!--<td
                    rowspan="2"style="  text-align: center !important;
                                                background-color: #DBDBDB;">
                    CIUDAD</td>-->
                <td
                    rowspan="2"style="  text-align: center !important;
                                                background-color: #DBDBDB;">
                    SUELDO</td>
                <td rowspan="2" style="background-color: #F8CBAD">DIAS </td>
                <th colspan="{{ $numcol_ingreso }}" scope="colgroup" class="encabezado-ingresos"
                    style="text-align: center !important;
                                                background-color: #FFF2CC;">
                    INGRESOS</th>
                <td rowspan="2" style="background-color: #FFE699">TTII
                </td>
                <th colspan="{{ $numcol_egreso }}" scope="colgroup" class="encabezado-egresos"
                    style="text-align: center !important;
                                                background-color: #BDD7EE;">
                    EGRESOS</th>
                <td rowspan="2" style="background-color: #CCCCFF">TTEE
                </td>
                <td rowspan="2" style="background-color:#A9D08E">TTROL
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
                    XIIIROL</th>
                <th
                    scope="col"class="encabezado-ingresos"style="text-align: center !important;
                                                background-color: #FFF2CC;">
                    XIVROL</th>
                <th
                    scope="col"class="encabezado-ingresos"style="text-align: center !important;
                                                background-color:#FFF2CC;">
                    FDRAROL</th>
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
                    PRSQRG</th>

                <th scope="col"class="encabezado-egresos"
                    style="text-align: center !important;
                                                background-color: #BDD7EE;">
                    PRHIPO</th>
                <th scope="col"class="encabezado-egresos"
                    style="text-align: center !important;
                                                background-color: #BDD7EE;">
                    PRESTAMO</th>
                <th scope="col"class="encabezado-egresos"
                    style="text-align: center !important;
                                                background-color: #BDD7EE;">
                    EXTCONYUGE</th>
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
                    <!-- <td>{{-- $rol_pago['cargo'] --}}</td>-->
                    <!--<td>$rol_pago['ciuda --}}d'] --}}</td>-->
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
                                        <td>{{ number_format($subvalue['valor'], 2, ',', '.') }}
                                        </td>
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
                <td colspan="3" style="text-align: center">
                    <strong>TOTALES&nbsp;</strong>
                </td>
                <td>{{ number_format($sumatoria['salario'], 2, ',', '.') }}</td>
                <td>&nbsp;</td>
                <td>{{ number_format($sumatoria['sueldo'], 2, ',', '.') }}</td>
                <td>{{ number_format($sumatoria['decimo_tercero'], 2, ',', '.') }}
                </td>
                <td>{{ number_format($sumatoria['decimo_cuarto'], 2, ',', '.') }}
                </td>
                <td>{{ number_format($sumatoria['fondos_reserva'], 2, ',', '.') }}
                </td>
                @foreach ($sumatoria_ingresos as $sumatoria_ingreso)
                    <td>6-{{ number_format($sumatoria_ingreso, 2, ',', '.') }}</td>
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
                <td>{{ number_format($sumatoria['extension_conyugal'], 2, ',', '.') }}
                </td>
                @if ($tiene_supa)
                    <td>{{ number_format($sumatoria['supa'], 2, ',', '.') }}</td>
                @endif
                <td>{{ number_format($sumatoria['anticipo'], 2, ',', '.') }}</td>
                @foreach ($sumatoria_egresos as $sumatoria_egreso)
                    <td>{{ number_format($sumatoria_egreso, 2, ',', '.') }}</td>
                @endforeach
                <td>{{ number_format($sumatoria['total_egreso'], 2, ',', '.') }}
                </td>
                <td>{{ number_format($sumatoria['total'], 2, ',', '.') }}</td>
            </tr>
        </table>
        <table class="firma" style="width: 100%;">
            <thead>
                <th align="center">
                    __________________________________________<br />
                    <b>{{ $creador_rol_pago->nombres . '' . $creador_rol_pago->apellidos }}</b>
                    <br>
                    <b>ELABORADO</b>
                </th>
                <th align="center"></th>
                <th align="center">
                    __________________________________________<br />
                    <b>ING. JEAN PATRICIO PAZMIÑO BARROS</b>
                    <br>
                    <b>APROBADO </b>
                </th>
            </thead>

        </table>
    </div>
    <script type="text/php">
        if (isset($pdf)) {
                $text = "Pág {PAGE_NUM} de {PAGE_COUNT}";
                $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
                $pdf->page_text(10, 800, $text, $font, 12);
        }
    </script>
</body>

</html>
