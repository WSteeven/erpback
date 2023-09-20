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
            font-size: 12px;
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
            bottom: 10px;
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
        <div align="center"><strong>{{ $periodo }}
            </strong></div>
        </p>
        <br>
        <table width="100%" border="1" align="left" cellpadding="0" cellspacing="0">
            <tr>
                <td style="text-align: center !important;
                    background-color: #DBDBDB;">
                    ITEM</td>
                <td style="text-align: center !important;
                    background-color: #DBDBDB;">
                    EMPLEADO</td>
                <td style="  text-align: center !important;
                    background-color: #DBDBDB;">
                    CEDULA</td>
                <td style="  text-align: center !important;
                    background-color: #DBDBDB;">
                    CARGO</td>
                <td style="  text-align: center !important;
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
                $pdf->page_text(10, 550, $text, $font, 12);
        }
    </script>
</body>

</html>
