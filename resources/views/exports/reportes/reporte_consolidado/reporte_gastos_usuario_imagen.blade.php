<html>
@php
    $fecha = new Datetime();
    $logo_principal = 'data:image/png;base64,' . base64_encode(file_get_contents(public_path() . $configuracion['logo_claro']));
    $logo_watermark = 'data:image/png;base64,' . base64_encode(file_get_contents(public_path() . $configuracion['logo_marca_agua']));
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de Gastos</title>
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

        table {
            table-layout: auto;
            /* Adjust column width to fit content */
            width: 100%;
            font-family: Verdana, Arial, Helvetica, sans-serif;
            font-size: 12px;
        }

        table td:nth-child(11) {
            max-width: 10%;
            /* Set max width for "Autorizador" column */
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
                <td style="width: 100%">
                    <div class="col-md-7" align="center"><b style="font-size: 75%">REPORTE DE GASTOS
                            {{ ' DEL ' . date('d-m-Y', strtotime($fecha_inicio)) . ' AL ' . date('d-m-Y', strtotime($fecha_fin)) }}</b>
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
                    <div style="margin: 0%; margin-bottom: 0px; margin-top: 0px;" align="center">{{ $copyright }}
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
        @php
            $total = 0;
        @endphp
        @if ($usuario != '')
            <p
                style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12; font-weight:bold; margin-top: -6px;">
            <div align="center" style=" background-color:#bfbfbf;"><strong>{{ $usuario }} </strong></div>
            </p>
            <br>
        @endif
        @if ($usuario != '')
            <table
                style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px;margin-top: 20px;">
                <tr height="29">
                    <td height="15">
                        <div align="center">
                            <table width="100%">
                                <tr>
                                    <td height="55px;">
                                        <table width="100%" border="1" align="left" cellpadding="0"
                                            cellspacing="0">
                                            <tr>
                                                <td bgcolor="#a9d08e" style="font-size:10px" width="29%">
                                                    <div align="center"><strong>NOMBRES Y APELLIDOS</strong></div>
                                                </td>
                                                <td bgcolor="#a9d08e" style="font-size:10px" width="15%">
                                                    <div align="center"><strong>LUGAR</strong></div>
                                                </td>
                                                <td bgcolor="#a9d08e" style="font-size:10px" width="17%">
                                                    <div align="center"><strong>FECHA CONSOLIDADO</strong></div>
                                                </td>
                                                <td bgcolor="#a9d08e" style="font-size:10px" width="29%">
                                                    <div align="center"><strong>DESCRIPCI&Oacute;N</strong></div>
                                                </td>
                                                <td bgcolor="#a9d08e" style="font-size:10px" width="10%">
                                                    <div align="center"><strong>MONTO</strong></div>
                                                </td>
                                            </tr>
                                            <!--Saldo Inicial-->
                                            <tr>
                                                <td style="font-size:10px" width="29%">
                                                    <div align="left">
                                                        {{ $usuario }}
                                                    </div>
                                                </td>
                                                <td style="font-size:10px" width="15%">
                                                    <div align="left">{{ $usuario_canton }}
                                                    </div>
                                                </td>
                                                <td style="font-size:10px" width="17%">
                                                    <div align="center">{{ date('d-m-Y', strtotime($fecha_anterior)) }}
                                                    </div>
                                                </td>
                                                <td style="font-size:10px" width="29%">
                                                    <div align="left">Saldo Inicial (+)</div>
                                                </td>
                                                <td style="font-size:10px" width="10%">
                                                    <div align="right">
                                                        {{ number_format($saldo_anterior, 2, ',', '.') }}</div>
                                                </td>
                                            </tr>
                                            <!--Fin Saldo Inicial-->
                                            <!--Acreditaciones-->
                                            <tr>
                                                <td style="font-size:10px" width="29%">
                                                    <div align="left">
                                                        {{ $usuario }}
                                                    </div>
                                                </td>
                                                <td style="font-size:10px" width="15%">
                                                    <div align="left">{{ $usuario_canton }}
                                                    </div>
                                                </td>
                                                <td style="font-size:10px" width="17%">
                                                    <div align="center">
                                                        {{ date('d-m-Y', strtotime($fecha_inicio)) . ' ' . date('d-m-Y', strtotime($fecha_fin)) }}
                                                    </div>
                                                </td>
                                                <td style="font-size:10px" width="29%">
                                                    <div align="left">Acreditaciones (+)</div>
                                                </td>
                                                <td style="font-size:10px" width="10%">
                                                    <div align="right">
                                                        {{ number_format($acreditaciones, 2, ',', '.') }}</div>
                                                </td>
                                            </tr>
                                            <!--Fin Acreditaciones-->
                                            <!--Transferencias-->
                                            <tr>
                                                <td style="font-size:10px" width="29%">
                                                    <div align="left">
                                                        {{ $usuario }}
                                                    </div>
                                                </td>
                                                <td style="font-size:10px" width="15%">
                                                    <div align="left">{{ $usuario_canton }}
                                                    </div>
                                                </td>
                                                <td style="font-size:10px" width="17%">
                                                    <div align="center">
                                                        {{ date('d-m-Y', strtotime($fecha_inicio)) . ' ' . date('d-m-Y', strtotime($fecha_fin)) }}
                                                    </div>
                                                </td>
                                                <td style="font-size:10px" width="29%">
                                                    <div align="left">Transferencias Enviadas (-)</div>
                                                </td>
                                                <td style="font-size:10px" width="10%">
                                                    <div align="right">
                                                        {{ number_format($transferencia, 2, ',', '.') }}</div>
                                                </td>
                                            </tr>
                                            <!--Fin Transferencias-->
                                            <!--transferencias recibidas-->
                                            <tr>
                                                <td style="font-size:10px" width="29%">
                                                    <div align="left">
                                                        {{ $usuario }}
                                                    </div>
                                                </td>
                                                <td style="font-size:10px" width="15%">
                                                    <div align="left">{{ $usuario_canton }}
                                                    </div>
                                                </td>
                                                <td style="font-size:10px" width="17%">
                                                    <div align="center">
                                                        {{ date('d-m-Y', strtotime($fecha_inicio)) . ' ' . date('d-m-Y', strtotime($fecha_fin)) }}
                                                    </div>
                                                </td>
                                                <td style="font-size:10px" width="29%">
                                                    <div align="left">Transferencias Recibidas (+)</div>
                                                </td>
                                                <td style="font-size:10px" width="10%">
                                                    <div align="right">
                                                        {{ number_format($transferencia_recibida, 2, ',', '.') }}</div>
                                                </td>
                                            </tr>
                                            <!--Gastos-->
                                            <tr>
                                                <td style="font-size:10px" width="29%">
                                                    <div align="left">
                                                        {{ $usuario }}
                                                    </div>
                                                </td>
                                                <td style="font-size:10px" width="15%">
                                                    <div align="left">{{ $usuario_canton }}
                                                    </div>
                                                </td>
                                                <td style="font-size:10px" width="17%">
                                                    <div align="center">
                                                        {{ date('d-m-Y', strtotime($fecha_inicio)) . ' ' . date('d-m-Y', strtotime($fecha_fin)) }}
                                                    </div>
                                                </td>
                                                <td style="font-size:10px" width="29%">
                                                    <div align="left">Gastos (-)</div>
                                                </td>
                                                <td style="font-size:10px" width="10%">
                                                    <div align="right">
                                                        {{ number_format($gastos_totales, 2, ',', '.') }}</div>
                                                </td>
                                            </tr>
                                            <!--Fin Gastos-->
                                            <!--Saldo Final-->
                                            <tr>
                                                <td colspan="4" style="font-size:10px">
                                                    <div align="right"><strong>TOTAL:</strong></div>
                                                </td>
                                                <td style="font-size:10px" align="center">
                                                    <div align="right" style="margin-right:20px;">
                                                        {{ number_format($total_suma, 2, ',', ' ') }}</div>
                                                </td>
                                            </tr>
                                            <!--Fin Saldo Final-->
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>
            </table>
        @endif


        <table width="100%" border="1" align="left" cellpadding="0" cellspacing="0">
            <tr>
                <td bgcolor="#a9d08e" style="font-size:10px" width="3%">
                    <div align="center"><strong>#</strong></div>
                </td>
                <td bgcolor="#a9d08e" style="font-size:10px" width="15%">
                    <div align="center"><strong>NOMBRES Y APELLIDOS</strong></div>
                </td>
                <td bgcolor="#a9d08e" style="font-size:10px" width="6%">
                    <div align="center"><strong>LUGAR</strong></div>
                </td>
                <td bgcolor="#a9d08e" style="font-size:10px" width="5%">
                    <div align="center"><strong>FECHA</strong></div>
                </td>
                <td bgcolor="#a9d08e" style="font-size:10px" width="5%">
                    <div align="center"><strong>FECHA DE AUTORIZACION</strong></div>
                </td>
                <td bgcolor="#a9d08e" style="font-size:10px" width="8%">
                    <div align="center"><strong>#COMPROBANTE</strong></div>
                </td>
                <td bgcolor="#a9d08e" style="font-size:10px" width="30%">
                    <div align="center"><strong>DESCRIPCION DETALLE</strong></div>
                </td>
                <td bgcolor="#a9d08e" style="font-size:10px" width="12%">
                    <div align="center"><strong>COMPROBANTE 1</strong></div>
                </td>
                <td bgcolor="#a9d08e" style="font-size:10px" width="12%">
                    <div align="center"><strong>COMPROBANTE 2</strong></div>
                </td>
                <td bgcolor="#a9d08e" style="font-size:10px" width="33%">
                    <div align="center"><strong>OBSERVACI&Oacute;N</strong></div>
                </td>
                <td bgcolor="#a9d08e" style="font-size:10px" width="7%">
                    <div align="center"><strong>COMENTARIO</strong></div>
                </td>
                <td bgcolor="#a9d08e" style="font-size:10px">
                    <div align="center"><strong>CENTRO DE COSTO</strong></div>
                </td>
                <td bgcolor="#a9d08e" style="font-size:10px">
                    <div align="center"><strong>SUBCENTRO DE COSTO</strong></div>
                </td>
                <td bgcolor="#a9d08e" style="font-size:10px">
                    <div align="center"><strong>AUTORIZADOR</strong></div>
                </td>
                <td bgcolor="#a9d08e" style="font-size:10px" width="5%">
                    <div align="center"><strong>MONTO</strong></div>
                </td>
            </tr>

            @foreach ($gastos as $gasto)
                @php
                    $total = number_format($gasto['total'], 2) + $total;
                @endphp
                <tr>
                    <td style="font-size:10px">
                        <div align="left">{{ $gasto['num_registro'] }}
                        </div>
                    </td>
                    <td style="font-size:10px">
                        <div align="left">
                            {{ $gasto['usuario']->nombres . ' ' . $gasto['usuario']->apellidos }}
                        </div>
                    </td>
                    <td style="font-size:10px">
                        <div align="left">{{ strtoupper($gasto['lugar']) }}
                        </div>
                    </td>
                    <td style="font-size:10px">
                        <div align="center">{{ date('d-m-Y', strtotime($gasto['fecha'])) }}</div>
                    </td>
                    <td style="font-size:10px">
                        <div align="center">{{ date('d-m-Y', strtotime($gasto['fecha_autorizacion'])) }}</div>
                    </td>
                    <td style="font-size:10px">
                        <div align="left">{{ $gasto['factura'] }}</div>
                    </td>
                    <td style="font-size:10px">
                        <div align="left">{{ strtoupper($gasto['sub_detalle_desc']) }}</div>
                    </td>
                    <td style="font-size:10px">
                        <div class="col-md-3">
                            <a href="{{ url($gasto['comprobante']) }}" target="_blank" title="nombreImagen">
                                <img src="{{ 'data:image/png;base64,' . base64_encode(file_get_contents(public_path() . $gasto['comprobante'])) }}"
                                    width="250">
                            </a>
                        </div>
                    </td>
                    <td style="font-size:10px">
                        <div class="col-md-3">
                            <a href="{{ url($gasto['comprobante2']) }}" target="_blank" title="nombreImagen">
                                <img src="{{ 'data:image/png;base64,' . base64_encode(file_get_contents(public_path() . $gasto['comprobante2'])) }}"
                                    width="250" />
                            </a>
                        </div>
                    </td>
                    <td style="font-size:10px; word-wrap: break-word;">
                        <div align="left">{{ strtoupper($gasto['observacion']) }}</div>
                    </td>
                    <td style="font-size:10px; word-wrap: break-word;">
                        <div align="left">{{ strtoupper($gasto['detalle_estado']) }}</div>
                    </td>
                    <td style="font-size:10px; word-wrap: break-word;">{{ $gasto['centro_costo'] }}</td>
                    <td style="font-size:10px; word-wrap: break-word;">{{ $gasto['sub_centro_costo'] }}</td>
                    <td style="font-size:10px" width="29%">
                        <div align="left">
                            {{ $gasto['autorizador'] }}
                        </div>
                    </td>
                    <td style="font-size:10px">
                        <div align="right">
                            {{ number_format($gasto['total'], 2, ',', '.') }}</div>
                    </td>
                </tr>
            @endforeach
            <tr>
                <td colspan="11" style="font-size:10px" width="29%">
                    <div align="right"><strong>Total</strong></div>
                </td>
                <td style="font-size:10px" width="10%">
                    <div align="right">
                        <strong>{{ number_format($total, 2, ',', '.') }}</strong>
                    </div>
                </td>
            </tr>
        </table>
    </main>
    <script type="text/php">
        if (isset($pdf)) {
                $text = "Pág {PAGE_NUM} de {PAGE_COUNT}";
                $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
                $pdf->page_text(10, 1130, $text, $font, 12);
        }
    </script>
</body>

</html>
