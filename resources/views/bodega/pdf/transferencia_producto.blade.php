<!DOCTYPE html>
<html lang="es">
@php
    $fecha = new Datetime();
    $mensaje_qr = 'JP CONSTRUCRED C. LTDA.';
    $logo_principal =
        'data:image/png;base64,' . base64_encode(file_get_contents(public_path() . $configuracion['logo_claro']));
    $logo_watermark =
        'data:image/png;base64,' . base64_encode(file_get_contents(public_path() . $configuracion['logo_marca_agua']));
@endphp


@endphp

<head>
    <meta charset="utf-8">
    <title>Transferencia Productos N° {{ $transferencia['id'] }}</title>
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
                <td style="width: 10%">
                    <div class="col-md-3"><img src="{{ $logo_principal }}" width="90"></div>
                </td>
                <td style="width: 68%">
                    <div class="col-md-7" align="center"><b>TRANSFERENCIA DE PRODUCTOS</b></div>
                </td>
                <td style="width: 22%">
                    <div class="col-md-2" align="right">Sistema de bodega</div>
                </td>
            </tr>
        </table>
        <hr>
    </header>

    <footer>
        <table style="width: 100%;">
            <tr>
                <td class="page">Página </td>
                <td style="line-height: normal;">
                    <div style="margin: 0%; margin-bottom: 0px; margin-top: 0px;" align="center">
                        {{ $configuracion['razon_social'] }}</div>
                    <div style="margin: 0%; margin-bottom: 0px; margin-top: 0px;" align="center">Generado por el
                        usuario:
                        {{ auth('sanctum')->user()->empleado->nombres }}
                        {{ auth('sanctum')->user()->empleado->apellidos }} el
                        {{ $fecha->format('d/m/Y H:i') }}
                    </div>
                </td>
            </tr>
        </table>
    </footer>

    <!-- aqui va el contenido del document<br><br>o -->
    <main>
        <table style="width: 100%; border: #000000; border-collapse: collapse;" border="0">
            <tr class="row">
                <td>Transferencia N°: <b>{{ $transferencia['id'] }}</b></td>
                <td>Fecha: <b>{{ $transferencia['created_at'] }}</b></td>
            </tr>
            <tr>
                <td>Solicitante: <b>{{ $transferencia['solicitante'] }}</b></td>
                <td>Propietario: <b>{{ $transferencia['empleado_origen'] }}</b></td>
            </tr>
            <tr>
                <td>Cliente propietario: <b>{{ $transferencia['nombre_cliente'] }}</b></td>
                <td>Receptor: <b>{{ $transferencia['empleado_destino'] }}</b></td>
            </tr>
            <tr>
                <td>Autorizador: <b>{{ $transferencia['autorizador'] }}</b></td>
                <td>Estado: <b>{{ $transferencia['estado'] }}</b></td>
            </tr>
            <tr>
                <td>Tarea origen: <b>{{ $transferencia['tarea_origen'] }}</b></td>
                <td>Tarea destino: <b>{{ $transferencia['tarea_destino'] }}</b></td>
            </tr>
            <tr>
                <td>Justificación: <b>{{ $transferencia['justificacion'] }}</b></td>
            </tr>
        </table>

        <br>
        
        <table border="1" style="border-collapse: collapse; margin-bottom:4px; width: 98%;" align="center">
            <thead>
                <th>Producto</th>
                <th>Descripcion</th>
                <th>Categoria</th>
                <th>Serial</th>
                <th>Cantidad</th>
            </thead>
            <tbody style="font-size: 10px;">
                @foreach ($transferencia['listado_productos'] as $listado)
                    <tr>
                        <td>{{ $listado['producto'] }}</td>
                        <td>{{ $listado['descripcion'] }}</td>
                        <td>{{ $listado['categoria'] }}</td>
                        <td>{{ $listado['serial'] }}</td>
                        <td align="center">{{ $listado['cantidad'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table> 
    </main>


</body>

</html>
