<!DOCTYPE html>
<html lang="es">

<head>
    <title>Pedido N° {{ $id }}</title>
    <style>
        h1 {
            text-align: center;
            text-transform: uppercase;
        }

        .contenido {
            font-size: 20px;
        }

        #primero {
            background-color: #ccc;
        }

        #segundo {
            color: #44a359;
        }

        #tercero {
            text-decoration: line-through;
        }
    </style>
</head>

<body>
    <header>
        <table class="container-fluid">
            <tr class="row">
                <td>
                    <div class="col-md-3"><img src="img/logoJP.png" width="50"></div>
                </td>
                <td>
                    <div class="col-md-6" align="center"><b>COMPROBANTE DE PEDIDO</b></div>
                </td>
                <td>
                    <div class="col-md-3">Sistema de bodega</div>
                </td>
            </tr>
        </table>
    </header>
    <hr>
    <div class="contenido">
        <p id="primero">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Labore nihil illo odit aperiam alias
            rem voluptatem odio maiores doloribus facere recusandae suscipit animi quod voluptatibus, laudantium
            obcaecati quisquam minus modi.</p>
        <p id="segundo">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Labore nihil illo odit aperiam alias
            rem voluptatem odio maiores doloribus facere recusandae suscipit animi quod voluptatibus, laudantium
            obcaecati quisquam minus modi.</p>
        <p id="tercero">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Labore nihil illo odit aperiam alias
            rem voluptatem odio maiores doloribus facere recusandae suscipit animi quod voluptatibus, laudantium
            obcaecati quisquam minus modi.</p>
        {{-- Dato recibido desde el controlador --}}
        <h5>{{ $id }}</h5>
        @json($listadoProductos)
        @foreach ($listadoProductos as $listado)
            <li>{{ $listado['id'] }}</li>
        @endforeach


        {{-- @foreach ($pedido->listadoProductos as $detalle)
                        <li>{{$detalle}}</li>
                        Producto: <strong>{{$detalle->detalle->descripcion}}|{{$detalle->detalle->campos_adicionales}}</strong><br>
                        Categoria: <strong>{{$detalle->detalle->producto->categoria->nombre}}</strong><br>
                        Cantidad inicial: <strong>{{$detalle->cantidad_inicial}}</strong><br>
                        Cantidad final: <strong>{{$detalle->cantidad_final}}</strong><br>
                        @endforeach
                        @foreach ($transaccion->detalles as $detalle2)
                        Producto: <strong>{{$detalle2->descripcion}}|{{$detalle2->campos_adicionales}}</strong><br>
                        Categoria: <strong>{{$detalle2->producto->categoria->nombre}}</strong><br>
                        Cantidad inicial: <strong>{{$detalle2->pivot->cantidad_inicial}}</strong><br>
                        Cantidad final: <strong>{{$detalle2->pivot->cantidad_final}}</strong><br>
                        @endforeach --}}

    </div>
    @php
        $usuario = auth()->user();
        $fecha = new Datetime();
    @endphp
    <footer>JP Construcred C. Ltda. / Reposte Generado por el Usuario: ' {{ auth('api')->user() }} el
        {{ $fecha->format('d/m/Y H:i') }}
        
        </footer>
</body>


</html>
