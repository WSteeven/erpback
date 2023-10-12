<!DOCTYPE html>
<html lang="es">
    @php
    $fecha = new Datetime();
    $logo_principal = 'data:image/png;base64,' . base64_encode(file_get_contents(public_path() . $configuracion['logo_claro']));
    $logo_watermark = 'data:image/png;base64,' . base64_encode(file_get_contents(public_path() . $configuracion['logo_marca_agua']));
    $suma_salario =0;
@endphp

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
            background-image: url({{ $logo_watermark }});
            background-size: 50% auto;
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
<body>
    <header>
        <table
            style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:18px;">
            <tr class="row" style="width:auto">
                <td style="width: 10%;">
                    <div class="col-md-3"><img
                            src="{{ $logo_principal }}"
                            width="90"></div>
                </td>
                <td style="width: 100%">
                    <div class="col-md-7" align="center"><b style="font-size: 75%">Empleados</b>
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
        <table width="100%" border="1" align="left" cellpadding="0" cellspacing="0" class="datos">
            <tr class="encabezado-datos" style="text-align: center ">
                <td
                    style="text-align: center !important;
                                                background-color: #DBDBDB;">
                    ITEM</td>
                <td
                    style="text-align: center !important;
                                                background-color: #DBDBDB;">
                    APELLIDOS</td>
                    <td
                    style="text-align: center !important;
                                                background-color: #DBDBDB;">
                    NOMBRES</td>
                <td
                    style="  text-align: center !important;
                                                background-color: #DBDBDB;">
                    CEDULA</td>
                <td
                    style="  text-align: center !important;
                                                background-color: #DBDBDB;">
                    DEPARTAMENTO</td>
                <td
                    style="  text-align: center !important;
                                                background-color: #DBDBDB;">
                    CARGO</td>
                    <td
                    style="  text-align: center !important;
                                                background-color: #DBDBDB;">
                    SALARIO</td>
            </tr>

            @foreach ($results as $reporte)

                <tr>
                    <td>{{ $reporte['item']}}</td>
                    <td>{{ $reporte['apellidos'] }}</td>
                    <td>{{ $reporte['nombres'] }}</td>
                    <td>{{ $reporte['identificacion'] }}</td>
                    <td>{{ $reporte['departamento'] }}</td>
                    <td>{{ $reporte['cargo'] }}</td>
                    <td>{{ $reporte['salario'] }}</td>

                </tr>
@php
    $suma_salario += $reporte['salario'] ;
@endphp
            @endforeach
<tr>
    <td colspan="6">Sumatoria</td>
    <td>
        {{ $suma_salario }}
    </td>
</tr>
        </table>
    </div>
    <script type="text/php">
        if (isset($pdf)) {
                $text = "Pág {PAGE_NUM} de {PAGE_COUNT}";
                $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
                $pdf->page_text(10,785, $text, $font, 12);
        }
    </script>
</body>

</html>
