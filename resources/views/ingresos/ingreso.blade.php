<!DOCTYPE html>
<html lang="es" >

<head>
<meta charset="utf-8">
    <title>Comprobante N° {{ $id }}</title>
    <style>
        @page {
            margin: 2px 15px 5px 15px;
        }

        body {
            /* background-image: url('img/logoJPBN_10.png'); */
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
            /* position: inherit; */
            /* top: 140px; */
        }


        .row {
            width: 100%;
        }
    </style>
</head>
@php
$fecha = new Datetime();
$qr = QrCode::size(100)
->backgroundColor(255, 90, 0)
->generate('Hola a todos, saludos cordiales');
$mensaje_qr='JP CONSTRUCRED C. LTDA.'.PHP_EOL.'TRANSACCION: '.$id.PHP_EOL.'INGRESO: '.$motivo.PHP_EOL.'SOLICITADO POR: '.$solicitante.PHP_EOL.'AUTORIZADO POR: '.$per_autoriza.PHP_EOL.'BODEGA DE CLIENTE: '.$cliente.PHP_EOL.'SUCURSAL: '.$sucursal;
$ciclo = [1,2,3,4,5,6,7,8,9,0,1,2,3,4,5];
@endphp

<body>
    <header>
        <table style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:18px;">
            <tr class="row" style="width:auto">
                <td style="width: 10%;">
                    <div class="col-md-3"><img src="img/logoJP.png" width="90"></div>
                </td>
                <td style="width: 68%">
                    @if ($transferencia)
                    <div class="col-md-7" align="center"><b>COMPROBANTE DE TRANSFERENCIA</b></div>
                    @else
                    <div class="col-md-7" align="center"><b>COMPROBANTE DE INGRESO</b></div>
                    @endif
                </td>
                <td style="width: 22%;">
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
                    <div style="margin: 0%; margin-bottom: 0px; margin-top: 0px;" align="center">JP Construcred C. Ltda.</div>
                    <div style="margin: 0%; margin-bottom: 0px; margin-top: 0px;" align="center">Generado por:
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
                <td>Ingresado por: <b>{{ $per_autoriza }}</b></td>
                <td></td>
                <td>Estado: <b>{{ $estado }}</b></td>
            </tr>
            <tr class="row">
                <td>Cliente: <b>{{$cliente}}</b></td>
                <td></td>
                <td>Motivo: <b>{{$motivo}}</b></td>
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
                <th>Condición</th>
                <th>Cantidad</th>
            </thead>
            <tbody style="font-size: 14px;">
                {{-- @foreach ($ciclo as $c) --}}
                @foreach ($listadoProductosTransaccion as $listado)
                <tr>
                    <td>{{ $listado['producto'] }}</td>
                    <td>{{ $listado['descripcion'] }}</td>
                    <td>{{ $listado['categoria'] }}</td>
                    <td>{{ $listado['condiciones'] }}</td>
                    <td align="center">{{ $listado['cantidad'] }}</td>
                </tr>
                @endforeach
                {{-- @endforeach --}}
            </tbody>
        </table>
    </main>


</body>

</html>
