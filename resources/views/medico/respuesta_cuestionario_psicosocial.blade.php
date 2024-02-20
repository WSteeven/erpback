<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>C.PisicoSocial</title>
</head>

<body>
    <table>
        <tr>
            <td colspan="4">CENTRO</td>
        </tr>
        <tr>
            <td colspan="4">PUESTO 1</td>
        </tr>
        @foreach ($reportes_empaquetado as $reporte_empaquetado)
            <tr>
                <td>{{ $reporte_empaquetado['empleado'] }}</td>
                <td>1</td>
                <td>{{ $reporte_empaquetado['respuestas_concatenadas'] }}</td>
                <td></td>
            </tr>
        @endforeach
    </table>
</body>

</html>
