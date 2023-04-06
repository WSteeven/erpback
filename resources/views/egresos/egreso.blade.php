<!DOCTYPE html>
<html lang="es">

@php
    $fecha = new Datetime();
    $mensaje_qr = 'JP CONSTRUCRED C. LTDA.' . PHP_EOL . 'TRANSACCION: ' . $transaccion['id'] . PHP_EOL . 'EGRESO: ' . $transaccion['motivo'] . PHP_EOL . 'TAREA: ' . $transaccion['tarea_codigo'] . PHP_EOL . 'SOLICITADO POR: ' . $transaccion['solicitante'] . PHP_EOL . 'AUTORIZADO POR: ' . $transaccion['per_autoriza'] . PHP_EOL . 'BODEGA DE CLIENTE: ' . $transaccion['cliente'] . PHP_EOL . 'SUCURSAL: ' . $transaccion['sucursal'];
    $logo = 'data:image/png;base64,' . base64_encode(file_get_contents('img/logoJP.png'));
    if ($persona_entrega->firma_url) {
        $entrega_firma = 'data:image/png;base64,' . base64_encode(file_get_contents(substr($persona_entrega->firma_url, 1)));
    }
    if ($persona_retira->firma_url) {
        $retira_firma = 'data:image/png;base64,' . base64_encode(file_get_contents(substr($persona_retira->firma_url, 1)));
    }
@endphp

<head>
    <meta charset="utf-8">
    <title>Comprobante N° {{ $transaccion['id'] }}</title>
    <style>
        @page {
            margin: 2px 15px 5px 15px;
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
                <td style="width: 10%;">
                    <div class="col-md-3"><img src="{{ $logo }}" width="90"></div>
                </td>
                <td style="width: 68%">
                    @if ($transaccion['transferencia'])
                        <div class="col-md-7" align="center"><b>COMPROBANTE DE TRANSFERENCIA</b></div>
                    @else
                        <div class="col-md-7" align="center"><b>COMPROBANTE DE EGRESO</b></div>
                    @endif
                </td>
                <td style="width: 22%;">
                    <div class="col-md-2" align="right" style="font-size: 15px">Sistema de bodega</div>
                </td>
            </tr>
        </table>
        <hr>
    </header>
    <footer>
        <table class="firma" style="width: 100%;">
            <thead>
                <th align="center">
                    @isset($entrega_firma)
                        <img src="{{ $entrega_firma }}" alt="" width="100%" height="40">
                    @endisset
                    @empty($entrega_firma)
                        ___________________<br />
                    @endempty
                    <b>ENTREGA</b>
                </th>
                <th align="center"></th>
                <th align="center">
                    @if ($transaccion['firmada'])
                        @isset($retira_firma)
                            <img src="{{ $retira_firma }}" alt="" width="100%" height="40">
                        @endisset
                    @endif
                    @empty($retira_firma)
                        ___________________<br />
                    @endempty
                    <b>RECIBE</b>
                </th>
            </thead>
            <tbody>
                <tr align="center">
                    <td>{{ $persona_entrega->nombres }} {{ $persona_entrega->apellidos }} <br>
                        {{ $persona_entrega->identificacion }}
                    </td>
                    <td></td>
                    <td>
                        @if ($transaccion['responsable_id'])
                            {{ $persona_retira->nombres }} {{ $persona_retira->apellidos }} <br>
                            {{ $persona_retira->identificacion }}
                        @else
                            Nombre:
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>
        <table style="width: 100%;">
            <tr>
                <td class="page">Página </td>
                <td style="line-height: normal;">
                    <div style="margin: 0%; margin-bottom: 0px; margin-top: 0px;" align="center">JP CONSTRUCRED C. LTDA.
                    </div>
                    <div style="margin: 0%; margin-bottom: 0px; margin-top: 0px;" align="center">GENERADO POR:
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
                <td>Transacción N°: <b>{{ $transaccion['id'] }}</b></td>
                <td>Fecha: <b>{{ $transaccion['created_at'] }}</b></td>
                <td>Solicitante: <b>{{ $transaccion['solicitante'] }}</b></td>
            </tr>
            <tr class="row">
                <td>Justificación: <b>{{ $transaccion['justificacion'] }}</b></td>
                <td></td>
                <td>Sucursal: <b>{{ $transaccion['sucursal'] }}</b></td>
            </tr>
            <tr class="row">
                <td>Autorizado por: <b>{{ $transaccion['per_autoriza'] }}</b></td>
                <td></td>
                <td>Estado: <b>{{ $transaccion['estado'] }}</b></td>
            </tr>
            <tr class="row">
                <td>Cliente: <b>{{ $transaccion['cliente'] }}</b></td>
                <td></td>
                <td>Motivo: <b>{{ $transaccion['motivo'] }}</b></td>
            </tr>
        </table>
        <table style="width: 100%; border: #000000; border-collapse: collapse;" border="0">

            @if ($transaccion['tarea'])
                <tr class="row">
                    <td style="width: 65%">Tarea: <b>{{ $transaccion['tarea'] }}</b></td>
                    <td style="width: 35%">Cod. Tarea: <b>{{ $transaccion['tarea_codigo'] }}</b></td>
                </tr>
            @endif

        </table>
        <!-- aqui va el listado de productos -->
        <table border="1" style="border-collapse: collapse; margin-bottom:4px; width: 100%;" align="center">
            <thead>
                <th>Producto</th>
                <th>Descripcion</th>
                <th>Categoria</th>
                <th>Despachado</th>
            </thead>
            <tbody style="font-size: 14px;">
                {{-- @foreach ($ciclo as $c) --}}
                @foreach ($transaccion['listadoProductosTransaccion'] as $listado)
                    <tr>
                        <td>{{ $listado['producto'] }}</td>
                        <td>{{ $listado['descripcion'] }}</td>
                        <td>{{$listado['categoria']}}</td>
                        <td align="center">{{ $listado['cantidad'] }}</td>
                    </tr>
                @endforeach
                {{-- @endforeach --}}
            </tbody>
        </table>
    </main>
</body>

</html>
