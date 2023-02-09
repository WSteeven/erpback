<!DOCTYPE html>
<html lang="es">

<head>
    <title>Pedido N° {{ $id }}</title>
    <style>
        @page {
            margin: 0cm 4px;
        }

        body {
            background-image: url('img/logoJPBN_10.png');
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
            bottom: 1cm;
            left: 0cm;
            right: 0cm;
            height: 2cm;

            /** Estilos extra personales **/
            text-align: center;
            color: #000000;
            line-height: 1.5cm;
        }

        div {
            color: #000000 !important;
        }

        h1 {
            text-align: center;
            text-transform: uppercase;
        }

        .contenido {
            /* margin-top: 4px; */
            font-size: 15px;
            page-break-before: always;
            /* text-transform: uppercase; */
        }

        .row {
            width: 100%;
        }
    </style>
</head>
@php
$usuario = auth()->user();
$fecha = new Datetime();
$qr = QrCode::size(100)
->backgroundColor(255, 90, 0)
->generate('Hola a todos, saludos cordiales');
@endphp

<body>
    <header>
        <table style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:18px;page-break-inside: avoid;">
            <tr class="row" style="width:auto">
                <td>
                    <div class="col-md-3"><img src="img/logoJP.png" width="50"></div>
                </td>
                <td>
                    <div class="col-md-7" align="center"><b>COMPROBANTE DE PEDIDO</b></div>
                </td>
                <td>
                    <div class="col-md-2" align="right">Sistema de bodega</div>
                </td>
            </tr>
        </table>
        <hr>
    </header>
    <br><br><br><br>
    <!-- aqui va el contenido del document<br><br>o -->
    <div class="contenido">
        <table style="width: 100%; border: #000000; border-collapse: collapse;" border="0">
            <tr class="row">
                <td>Transacción N°: <b>{{ $id }}</b></td>
                <td>Fecha: <b>{{ $created_at }}</b></td>
                <td>Solicitante: <b>{{ $solicitante }}</b></td>
            </tr>
            <tr class="row">
                <td>Justificación: <b>{{ $justificacion }}</b></td>
                <td></td>
                <td>Sucursal: <b>{{ $sucursal }}</b></td>
            </tr>
            <tr class="row">
                <td>Autorizado por: <b>{{ $per_autoriza }}</b></td>
                <td></td>
                <td>Estado: <b>{{ $estado }}</b></td>
            </tr>
        </table>
        <table>
            <thead style="margin-bottom:4px;">
                <tr>
                    <td>Tarea: <b>{{ $tarea }}</b></td>
                </tr>
            </thead>
        </table>
        <!-- aqui va el listado de produ bordctos -->
        <table border="1" style="border-collapse: collapse; margin-bottom:4px; width: 98%;" align="center">
            <thead>
                <th>Producto</th>
                <th>Descripcion</th>
                <th>Categoria</th>
                <th>Cantidad</th>
                <th>Despachado</th>
            </thead>
            <tbody style="font-size: 14px;">
                @foreach ($listadoProductos as $listado)
                <tr>
                    <td>{{ $listado['producto'] }}</td>
                    <td>{{ $listado['descripcion'] }}</td>
                    <td>{{ $listado['categoria'] }}</td>
                    <td align="center">{{ $listado['cantidad'] }}</td>
                    <td align="center">{{ $listado['despachado'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{-- Dato recibido desde el controlador --}}

    @php
    if ( isset($pdf) ) {
        $pdf->page_script('
        $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
        $pdf->text(0, 0, "Pagina $PAGE_NUM de $PAGE_COUNT", $font, 10);
        ');
    }
    @endphp


    </div>

    <footer>
        <table style="width: 100%;">
            <tr>
                <td>hola</td>
                <td>
                    <div style="margin: 0%;" align="center">JP Construcred C. Ltda.</div>
                    <div style="margin: 0%;" align="center">Reporte Generado por el Usuario:
                        {{ auth('sanctum')->user()->empleado->nombres }}
                        {{ auth('sanctum')->user()->empleado->apellidos }} el
                        {{ $fecha->format('d/m/Y H:i') }}
                    </div>
                </td>
                <td>
                    <div color="#000000"><img src="data:image/svg;base64,{!! base64_encode(
        QrCode::format('svg')->size(70)->generate('Hola, soy un qr en un PDF'),
    ) !!}"></div>
                </td>
            </tr>
        </table>


    </footer>
</body>

</html>