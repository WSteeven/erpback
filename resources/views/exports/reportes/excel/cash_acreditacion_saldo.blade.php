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
                <td width='35%'>{{ $cash['tipo_pago'] }}</td>
                <td width='96%'>{{ $cash['numero_cuenta_empresa'] }}</td>
                <td width='55%'>{{ $cash['item'] }}</td>
                <td> </td>
                <td width='100%'>{{ $cash['identificacion'] }}</td>
                <td width='45%'>{{ $cash['moneda'] }}</td>
                <td width='100%'>{{ $cash['total'] }}</td>
                <td width='45%'>{{ $cash['forma_pago'] }}</td>
                <td width='50%'>{{ $cash['codigo_banco'] }}</td>
                <td width='50%'>{{ $cash['tipo_cuenta'] }}</td>
                <td width='98%'>{{ $cash['numero_cuenta_bancareo'] }}</td>
                <td width='35%'>{{ $cash['tipo_documento_empleado'] }}</td>
                <td width='100%'>{{ $cash['identificacion'] }}</td>
                <td width='96%'>{{ $cash['empleado_info'] }}</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td width='100%'>{{ $cash['referencia'] }}</td>
                <td width='100%'> |{{ $cash['email'] }}</td>
            </tr>
        @endforeach


    </table>
</body>

</html>
