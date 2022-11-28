<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Document</title>
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
    <h1>Titulo de prueba</h1>
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
        <h5>{{$transaccion}}</h5>

        
            @foreach ($transaccion->detallesTransaccion as $detalle )
                <li>{{$detalle}}</li>
                Producto: <strong>{{$detalle->detalle->descripcion}}|{{$detalle->detalle->campos_adicionales}}</strong><br>
                Categoria: <strong>{{$detalle->detalle->producto->categoria->nombre}}</strong><br>
                Cantidad inicial: <strong>{{$detalle->cantidad_inicial}}</strong><br>
                Cantidad final: <strong>{{$detalle->cantidad_final}}</strong><br>
            @endforeach
            @foreach ($transaccion->detalles as $detalle2 )
                Producto: <strong>{{$detalle2->descripcion}}|{{$detalle2->campos_adicionales}}</strong><br>
                Categoria: <strong>{{$detalle2->producto->categoria->nombre}}</strong><br>
                Cantidad inicial: <strong>{{$detalle2->pivot->cantidad_inicial}}</strong><br>
                Cantidad final: <strong>{{$detalle2->pivot->cantidad_final}}</strong><br>
            @endforeach
        
    </div>
</body>

</html>
