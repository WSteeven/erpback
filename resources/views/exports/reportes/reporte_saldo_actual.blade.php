<!DOCTYPE html>
<html lang="es">
@php
    $fecha = new Datetime();
    $logo_principal = 'data:image/png;base64,' . base64_encode(file_get_contents(public_path() . $configuracion['logo_claro']));
    $logo_watermark = 'data:image/png;base64,' . base64_encode(file_get_contents(public_path() . $configuracion['logo_marca_agua']));
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte Saldo Actual</title>
    <style>
        @page {
            margin: 2px 15px 5px 15px;
        }

        body {
            /* background-image: url({{ 'data:image/png;base64,' . base64_encode(file_get_contents('img/logoBN10.png')) }}); */
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
    </style>
</head>

<body>
    <header>
        <table
            style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:18px;">
            <tr class="row" style="width:auto">
                <td style="width: 10%;">
                    <div class="col-md-3"><img src="{{ $logo_principal }}" width="90"></div>
                </td>
                <td style="width: 68%">
                    <div class="col-md-7" align="center"><b>REPORTE SALDO ACTUAL</b></div>
                </td>
                <td style="width: 52%;">
                    <div class="col-md-2" align="right">Sistema de Fondos Rotativos</div>
                </td>
            </tr>
        </table>
        <hr>
    </header>
    <footer>
        <table style="width: 100%;">
            <tr>
                <td style="line-height: normal;">
                    <div style="margin: 0%; margin-bottom: 0px; margin-top: 0px;" align="center">{{ $copyright }}</div>
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
        <table width="100%" border="1" align="left" cellpadding="0" cellspacing="0">
            <tr>
                <td bgcolor="#a9d08e" style="font-size:10px">
                    <div align="center"><strong>#</strong></div>
                </td>
                <td bgcolor="#a9d08e" style="font-size:10px">
                    <div align="center"><strong>APELLIDOS Y NOMBRES</strong></div>
                </td>
                <td bgcolor="#a9d08e" style="font-size:10px">
                    <div align="center"><strong>CARGO</strong></div>
                </td>
                <td bgcolor="#a9d08e" style="font-size:10px">
                    <div align="center"><strong>LUGAR</strong></div>
                </td>
                <td bgcolor="#a9d08e" style="font-size:10px">
                    <div align="center"><strong>MONTO</strong></div>
                </td>
            </tr>
            @php
                $item = 0;
            @endphp
            @foreach ($saldos as $saldo)
                @php
                    $item++;
                @endphp
                <tr>
                    <td style="font-size:10px">
                        <div align="left" style="margin-left:20px;"> {{ $item }}</div>
                    </td>
                    <td style="font-size:10px">
                        <div align="left" style="margin-left:20px;">
                            {{ $saldo['empleado']->apellidos . ' ' . $saldo['empleado']->nombres }}
                        </div>
                    </td>
                    <td style="font-size:10px">
                        <div align="left" style="margin-left:20px;">
                            {{ $saldo['cargo'] }}
                        </div>
                    </td>
                    <td style="font-size:10px">
                        <div align="left" style="margin-left:20px;">
                            {{ $saldo['localidad'] }}
                        </div>
                    </td>
                    <td style="font-size:10px">
                        <div align="right" style="margin-right:20px;">
                            {{ number_format($saldo['saldo_actual'], 2, ',', '.') }}
                        </div>
                    </td>
                </tr>
            @endforeach

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
