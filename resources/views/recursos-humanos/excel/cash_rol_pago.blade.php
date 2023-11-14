<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cash</title>
</head>

<body>
    <table class="table">
        @foreach ($reporte as $cash)
            <tr>
                <td>{{ $cash['tipo_pago'] }}</td>
                <td>{{ $cash['numero_cuenta_empresa'] }}</td>
                <td>{{ $cash['item'] }}</td>
                <td> </td>
                <td>{{ $cash['identificacion'] }}</td>
                <td>{{ $cash['moneda'] }}</td>
                <td>{{ $cash['total'] }}</td>
                <td>{{ $cash['forma_pago'] }}</td>
                <td>{{ $cash['codigo_banco'] }}</td>
                <td>{{ $cash['tipo_cuenta'] }}</td>
                <td>{{ $cash['numero_cuenta_bancareo'] }}</td>
                <td>{{ $cash['tipo_documento_empleado'] }}</td>
                <td>{{ $cash['identificacion'] }}</td>
                <td> {{ $cash['empleado_info'] }}</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>{{ $cash['referencia'] }}</td>
                <td>|{{ $cash['email'] }}</td>
                <td>{{ $cash['departamento'] }}</td>

            </tr>
        @endforeach


    </table>
</body>

</html>
