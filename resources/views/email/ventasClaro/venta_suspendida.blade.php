<!DOCTYPE html>
<html lang="en">
@php
    $logo_principal = 'data:image/png;base64,' . base64_encode(file_get_contents(public_path() . $configuracion['logo_claro']));
    $logo_watermark = 'data:image/png;base64,' . base64_encode(file_get_contents(public_path() . $configuracion['logo_marca_agua']));
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Venta suspendida</title>
    <style>
        @page {
            margin: 0cm 15px;
        }

        header {
            top: 0cm;
            left: 0cm;
            right: 0cm;
            height: 4cm;

            /** Estilos extra personales **/
            text-align: center;
            line-height: 42%;
        }

        body {
            background-image: url({{ $logo_watermark }});
            background-repeat: no-repeat;
            background-position: center;
            background-size: contain;
        }

        /** Definir las reglas del encabezado **/




        div {
            color: #000000 !important;
        }

        h1 {
            text-align: center;
            text-transform: uppercase;
        }

        /* Estilo para la tabla con clase "cuerpo" */
        table.cuerpo {
            border: #b2b2b200 1px solid;
            font-size: 10pt;
            margin-top: 1.05cm;

        }

        .cuerpo td,
        .cuerpo th {
            border: black 1px solid;
        }

        table.descripcion {
            width: 100%;
        }

        .descripcion td,
        descripcion th {
            border: none;
        }

        .subtitulo-rol {
            text-align: center;
        }

        .encabezado-rol {
            text-align: center;
        }

        .encabezado-tabla-rol {
            text-align: center;
        }

        .totales {
            text-align: right;
        }

        /** Definir las reglas del pie de página **/
        footer {
            position: fixed;
            bottom: 90px;
            font-size: 7pt;
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

        .firma {
            table-layout: fixed;
            width: 100%;
            line-height: normal;
            font-size: 7pt;
            padding-top: 7%;
        }


        .row {
            width: 100%;
        }
    </style>
</head>

<body>
    <h2>JP CONSTRUCTRED C.LTDA.</h2>
    <img src="{{ $logo_principal }}" alt="logo" width="100" height="100" />
    <h2> Estimado Vendedor, {{ auth('sanctum')->user()->empleado->nombres }}
        {{ auth('sanctum')->user()->empleado->apellidos }} ha marcado una venta como suspendida.</h2>
    <p>Se requiere realice la gestión de cobranza respectiva y adjuntar su evidencia en novedades de la venta realizada.
    </p>
    {{-- Detalles de la venta --}}
    <p>Detalles de la venta: </p>
    <main>
        <table align="center" class="cuerpo">
            <tr>
                <td><strong>N° VENTA</strong></td>
                <td><strong>N° ORDEN</strong></td>
                <td><strong>CLIENTE</strong></td>
                <td><strong>PRODUCTO</strong></td>
                <td><strong>FECHA ACTIVACION</strong></td>
                <td><strong>ESTADO</strong></td>
                <td><strong>FORMA DE PAGO</strong></td>
                <td><strong>PRIMER MES PAGADO</strong></td>
                <td><strong>FECHA DE SUSPENSION</strong></td>
            </tr>
            <tr>
                <td>{{ $venta->id }}</td>
                <td>{{ $venta->orden_id }}</td>
                <td>{{ $venta->cliente->nombres }} {{ $venta->cliente->apellidos }}</td>
                <td>{{ $venta->producto->bundle_id }} - {{ $venta->producto->nombre }}</td>
                <td>{{ $venta->fecha_activacion }}</td>
                <td>{{ $venta->estado_activacion }}</td>
                <td>{{ $venta->forma_pago }}</td>
                <td>{{ $venta->primer_mes ? 'SI' : 'NO' }}</td>
                <td>{{ $venta->updated_at }}</td>
            </tr>
        </table>
        {{-- Detalles del cliente --}}
        <p>Detalles del cliente: </p>
        <table align="center" class="cuerpo">
            <tr>
                <td><strong>IDENTIFICACION</strong></td>
                <td><strong>NOMBRES Y APELLIDOS</strong></td>
                <td><strong>DIRECCION</strong></td>
                <td><strong>TELEFONOS</strong></td>
            </tr>
            <tr>
                <td>{{ $venta->cliente->identificacion }} </td>
                <td>{{ $venta->cliente->nombres }} {{ $venta->cliente->apellidos }}</td>
                <td>{{ $venta->cliente->direccion }} </td>
                <td>{{ $venta->cliente->telefono1 }} {{ $venta->cliente->telefono2 }}</td>
            </tr>
        </table>

        <p>Este correo se generó automaticamente, por favor no lo responda.</p>
    </main>

</body>

</html>
