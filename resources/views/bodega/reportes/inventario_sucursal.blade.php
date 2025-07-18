<!DOCTYPE html>
<html lang="es">
@php
    use Src\Shared\Utils;

    $fecha = new Datetime();
@endphp

<head>
    <meta charset="utf-8">
    <title>Reporte de inventario en la {{ $reporte[0]['sucursal'] }}</title>
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
            bottom: 0px;
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
            margin-bottom: 5cm;
            font-size: 15px;
            /* margin-bottom: 100px; */
            /* text-transform: uppercase; */
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
                <div class="col-md-3"><img src="{{ Utils::urlToBase64(url($configuracion->logo_claro)) }}" width="90" alt="logo">
                </div>
            </td>
            <td>
                <div class="col-md-7" align="center"><b>REPORTE DE INVENTARIO - {{ $fecha->format('d-m-Y') }}</b>
                </div>
            </td>
            <td>
                <div class="col-md-2" align="right">Sistema de Bodega</div>
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
                    propiedad de {{$configuracion['razon_social']}} <br>Prohibida su divulgación
                </div>
                <div style="margin: 0%; margin-bottom: 0px; margin-top: 0px;" align="center">Generado por:
                    {{ auth('sanctum')->user()->empleado->nombres }}
                    {{ auth('sanctum')->user()->empleado->apellidos }} el
                    {{ $fecha->format('d-m-Y H:i') }}
                </div>
            </td>
            <td>
            </td>
        </tr>
    </table>
</footer>
<!-- aqui va el contenido del document<br><br>o -->
<main>
    <table border="1" style="border-collapse:collapse; margin-bottom: 4px; width: 100%" align="center">
        <thead style="margin-bottom:4px;">
        <th>Id</th>
        <th>Producto</th>
        <th>Descripción</th>
        <th>Categoria</th>
        <th>Cliente</th>
        <th>Serial</th>
        <th>Sucursal</th>
        <th>Condiciones</th>
        <th>Por recibir</th>
        <th>Cantidad</th>
        <th>Por entregar</th>
        </thead>
        <tbody style="font-size: 8px">
        @foreach ($reporte as $rpt)
            <tr>
                <td>{{ $rpt['id'] }}</td>
                <td>{{ $rpt['producto'] }}</td>
                <td>{{ $rpt['descripcion'] }}</td>
                <td>{{ $rpt['categoria'] }}</td>
                <td>{{ $rpt['cliente'] }}</td>
                <td>{{ $rpt['serial'] }}</td>
                <td>{{ $rpt['sucursal'] }}</td>
                <td>{{ $rpt['condiciones'] }}</td>
                <td align="center">{{ $rpt['por_recibir'] }}</td>
                <td align="center">{{ $rpt['cantidad'] }}</td>
                <td align="center">{{ $rpt['por_entregar'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <!-- aqui va el listado de productos -->

</main>


</body>

</html>
