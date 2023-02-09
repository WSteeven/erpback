<!DOCTYPE html>
<html lang="es">

<head>
    <title>Pedido N° {{ $id }}</title>
    <style>
        @page {
            margin: 0cm 0cm;
        }

        body {
            background-image: url('img/logoJP_gris.png');
            background-repeat: no-repeat;
            background-position: center;
            opacity: .2;
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
            bottom: 0cm;
            left: 0cm;
            right: 0cm;
            height: 2cm;

            /** Estilos extra personales **/
            text-align: center;
            color:#000000; 
            line-height: 1.5cm;
        }

        h1 {
            text-align: center;
            text-transform: uppercase;
        }

        .contenido {
            /* margin-top: 4px; */
            font-size: 15px;
            /* text-transform: uppercase; */
        }

        .row {
            width: 100%;
        }

    </style>
</head>

<body>
    <header>
        <table style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px;page-break-inside: avoid;">
            <tr class="row">
                <td>
                    <div class="col-md-3"><img src="img/logoJP.png" width="50"></div>
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
    <br><br><br><br>
    <!-- aqui va el contenido del document<br><br>o -->
    <div class="contenido">
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
            <thead>
                <tr>
                    <td>Tarea: <b>{{ $tarea }}</b></td>
                </tr>
            </thead>
        </table>
        <!-- aqui va el listado de produ bordctos -->
        <table border="1" style="border-collapse: collapse; width: 98%;" align="center">
            <thead>
                <th>Producto</th>
                <th>Descripcion</th>
                <th>Categoria</th>
                <th>Cantidad</th>
                <th>Despachado</th>
            </thead>
            <tbody style="font-size: 14px;">
                @foreach($listadoProductos as $listado)
                <tr>
                    <td>{{ $listado['producto'] }}</td>
                    <td>{{ $listado['descripcion'] }}</td>
                    <td>{{ $listado['categoria'] }}</td>
                    <td align="center">{{ $listado['cantidad'] }}</td>
                    <td align="center">{{ $listado['despachado'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{-- Dato recibido desde el controlador --}}


    </div>
    @php
    $usuario = auth()->user();
    $fecha = new Datetime();
    @endphp
    <footer>JP Construcred C. Ltda. / Reposte Generado por el Usuario:
        {{ auth('sanctum')->user()->empleado->nombres }}
        {{ auth('sanctum')->user()->empleado->apellidos }} el
        {{ $fecha->format('d/m/Y H:i') }}
    </footer>
</body>


</html>
