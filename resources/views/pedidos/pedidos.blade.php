<!DOCTYPE html>
<html lang="es">
{{-- Aquí codigo PHP --}}
@php
    use Src\Shared\Utils;
    $fecha = new Datetime();
@endphp

<head>
    <meta charset="utf-8">
    <title>Reporte de pedidos</title>
    <style>
        @page {
            margin: 0cm 15px;
        }

        header {
            top: 0cm;
            left: 0cm;
            right: 0cm;
            height: 2.5cm;

            /** Estilos extra personales **/
            text-align: center;
            line-height: 42%;
        }

        body {
            background-image: url({{ Utils::urlToBase64(url($configuracion->logo_marca_agua)) }});
            background-repeat: no-repeat;
            background-position: center;
            background-size: contain;
        }

        /** Definir las reglas del pie de página **/
        footer {
            position: fixed;
            bottom: 5px;
            font-size: 10pt;
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
            margin-top: 0%;
            top: 0px;
            left: 0cm;
            right: 0cm;
            /* margin-bottom: 5cm; */
            font-size: 12px;
        }

        div {
            color: #000000 !important;
        }

        h1 {
            text-align: center;
            text-transform: uppercase;
        }

        table.cuerpo {
            border: #b2b2b200 1px solid;
            font-size: 10pt;
            margin-top: -1.05cm;

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

        .row {
            width: 100%;
        }
    </style>
</head>


<body>
<header>
    <table
        style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10pt;">
        <tr class="row" style="width:auto">
            <td>
                <div class="col-md-3"><img src="{{ Utils::urlToBase64(url($configuracion->logo_claro)) }}" width="90" alt="logo">
                </div>
            </td>
            <td>
                <p class="encabezado-rol"><strong>{{ $configuracion['razon_social'] }}</strong></p>
                <p class="encabezado-rol"><strong>RUC {{ $configuracion['ruc'] }}</strong></p>
                <div class="encabezado-rol"><b>REPORTE DE PEDIDOS </b></div>
            </td>
            <td>
                {{-- Columna vacia --}}
            </td>
        </tr>
    </table>
    <hr>
</header>
<footer>
    <table style="width: 100%;">
        <tr>
            <td class="page">Página</td>
            <td style="line-height: normal;">
                <div style="margin: 0%; margin-bottom: 6px; margin-top: 0px;" align="center">Esta información es
                    propiedad de {{ $configuracion['razon_social'] }} <br>Válida únicamente para fines autorizados.
                </div>
                <div style="margin: 0%; margin-bottom: 0px; margin-top: 0px;" align="center">Generado por:
                    {{ auth('sanctum')->user()->empleado->nombres }}
                    {{ auth('sanctum')->user()->empleado->apellidos }} el
                    {{ $fecha->format('d-m-Y H:i') }}
                </div>
            </td>
            <td></td>
        </tr>
    </table>
</footer>

<!-- aqui va el contenido del document<br><br>o -->
<main>
    <table border="1" style="border-collapse:collapse; margin-bottom: 4px; width: 100%" align="center">
        <thead>
        <td class="encabezado-tabla-rol"><strong>ID PEDIDO</strong></td>
        <td class="encabezado-tabla-rol"><strong> FECHA</strong></td>
        <td class="encabezado-tabla-rol"><strong>JUSTIFICACION</strong></td>
        <td class="encabezado-tabla-rol"><strong>DESCRIPCION</strong></td>
        <td class="encabezado-tabla-rol"><strong>SERIAL</strong></td>
        <td class="encabezado-tabla-rol"><strong>CATEGORIA</strong></td>
        <td class="encabezado-tabla-rol"><strong>CANTIDAD</strong></td>
        <td class="encabezado-tabla-rol"><strong>DESPACHADO</strong></td>
        <td class="encabezado-tabla-rol"><strong>SOLICITANTE</strong></td>
        <td class="encabezado-tabla-rol"><strong>AUTORIZACION</strong></td>
        <td class="encabezado-tabla-rol"><strong>AUTORIZADOR</strong></td>
        <td class="encabezado-tabla-rol"><strong>ESTADO</strong></td>
        <td class="encabezado-tabla-rol"><strong>RESPONSABLE</strong></td>
        </thead>
        <tbody>
        @foreach ($reporte as $rpt)
            <tr>
                <td align="center">{{ $rpt['pedido_id'] }}</td>
                <td>{{ $rpt['created_at'] }}</td>
                <td>{{ $rpt['justificacion'] }}</td>
                <td>{{ $rpt['descripcion'] }}</td>
                <td>{{ $rpt['serial'] }}</td>
                <td align="center">{{ $rpt['categoria'] }}</td>
                <td align="center">{{ $rpt['cantidad'] }}</td>
                <td align="center">{{ $rpt['despachado'] }}</td>
                <td>{{ $rpt['solicitante'] }}</td>
                <td align="center">{{ $rpt['autorizacion'] }}</td>
                <td>{{ $rpt['autorizador'] }}</td>
                <td>{{ $rpt['estado'] }}</td>
                <td>{{ $rpt['responsable'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</main>


</body>

</html>
