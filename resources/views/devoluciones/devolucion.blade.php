<!DOCTYPE html>
<html lang="es">
@php
use Src\Shared\Utils;
    $fecha = new Datetime();
    $mensaje_qr = 'JP CONSTRUCRED C. LTDA.' . PHP_EOL . 'DEVOLUCION: ' . $devolucion['id'] . PHP_EOL . 'SOLICITADO POR: ' . $devolucion['solicitante'] . PHP_EOL . 'SUCURSAL: ' . $devolucion['sucursal'] . PHP_EOL . 'PERSONA QUE AUTORIZA: ' . $devolucion['per_autoriza']. PHP_EOL . 'AUTORIZACION: ' . $devolucion['autorizacion']. PHP_EOL . 'ULTIMA MODIFICACION: ' . $devolucion['updated_at'];
    if ($persona_solicitante->firma_url) {
        $solicitante_firma = Utils::urlToBase64(url($persona_solicitante->firma_url));
    }
    if ($persona_autoriza->firma_url) {
        $autoriza_firma = Utils::urlToBase64(url($persona_autoriza->firma_url));
    }
@endphp


@endphp

<head>
    <meta charset="utf-8">
    <title>Devolución N° {{ $devolucion['id'] }}</title>
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
            top: 80px;
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

        .firma {
            table-layout: fixed;
            width: 100%;
            line-height: normal;
            font-size: 10px;
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
                <td style="width: 10%">
                    <div class="col-md-3"><img src="{{ Utils::urlToBase64( url($configuracion->logo_claro)) }}" width="90"></div>
                </td>
                <td style="width: 68%">
                    <div class="col-md-7" align="center"><b>COMPROBANTE DE DEVOLUCION</b></div>
                </td>
                <td style="width: 22%">
                    <div class="col-md-2" align="right">Sistema de bodega</div>
                </td>
            </tr>
        </table>
        <hr>
    </header>
    <footer>
        <table class="firma" style="width: 100%;">
            <thead>
                <th align="center">
                    @isset($solicitante_firma)
                        <img src="{{ $solicitante_firma }}" alt="" width="100%" height="40">
                    @endisset
                    @empty($solicitante_firma)
                        ___________________<br />
                    @endempty
                    <b>SOLICITANTE</b>
                </th>
                <th align="center"></th>
                <th align="center">
                    @if ($devolucion['autorizacion'] == 'APROBADO')
                        @isset($autoriza_firma)
                            <img src="{{ $autoriza_firma }}" alt="" width="100%" height="40">
                        @endisset
                    @else
                    <br /><br />___________________<br />
                    @endif
                    @empty($autoriza_firma)
                        ___________________<br />
                    @endempty
                    <b>AUTORIZADOR</b>
                </th>
            </thead>
            <tbody>
                <tr align="center">
                    <td>
                        {{ $persona_solicitante->nombres }} {{ $persona_solicitante->apellidos }} <br>
                        {{ $persona_solicitante->identificacion }} </td>
                    <td></td>
                    <td>
                        {{ $persona_autoriza->nombres }} {{ $persona_autoriza->apellidos }} <br>
                        {{ $persona_autoriza->identificacion }}
                    </td>
                </tr>
            </tbody>
        </table>
        <table style="width: 100%;">
            <tr>
                <td class="page">Página </td>
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
                    ) !!}"></div>
                </td>
            </tr>
        </table>
    </footer>
    <!-- aqui va el contenido del document<br><br>o -->
    <main>
        <table style="width: 100%; border: #000000; border-collapse: collapse;" border="0">
            <tr class="row">
                <td>Devolución N°: <b>{{ $devolucion['id'] }}</b></td>
                <td>Fecha: <b>{{ $devolucion['created_at'] }}</b></td>
            </tr>
            <tr>
                <td>Solicitante: <b>{{ $devolucion['solicitante'] }}</b></td>
                <td>Sucursal: <b>{{ $devolucion['sucursal'] }}</b></td>
            </tr>
            <tr>
                <td>Autorizacion: <b>{{ $devolucion['autorizacion'] }}</b></td>
                <td>Estado bodega: <b>{{ $devolucion['estado_bodega'] }}</b></td>
            </tr>
        </table>
        <table>
            <thead style="margin-bottom:4px;">
                <tr class="row">
                    <td>Justificación: <b>{{ Str::upper($devolucion['justificacion']) }}</b></td>
                </tr>
            </thead>
        </table>
        <!-- aqui va el listado de productos -->
        <table border="1" style="border-collapse: collapse; margin-bottom:4px; width: 98%;" align="center">
            <thead>
                <th>Producto</th>
                <th>Descripcion</th>
                <th>Categoria</th>
                <th>Cantidad</th>
                <th>Condición</th>
                <th>Observación</th>
            </thead>
            <tbody style="font-size: 10px;">
                @foreach ($devolucion['listadoProductos'] as $listado)
                    <tr>
                        <td>{{ $listado['producto'] }}</td>
                        <td>{{ $listado['descripcion'] }}</td>
                        <td>{{ $listado['categoria'] }}</td>
                        <td align="center">{{ $listado['cantidad'] }}</td>
                        <td>{{ $listado['condiciones'] }}</td>
                        <td>{{ Str::upper($listado['observacion']) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </main>


</body>

</html>
