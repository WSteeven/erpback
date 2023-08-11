<html>
@php
    $fecha = new Datetime();
    $logo = 'data:image/png;base64,' . base64_encode(file_get_contents('img/logoJP.png'));
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Orden de compra N° {{ $orden['id'] }}</title>
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
                                <div align="center"><img src="{{ $logo }}" alt="" width="218"
                                        height="85" /></div>
                            </td>
                        </tr>
                        <tr>
                            <td align="center">
                                Urdesa Central, Bálsamos 323 e/ segunda y cuarta peatonal.
                            </td>
                        </tr>
                        <tr>
                            <td align="center">Guayaquil - Guayas - Ecuador</td>
                        </tr>
                        <tr>
                            <td align="center">Tlf. 0999999999
                            </td>
                        </tr>
                        <tr>
                            <td align="center">info@jpconstrucred.com - www.jpconstrucred.com</td>
                        </tr>
                    </table>
                </td>
                <td style="width: 50%">
                    <table>
                        <tr>
                            <td align="right">
                                <b style="font-size: 30px; margin-left: 30px">ORDEN DE COMPRA</b>
                            </td>
                        </tr>
                        <tr>
                            <td align="center">
                                <b>N° </b> 23-08001
                            </td>
                        </tr>
                        <tr>
                            <td align="center">
                                <b>Fecha: </b>{{ $orden['fecha'] }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </header>
    <footer>
        <table style="width: 100%;">
            <tr>
                <td style="line-height: normal;">
                    <div style="margin: 0%; margin-bottom: 0px; margin-top: 0px;" align="center">Esta informacion es
                        propiedad de JPCONSTRUCRED C.LTDA. <br> Utilizar únicamente para compras a proveedores autorizados
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
                            <td colspan="2" align="center"> PROVEEDOR</td>
                        </tr>
                        <tr>
                            <td>Razón social</td>
                            <td>{{ $proveedor['razon_social'] }}</td>
                        </tr>
                        <tr>
                            <td>RUC</td>
                            <td>{{ $proveedor['ruc'] }}</td>
                        </tr>
                        <tr>
                            <td>Dirección</td>
                            <td>{{ $proveedor['direccion'] }}</td>
                        </tr>
                        <tr>
                            <td>Ubicación</td>
                            <td>{{ $proveedor['ubicacion'] }}</td>
                        </tr>
                        <tr>
                            <td>Solicitado por</td>
                            <td>{{ $orden['solicitante'] }}<br>{{ $empleado_solicita->user->email }}</td>
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
                            <td>{{ $orden['autorizador'] }}</td>
                        </tr>
                        <tr>
                            <td>Estado</td>
                            <td>{{ $orden['autorizacion'] == 'CANCELADO' ? 'ANULADA - ' . $orden['estado'] : $orden['autorizacion'] }}
                            </td>
                        </tr>
                        @if ($orden['autorizacion'] == 'CANCELADO')
                            <tr>
                                <td>Causa anulación</td>
                                <td>{{ $orden['causa_anulacion'] }}</td>
                            </tr>
                        @endif
                        <tr>
                            <td>Forma de pago</td>
                            <td>{{ $orden['forma'] }}</td>
                        </tr>
                        <tr>
                            <td>Tiempo de validez</td>
                            <td>{{ $orden['tiempo'] }} a partir de la fecha de creación</td>
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
                <th>IVA</th>
                <th>Subtotal</th>
                <th>Total</th>
            </thead>
            <tbody>

                @foreach ($orden['listadoProductos'] as $index => $item)
                    <tr class="row" style="width: auto">
                        {{-- <td>{{$index+1}}</td> --}}
                        <td align="center">{{ $item['cantidad'] }}</td>
                        <td align="center">{{ $item['descripcion'] }}</td>
                        <td align="center">{{ $item['unidad_medida'] }}</td>
                        <td align="center">{{ $item['precio_unitario'] }}</td>
                        <td align="center">{{ $item['descuento'] }}</td>
                        <td align="center">{{ $item['iva'] }}</td>
                        <td align="center">{{ $item['subtotal'] }}</td>
                        <td align="center">{{ $item['total'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <table
            style="color:#000000; table-layout:fixed; width: 98%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px;margin-top: 20px;">
            <tr>
                <td width="70%">
                    <table border="1" style="max-width: 100%;width: 90%">
                        <tr>
                            <td align="center"> OBSERVACIONES</td>
                        </tr>
                        <tr>
                            <td style="height: 100px" valign="top">{{ $orden['descripcion'] }}</td>
                        </tr>
                    </table>
                </td>
                <td width="30%">
                    <table align="right" border="1" style="max-width: 100%;width:70%">
                        <tr>
                            <td align="right">SUBTOTAL</td>
                            <td align="center">{{ $orden['subtotal'] }}</td>
                        </tr>
                        <tr>
                            <td align="right">DESCUENTO</td>
                            <td align="center">{{ $orden['descuento'] }}</td>
                        </tr>
                        <tr>
                            <td align="right">IVA</td>
                            <td align="center">{{ $orden['iva'] }}</td>
                        </tr>
                        <tr>
                            <td align="right">TOTAL</td>
                            <td align="center">{{ $orden['total'] }}</td>
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
