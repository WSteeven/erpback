<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rol de Pagos</title>
    <style>
        @page {
            margin: 2px 15px 5px 15px;
        }

        body {
            /* background-image: url({{ 'data:image/png;base64,' . base64_encode(file_get_contents('img/logoJPBN_10.png')) }}); */
            background-image: url({{ 'data:image/png;base64,' . base64_encode(file_get_contents('img/logoJPBN_10.png')) }});
            background-repeat: no-repeat;
            background-position: center;
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

        main {
            position: relative;
            top: 80px;
            left: 0cm;
            right: 0cm;
            margin-bottom: 4.3cm;
            font-size: 12px;
        }

        div {
            color: #000000 !important;
        }

        h1 {
            text-align: center;
            text-transform: uppercase;
        }

        table.datos {
            font-size:4pt;
            width: 100%;
            border-collapse: collapse;
        }

        table.datos th,
        table.datos td {
            border: 1px solid black;
            padding: 8px;
        }
        .encabezado-datos{
            text-align: center !important;
            background-color: #DBDBDB;
        }
        .encabezado-ingresos{
            background-color: #FFF2CC;
        }
        .encabezado-egresos{
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
    $ciclo = [1, 2, 3, 4, 5, 6, 7, 8, 9, 0, 1, 2, 3, 4, 5];
    $numcol_ingreso = $cantidad_columna_ingresos + 5;
    $numcol_egreso = $cantidad_columna_egresos + 3;
    $tiene_supa = $sumatoria['supa'] > 0;
    $tiene_bonificacion = $sumatoria['bonificacion'] > 0;
    $tiene_bono_recurente = $sumatoria['bono_recurente'] > 0;
    if ($tiene_bono_recurente) {
        $numcol_ingreso = $cantidad_columna_ingresos + 5;
    }
    if ($tiene_bonificacion) {
        $numcol_ingreso = $cantidad_columna_ingresos +5;
    }
    if ($tiene_bonificacion && $tiene_bono_recurente) {
        $numcol_ingreso = $cantidad_columna_ingresos + 6;
    }

    if ($tiene_supa) {
        $numcol_egreso = $cantidad_columna_egresos + 3;
    }
@endphp

<body>
    <header>
        <table
            style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:18px;">
            <tr class="row" style="width:auto">
                <td style="width: 10%;">
                    <div class="col-md-3"><img
                            src="{{ 'data:image/png;base64,' . base64_encode(file_get_contents('img/logoJP.png')) }}"
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
    <main>
        @if (isset($periodo))
            <p
                style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12; font-weight:bold; margin-top: -6px;">
            <div align="center"><strong>{{ $periodo }}
                </strong></div>
            </p>
            <br>
        @endif
        <table width="100%" border="1" align="left" cellpadding="0" cellspacing="0" class="datos">
            <colgroup span="{{ $numcol_ingreso }}"></colgroup>
            <colgroup span="{{ $numcol_egreso }}"></colgroup>
            <tr class="encabezado-datos">
                <td rowspan="2">ITEM</td>
                <td rowspan="2">EMPLEADO</td>
                <td rowspan="2">CEDULA</td>
                <td rowspan="2">CARGO</td>
                <td rowspan="2">SUELDO</td>
                <td rowspan="2" style="background-color: #F8CBAD">DIAS  TRABAJADOS</td>
                <th colspan="{{ $numcol_ingreso }}" scope="colgroup" class="encabezado-ingresos">INGRESOS</th>
                <td rowspan="2" style="background-color: #FFE699">TOTAL INGRESOS</td>
                <th colspan="{{ $numcol_egreso }}" scope="colgroup" class ="encabezado-egresos">EGRESOS</th>
                <td rowspan="2" style="background-color: #CCCCFF" >TOTAL EGRESOS</td>
                <td rowspan="2"style="background-color:#A9D08E">NETO A RECIBIR</td>

            </tr>
            <tr class="encabezado-datos">
                <td scope="col"class="encabezado-ingresos">SUELDO GANADO</td>
                <th scope="col"class="encabezado-ingresos">DECIMO XII</th>
                <th scope="col"class="encabezado-ingresos">DECIMO XIV</th>
                <th scope="col"class="encabezado-ingresos">FONDOS DE RESERVA</th>
                <th scope="col"class="encabezado-ingresos">IESS (9.45%)</th>
                @if ($tiene_bonificacion)
                    <th scope="col" class="encabezado-ingresos">BONIFICACION</th>
                @endif
                @if ($tiene_bono_recurente)
                    <th scope="col" class="encabezado-ingresos">BONO RECURENTE</th>
                @endif
                @foreach ($columnas_ingresos as $ingreso)
                    <th scope="col"class="encabezado-ingresos">{{ $ingreso }}</th>
                @endforeach
                <th scope="col"class ="encabezado-egresos">PRESTAMO QUIROGRAFARIO</th>
                <th scope="col"class ="encabezado-egresos">PRESTAMO HIPOTECARIO</th>
                <th scope="col"class="encabezado-egresos">ANTICIPO</th>

                @if ($tiene_supa)
                    <th scope="col"class ="encabezado-egresos">SUPA</th>
                @endif
                @foreach ($columnas_egresos as $egreso)
                    <th scope="col"class ="encabezado-egresos">{{ $egreso }}</th>
                @endforeach
            </tr>
            @foreach ($roles_pago as $rol_pago)
                <tr>
                    <td>{{ $rol_pago['item'] }}</td>
                    <td>{{ $rol_pago['empleado_info'] }}</td>
                    <td>{{ $rol_pago['cedula'] }}</td>
                    <td>{{ $rol_pago['cargo'] }}</td>
                    <td>{{ $rol_pago['salario'] }}</td>
                    <td>{{ $rol_pago['dias_laborados'] }}</td>
                    <td>{{ $rol_pago['sueldo'] }}</td>
                    <td>{{ $rol_pago['decimo_tercero'] }}</td>
                    <td>{{ $rol_pago['decimo_cuarto'] }}</td>
                    <td> {{ $rol_pago['fondos_reserva'] }}</td>
                    <td> {{ $rol_pago['iess'] }}</td>
                    @if ($tiene_bonificacion)
                        <td>{{ $rol_pago['bonificacion'] }}</td>
                    @endif
                    @if ($tiene_bono_recurente)
                        <td>{{ $rol_pago['bono_recurente'] }}</td>
                    @endif
                    @foreach ($rol_pago['ingresos'] as $ingreso)
                        <td>{{ $ingreso->monto }}</td>
                    @endforeach
                    @if ($rol_pago['ingresos_cantidad_columna'] == 0)
                        @for ($i = 0; $i < $cantidad_columna_ingresos; $i++)
                            <td>0 </td>
                        @endfor
                    @endif
                    <td>{{ $rol_pago['total_ingreso'] }}</td>
                    <td>{{ $rol_pago['prestamo_quirorafario'] }}</td>
                    <td>{{ $rol_pago['prestamo_hipotecario'] }}</td>
                    <td>{{ $rol_pago['anticipo'] }}</td>
                    @if ($tiene_supa)
                        <td>{{ $rol_pago['supa'] }}</td>
                    @endif

                    @foreach ($rol_pago['egresos'] as $descuento)
                        <td> {{ $descuento->monto }} </td>
                    @endforeach
                    @if ($rol_pago['egresos_cantidad_columna'] == 0)
                        @for ($i = 0; $i < $cantidad_columna_egresos; $i++)
                            <td>0 </td>
                        @endfor
                    @endif
                    <td>{{ $rol_pago['total_egreso'] }}</td>
                    <td>{{ $rol_pago['total'] }}</td>
                </tr>
            @endforeach
            <tr style="background-color: #FFE699">
                <td colspan="4" style="text-align: center"><strong>TOTALES&nbsp;</strong></td>
                <td> {{ number_format($sumatoria['salario'], 2, ',', '.') }}</td>
                <td>&nbsp;</td>
                <td> {{ number_format($sumatoria['sueldo'], 2, ',', '.') }}</td>
                <td>{{ number_format($sumatoria['decimo_tercero'], 2, ',', '.') }}</td>
                <td>{{ number_format($sumatoria['decimo_cuarto'], 2, ',', '.') }}</td>
                <td>{{ number_format($sumatoria['fondos_reserva'], 2, ',', '.') }}</td>
                <td>{{ number_format($sumatoria['iess'], 2, ',', '.') }}</td>
                <td>{{ number_format($sumatoria['anticipo'], 2, ',', '.') }}</td>
                @foreach ($sumatoria_ingresos as $sumatoria_ingreso)
                    <td>{{ number_format($sumatoria_ingreso, 2, ',', '.') }}</td>
                @endforeach
                <td>{{ number_format($sumatoria['total_ingreso'], 2, ',', '.') }}</td>
                <td>{{ number_format($sumatoria['prestamo_quirorafario'], 2, ',', '.') }}</td>
                <td>{{ number_format($sumatoria['prestamo_hipotecario'], 2, ',', '.') }}</td>
                @if ($tiene_supa)
                <td>{{ number_format($sumatoria['supa'], 2, ',', '.') }}</td>
                @endif
                @foreach ($sumatoria_egresos as $sumatoria_egreso)
                    <td>{{ number_format($sumatoria_egreso, 2, ',', '.') }}</td>
                @endforeach
                <td>{{ number_format($sumatoria['total_egreso'], 2, ',', '.') }}</td>
                <td>{{ number_format($sumatoria['total'], 2, ',', '.') }}</td>
            </tr>
        </table>
        <table class="firma" style="width: 100%;">
            <thead>
                <th align="center">
                    __________________________________________<br />
                    <b>{{ $creador_rol_pago->nombres.' '.$creador_rol_pago->apellidos }}</b>
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
    </main>
    <script type="text/php">
        if (isset($pdf)) {
                $text = "Pág {PAGE_NUM} de {PAGE_COUNT}";
                $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
                $pdf->page_text(10, 785, $text, $font, 12);
        }
    </script>
</body>

</html>
