<!DOCTYPE html>
<html lang="es">
{{-- Aquí codigo PHP --}}
@php
    use Src\Shared\Utils;

    $fecha = new Datetime();
    $mensaje_qr = $configuracion['razon_social'] . PHP_EOL . 'PEDIDO: ' . $pedido['id'] . PHP_EOL . 'SOLICITADO POR: ' . $pedido['solicitante'] . PHP_EOL . 'AUTORIZADO POR: ' . $pedido['per_autoriza'] . PHP_EOL . 'RESPONSABLE: ' . $pedido['responsable'] . PHP_EOL . 'SUCURSAL: ' . $pedido['sucursal'] . PHP_EOL . 'ESTADO DEL DESPACHO: ' . $pedido['estado'] . PHP_EOL . 'ULTIMA MODIFICACION: ' . $pedido['updated_at'];

@endphp

<head>
    <meta charset="utf-8">
    <title>Pedido N° {{ $pedido['id'] }}</title>
    <style>
        @page {
            margin: 0 15px;
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
            bottom: 93px;
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
                <div class="col-md-3">
                    <img src="{{ Utils::urlToBase64(url($configuracion->logo_claro)) }}" width="90" alt="Logo">
                </div>
            </td>
            <td>
                <div class="col-md-7" align="center"><b>COMPROBANTE DE PEDIDO</b></div>
            </td>
            <td>
                {{-- <div class="col-md-2" align="right">Sistema de bodega {{$configuracion['ruc']}}</div> --}}
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
            <td style="padding-left: 60px;">Nombre:</td>
            <td></td>
            <td style="padding-left: 60px;">Nombre:</td>
        </tr>
        <tr>
            <td style="padding-left: 60px;">C.I:</td>
            <td></td>
            <td style="padding-left: 60px;">C.I:</td>
        </tr>
        </tbody>
    </table>
    <table style="width: 100%;">
        <tr>
            <td class="page">Página</td>
            <td style="line-height: normal;">
                <div style="margin: 0%; margin-bottom: 0px; margin-top: 0px;" align="center">
                    {{ $configuracion['razon_social'] }}</div>
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
                    ) !!}" alt="QR Pedido"></div>
            </td>
        </tr>
    </table>
</footer>
<!-- aqui va el contenido del document<br><br>o -->
<main>
    <table style="width: 100%; border: #000000; border-collapse: collapse;" border="0">
        <tr class="row">
            <td>Transacción N°: <b>{{ $pedido['id'] }}</b></td>
            <td>Fecha: <b>{{ $pedido['created_at'] }}</b></td>
            <td>Solicitante: <b>{{ $pedido['solicitante'] }}</b></td>
        </tr>
        <tr class="row">
            <td>Justificación: <b>{{ $pedido['justificacion'] }}</b></td>
            <td></td>
            <td>Sucursal: <b>{{ $pedido['sucursal'] }}</b></td>
        </tr>
        <tr class="row">
            <td>Autorizado por: <b>{{ $pedido['per_autoriza'] }}</b></td>
            <td></td>
            <td>Estado: <b>{{ $pedido['estado'] }}</b></td>
        </tr>
    </table>
    <table>
        <thead style="margin-bottom:4px;">
        @if ($pedido['tarea'])
            <tr>
                <td>Tarea: <b>{{ $pedido['tarea'] }}</b></td>
            </tr>
        @endif
        <tr>
            <td>Responsable: <b>{{ $pedido['responsable'] }}</b></td>
        </tr>
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
        @foreach ($pedido['listadoProductos'] as $listado)
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
