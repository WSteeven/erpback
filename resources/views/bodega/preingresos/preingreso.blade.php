<!DOCTYPE html>
<html lang="es">
{{-- Aquí codigo PHP --}}
@php
    $fecha = new Datetime();
    $mensaje_qr =
        $configuracion['razon_social'] .
        PHP_EOL .
        'PREINGRESO: ' .
        $preingreso['id'] .
        PHP_EOL .
        'AUTORIZADO POR: ' .
        $preingreso['autorizador'] .
        PHP_EOL .
        'RESPONSABLE: ' .
        $preingreso['responsable'] .
        PHP_EOL .
        'ESTADO DE AUTORIZACION: ' .
        $preingreso['autorizacion'] .
        PHP_EOL .
        'ULTIMA MODIFICACION: ' .
        $preingreso['updated_at'];
    $logo_principal =
        'data:image/png;base64,' . base64_encode(file_get_contents(public_path() . $configuracion['logo_claro']));
    $logo_watermark =
        'data:image/png;base64,' . base64_encode(file_get_contents(public_path() . $configuracion['logo_marca_agua']));

    if ($persona_responsable->firma_url) {
        $responsable_firma =
            'data:image/png;base64,' . base64_encode(file_get_contents(substr($persona_responsable->firma_url, 1)));
    }
    if ($persona_autoriza->firma_url) {
        $autoriza_firma =
            'data:image/png;base64,' . base64_encode(file_get_contents(substr($persona_autoriza->firma_url, 1)));
    }

@endphp

<head>
    <meta charset="utf-8">
    <title>Preingreso N° {{ $preingreso['id'] }}</title>
    <style>
        @page {
            margin: 2px 15px 5px 15px;
        }

        body {
            background-image: url({{ $logo_watermark }});
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
                <td>
                    <div class="col-md-3"><img src="{{ $logo_principal }}" width="90"></div>
                </td>
                <td>
                    <div class="col-md-7" align="center" style="font-size: 14px"><b>COMPROBANTE DE PREINGRESO</b></div>
                </td>
                <td>
                    {{-- <div class="col-md-2" align="right">Sistema de bodega {{$configuracion['ruc']}}</div> --}}
                    <div class="col-md-2" align="right">Sistema de bodega </div>
                </td>
            </tr>
        </table>
        <hr>
    </header>
    <footer>
        <table class="firma" style="width: 100%;">
            <thead>
                <th align="center">
                    @isset($responsable_firma)
                        <img src="{{ $responsable_firma }}" alt="" width="100%" height="40">
                    @endisset
                    @empty($responsable_firma)
                        ___________________<br />
                    @endempty
                    <b>RESPONSABLE</b>
                </th>
                <th align="center"></th>
                <th align="center">
                    @isset($autoriza_firma)
                        <img src="{{ $autoriza_firma }}" alt="" width="100%" height="40">
                    @endisset
                    @empty($autoriza_firma)
                        ___________________<br />
                    @endempty
                    <b>AUTORIZADOR</b>
                </th>
            </thead>
            <tbody>
                <tr align="center">
                    <td>
                        {{ $persona_responsable->nombres }} {{ $persona_responsable->apellidos }} <br>
                        {{ $persona_responsable->identificacion }} </td>
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
                    <div align="right"><img src="data:image/svg;base64,{!! base64_encode(QrCode::format('svg')->encoding('UTF-8')->size(70)->generate($mensaje_qr)) !!}"></div>
                </td>
            </tr>
        </table>
    </footer>
    <!-- aqui va el contenido del document<br><br>o -->
    <main>
        <table style="width: 100%; border: #000000; border-collapse: collapse;" border="0">
            <tr class="row">
                <td>Preingreso N°: <b>{{ $preingreso['id'] }}</b></td>
                <td>Fecha: <b>{{ $preingreso['created_at'] }}</b></td>
            </tr>
            <tr class="row">
                @if (!is_null($preingreso['solicitante']) && strlen($preingreso['solicitante']) > 5)
                    <td>Solicitante: <b>{{ $preingreso['solicitante'] }}</b></td>
                @endif
                <td></td>
                <td>Responsable: <b>{{ $preingreso['responsable'] }}</b></td>
            </tr>
            <tr class="row">
                <td>Observación: <b>{{ $preingreso['observacion'] }}</b></td>
                <td></td>
                <td>Cuadrilla: <b>{{ $preingreso['cuadrilla'] }}</b></td>
            </tr>
            <tr class="row">
                <td>Autorizado por: <b>{{ $preingreso['autorizador'] }}</b></td>
                <td></td>
                <td>Estado: <b>{{ $preingreso['autorizacion'] }}</b></td>
            </tr>
            <tr class="row">
                <td>Coordinador Responsable: <b>{{ $preingreso['coordinador'] }}</b></td>
                <td></td>
                <td>Cliente: <b>{{ $preingreso['cliente'] }}</b></td>
            </tr>
            <tr class="row">
                <td>Destino del material:
                    <b>{{ $preingreso['tarea'] ? 'TAREA ' . $preingreso['codigo_tarea'] : 'STOCK PERSONAL' }}</b></td>
            </tr>
        </table>
        <table>
            <thead style="margin-bottom:4px;">
                @if ($preingreso['tarea'])
                    <tr>
                        <td>Tarea: <b>{{ $preingreso['tarea'] }}</b></td>
                    </tr>
                @endif
            </thead>
        </table>
        <!-- aqui va el listado de productos -->
        <table border="1" style="border-collapse: collapse; margin-bottom:4px; width: 98%;" align="center">
            <thead>
                <th>Producto</th>
                <th>Descripcion</th>
                <th>Serie</th>
                <th>Cantidad</th>
                <th>P. Inicio</th>
                <th>P. Fin</th>

            </thead>
            <tbody style="font-size: 14px;">
                @foreach ($preingreso['listadoProductos'] as $listado)
                    <tr>
                        <td>{{ $listado['producto'] }}</td>
                        <td>{{ $listado['descripcion'] }}</td>
                        <td>{{ $listado['serial'] }}</td>
                        <td align="center">{{ $listado['cantidad'] }}</td>
                        <td align="center">{{ $listado['punta_inicial'] }}</td>
                        <td align="center">{{ $listado['punta_final'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </main>


</body>

</html>
