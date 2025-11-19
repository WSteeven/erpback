<html>
@php
    use Src\Shared\Utils;
    $fecha = new Datetime();
    if ($empleado_solicita->firma_url) {
        $firma_solicitante = Utils::urlToBase64(url($empleado_solicita->firma_url));
    }
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Proforma N° {{ $proforma['id'] }}</title>
    <style>
        @page {
            margin: 2px 15px 5px 15px;
        }

        body {
            background-image: url({{ Utils::urlToBase64(url($configuracion->logo_marca_agua)) }});
            background-repeat: no-repeat;
            background-position: center;
            background-size: contain;
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
            bottom: 90px;
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
            top: 140px;
            left: 0cm;
            right: 0cm;
            margin-bottom: 7cm;
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
            border-collapse: collapse;
        }

        td,
        th {
            line-height: 1.2;
            padding: 2px;
        }

        .firma {
            table-layout: fixed;
            width: 100%;
            line-height: normal;
            font-size: 12px;
            /* position: inherit; */
            /* top: 140px; */
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
            <tr class="row">
                <td style="width: 60%;">
                    <table width="95%" border="0" style="font-family:Arial; font-size:10px;">
                        <tr>
                            <td align="center">
                                <div align="center"><img
                                        src="{{  Utils::urlToBase64(url($configuracion->logo_claro))  }}" alt=""
                                        width="218" height="85" /></div>
                            </td>
                        </tr>
                        <tr>
                            <td align="center">
                                {{ $configuracion['direccion_principal'] }}
                            </td>
                        </tr>
                        {{-- <tr>--}}
                            {{-- <td align="center">{{ strtoupper('Guayaquil - Guayas - Ecuador')}}</td>--}}
                            {{-- </tr>--}}
                        <tr>
                            <td align="center">TELF. {{ $configuracion['telefono'] }}
                            </td>
                        </tr>
                        <tr>
                            <td align="center">{{ strtolower($configuracion['correo_principal']) }} -
                                {{ strtolower($configuracion['sitio_web'])}}
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="width: 50%">
                    <table style="margin-left:auto; margin-right:auto; text-align:center;">
                        <tr>
                            <td align="right">
                                <b style="font-size: 24px; margin-left: 30px">PROFORMA</b>
                            </td>
                        </tr>
                        <tr>
                            <td align="center">
                                <b>N° </b> {{ $proforma['codigo'] }}
                            </td>
                        </tr>
                        <tr>
                            <td align="center">
                                <b>Fecha: </b>{{ date('Y-m-d', strtotime($proforma['created_at'])) }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </header>
    <footer>
        @if ($proforma['cliente_id'] !== 2)
            <table class="firma" style="width: 100%; margin-bottom: 10px">
                <thead>
                    <th align="center">
                        @isset($firma_solicitante)
                            <img src="{{ $firma_solicitante }}" alt="" width="100%" height="40">
                        @endisset
                        @empty($firma_solicitante)
                            ___________________<br />
                        @endempty
                        <b>REALIZADO POR</b>
                    </th>
                    <th align="center"></th>
                    <th align="center"></th>
                </thead>
                <tbody>
                    <tr align="center">
                        <td>{{ $empleado_solicita->nombres }} {{ $empleado_solicita->apellidos }} <br>
                            {{ $empleado_solicita->identificacion }}
                        </td>
                        <td></td>
                        <td>

                        </td>
                    </tr>
                </tbody>
            </table>
        @else
            <p style="margin-bottom: 80px"></p>
        @endif
        <table style="width: 100%;">
            <tr>
                <td style="line-height: normal;">
                    <div style="margin: 0%; margin-bottom: 0px; margin-top: 0px;" align="center">Esta informacion es
                        propiedad de JPCONSTRUCRED C.LTDA.
                    </div>
                    <div style="margin: 0%; margin-bottom: 0px; margin-top: 0px;" align="center">Generado por el
                        usuario:
                        {{ auth('sanctum')->user()->empleado->nombres }}
                        {{ auth('sanctum')->user()->empleado->apellidos }} el
                        {{ $fecha->format('d-m-Y H:i') }}
                    </div>
                </td>
            </tr>
        </table>
    </footer>
    {{-- Cuerpo --}}
    <main>
        <table
            style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px;margin-top: 20px;">
            <tr>
                <td width="60%" style="min-width: 100%">
                    <table style="width: 90%" border="1">
                        <tr>
                            <td colspan="2" align="center"> CLIENTE</td>
                        </tr>
                        <tr>
                            <td>Razón social</td>
                            <td>{{ $cliente['razon_social'] }}</td>
                        </tr>
                        <tr>
                            <td>RUC</td>
                            <td>{{ $cliente['ruc'] }}</td>
                        </tr>
                        <tr>
                            <td>Solicitado por</td>
                            <td>{{ $proforma['solicitante'] }}<br>{{ $empleado_solicita->user->email }}</td>
                        </tr>
                    </table>
                </td>
                <td width="40%">
                    <table border="1">
                        <tr>
                            <td colspan="2" align="center"> CONDICIONES</td>
                        </tr>
                        <tr>
                            <td>Autorizado por</td>
                            <td>{{ $proforma['autorizador'] }}</td>
                        </tr>
                        <tr>
                            <td>Estado</td>
                            <td>{{ $proforma['autorizacion'] == 'CANCELADO' ? 'ANULADA - ' . $proforma['estado'] : $proforma['autorizacion'] }}
                            </td>
                        </tr>
                        @if ($proforma['autorizacion'] == 'CANCELADO')
                            <tr>
                                <td>Causa anulación</td>
                                <td>{{ $proforma['causa_anulacion'] }}</td>
                            </tr>
                        @endif
                        <tr>
                            <td>Forma de pago</td>
                            <td>{{ $proforma['forma'] }}</td>
                        </tr>
                        <tr>
                            <td>Tiempo de validez</td>
                            <td>{{ $proforma['tiempo'] }} a partir de la fecha de creación</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        {{-- Tabla de detalles --}}
        <table
            style="color:#000000; table-layout:fixed; width: 98%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px;margin-top: 20px;"
            border="1">
            <thead>
                <th>Cantidad</th>
                <th width="40%">Descripción</th>
                <th>Medida</th>
                <th>Precio U.</th>
                <th>Desc.</th>
                <th>{{$texto_iva}}</th>
                <th>Subtotal</th>
                <th>Total</th>
            </thead>
            <tbody>

                @foreach ($proforma['listadoProductos'] as $index => $item)
                    <tr class="row" style="width: auto">
                        {{-- <td>{{$index+1}}</td> --}}
                        <td align="center">{{ $item['cantidad'] }}</td>
                        <td align="center">{{ strtoupper($item['descripcion']) }}</td>
                        <td align="center">{{ $item['unidad_medida_id'] }}</td>
                        <td align="center">{{ $item['precio_unitario'] }}</td>
                        <td align="center">{{ $item['descuento'] }}</td>
                        <td align="center">{{ $item['iva'] }}</td>
                        <td align="center">{{ $item['subtotal'] }}</td>
                        <td align="center">{{ $item['total'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <p>{{ $valor }}</p>
        <table
            style="color:#000000; table-layout:fixed; width: 98%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px;margin-top: 20px;">
            <tr>
                <td width="70%">
                    <table border="1" style="max-width: 100%;width: 90%">
                        <tr>
                            <td align="center"> OBSERVACIONES</td>
                        </tr>
                        <tr>
                            <td style="height: 100px" valign="top">{{ $proforma['descripcion'] }}</td>
                        </tr>
                    </table>
                </td>
                <td width="30%">
                    <table align="right" border="1" style="max-width: 100%;width:70%">
                        @php
                            // Recalcular totales con la lógica correcta del frontend
                            $subtotal = 0;
                            $subtotal_con_impuestos = 0;
                            $subtotal_sin_impuestos = 0;
                            $descuento_items = 0;

                            // Iterar sobre los items
                            foreach ($proforma['listadoProductos'] as $item) {
                                $subtotal += floatval($item['subtotal']);

                                if ($item['grava_iva']) {
                                    $subtotal_con_impuestos += floatval($item['subtotal']);
                                } else {
                                    $subtotal_sin_impuestos += floatval($item['subtotal']);
                                }

                                $descuento_items += floatval($item['descuento']);
                            }

                            // Aplicar descuento general si existe
                            if ($proforma['descuento_general'] > 0) {
                                $descuento_final = $proforma['descuento_general'];
                                // Restar el descuento del subtotal_con_impuestos ANTES de mostrarlo
                                $subtotal_con_impuestos_mostrar = $subtotal_con_impuestos - $proforma['descuento_general'];
                                $iva_calculado = ($subtotal_con_impuestos_mostrar * $proforma['iva']) / 100;
                                $total_final = $subtotal + $iva_calculado - $descuento_final;
                            } else {
                                $descuento_final = $descuento_items;
                                $subtotal_con_impuestos_mostrar = $subtotal_con_impuestos;
                                $iva_calculado = ($subtotal_con_impuestos * $proforma['iva']) / 100;
                                $total_final = $subtotal_con_impuestos + $subtotal_sin_impuestos + $iva_calculado;
                            }
                        @endphp

                        <tr>
                            <td align="right">SUBTOTAL</td>
                            <td align="right">{{ number_format($subtotal, 2) }}</td>
                        </tr>
                        <tr>
                            <td align="right">SUBTOTAL 0%</td>
                            <td align="right">{{ number_format($subtotal_sin_impuestos, 2) }}</td>
                        </tr>
                        <tr>
                            <td align="right">DESCUENTO</td>
                            <td align="right">{{ number_format($descuento_final, 2) }}</td>
                        </tr>
                        <tr>
                            <td align="right">SUBTOTAL {{ $proforma['iva'] }}%</td>
                            <td align="right">{{ number_format($subtotal_con_impuestos_mostrar, 2) }}</td>
                        </tr>
                        <tr>
                            <td align="right">{{$texto_iva}} {{ $proforma['iva'] }}%</td>
                            <td align="right">{{ number_format($iva_calculado, 2) }}</td>
                        </tr>
                        <tr>
                            <td align="right">TOTAL</td>
                            <td align="right">{{ number_format($total_final, 2) }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

    </main>
    <script type="text/php">
        if (isset($pdf)) {
                $text = "Pág {PAGE_NUM} de {PAGE_COUNT}";
                $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
                $pdf->page_text(10, 800, $text, $font, 12);
        }
    </script>
</body>

</html>
