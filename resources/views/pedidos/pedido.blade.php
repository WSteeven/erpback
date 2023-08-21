<!DOCTYPE html>
<html lang="es">
    {{-- Aquí codigo PHP --}}
@php
    $fecha = new Datetime();
    $mensaje_qr = 'JP CONSTRUCRED C. LTDA.' . PHP_EOL . 'PEDIDO: ' . $id . PHP_EOL . 'SOLICITADO POR: ' . $solicitante . PHP_EOL . 'AUTORIZADO POR: ' . $per_autoriza . PHP_EOL . 'RESPONSABLE: ' . $responsable . PHP_EOL . 'SUCURSAL: ' . $sucursal . PHP_EOL . 'ESTADO DEL DESPACHO: ' . $estado . PHP_EOL . 'ULTIMA MODIFICACION: ' . $updated_at;
    $logo_principal = 'data:image/png;base64,' . base64_encode(file_get_contents('img/logo.png'));
    $logo_watermark = 'data:image/png;base64,' . base64_encode(file_get_contents('img/logoBN10.png'));
@endphp

<head>
    <meta charset="utf-8">
    <title>Pedido N° {{ $id }}</title>
    <style>
        @page {
            margin: 0cm 15px;
        }

        body {
            background-image: url('img/logoBN10.png');
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
            top: 80px;
            left: 0cm;
            right: 0cm;
            margin-bottom: 7cm;
            font-size: 13px;
        }

        div {
            color: #000000 !important;
        }

        h1 {
            text-align: center;
            text-transform: uppercase;
        }

        .firma {
            table-layout: fixed;
            width: 100%;
            line-height: normal;
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
            <tr class="row" style="width:auto">
                <td>
                    <div class="col-md-3"><img src="{{ $logo_principal }}" width="90"></div>
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
    <footer>
        <table class="firma" style="width: 100%;">
            <thead>
                <th align="center">___________________</th>
                <th align="center"></th>
                <th align="center">___________________</th>
            </thead>
            <tbody>
                <tr align="center">
                    <td><b>ENTREGA</b></td>
                    <td><b></b></td>
                    <td><b>RECIBE</b></td>
                </tr>
                <tr>
                    <td style="padding-left: 60px;">Nombre: </td>
                    <td></td>
                    <td style="padding-left: 60px;">Nombre:</td>
                </tr>
                <tr>
                    <td style="padding-left: 60px;">C.I: </td>
                    <td></td>
                    <td style="padding-left: 60px;">C.I:</td>
                </tr>
            </tbody>
        </table>
        <table style="width: 100%;">
            <tr>
                <td class="page">Página </td>
                <td style="line-height: normal;">
                    <div style="margin: 0%; margin-bottom: 0px; margin-top: 0px;" align="center">JP Construcred C. Ltda.
                    </div>
                    <div style="margin: 0%; margin-bottom: 0px; margin-top: 0px;" align="center">Generado por el
                        Usuario:
                        {{ auth('sanctum')->user()->empleado->nombres }}
                        {{ auth('sanctum')->user()->empleado->apellidos }} el
                        {{ $fecha->format('d/m/Y H:i') }}
                    </div>
                </td>
                <td>
                    <div align="right"><img src="data:image/svg;base64,{!! base64_encode(
                        QrCode::format('svg')->encoding('UTF-8')->size(70)->generate($mensaje_qr),
                    ) !!}"></div>
                </td>
            </tr>
        </table>
    </footer>
    <!-- aqui va el contenido del document<br><br>o -->
    <main>
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
                @if ($tarea)
                    <tr>
                        <td>Tarea: <b>{{ $tarea }}</b></td>
                    </tr>
                @endif
            </thead>
        </table>
        <!-- aqui va el listado de productos -->
        <table border="1" style="border-collapse: collapse; margin-bottom:4px; width: 98%;" align="center">
            <thead>
                <th>Producto</th>
                <th>Descripcion</th>
                <th>Categoria</th>
                <th>Serie</th>
                <th>Cantidad</th>
                <th>Despachado</th>
            </thead>
            <tbody style="font-size: 14px;">
                @foreach ($listadoProductos as $listado)
                    <tr>
                        <td>{{ $listado['producto'] }}</td>
                        <td>{{ $listado['descripcion'] }}</td>
                        <td>{{ $listado['categoria'] }}</td>
                        <td>{{ $listado['serial'] }}</td>
                        <td align="center">{{ $listado['cantidad'] }}</td>
                        <td align="center">{{ $listado['despachado'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </main>


</body>

</html>
