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
            <td colspan="3">a. Género</td>
        </tr>
        <tr>
            <td colspan="3">> Mujer
            </td>
        </tr>
        <tr>
            <td colspan="3">> Hombre</td>
        </tr>
        <tr>
            <td colspan="3">b. Antigüedad en el puesto</td>
        </tr>
        <tr>
            <td colspan="3">>Menor a un año</td>
        </tr>
        <tr>
            <td colspan="3">>Entre 1 y 2 años</td>
        </tr>
        <tr>
            <td colspan="3">>Entre 2 y 3 años</td>
        </tr>
        <tr>
            <td colspan="3">>Entre 3 y 4 años</td>
        </tr>
        <tr>
            <td colspan="3">>Entre 4 y 5 años</td>
        </tr>
        <tr>
            <td colspan="3">>Entre 5 y 6 años</td>
        <tr>
            <td colspan="3">>Entre 6 y 7 años</td>
        </tr>
        <tr>
            <td colspan="3">>Entre 7 y 8 años</td>
        </tr>
        <tr>
            <td colspan="3">>Entre 8 y 9 años</td>
        </tr>
        <tr>
            <td colspan="3">>Entre 9 y 10 años</td>
        </tr>
        <tr>
            <td colspan="3">>Mayor a 10 años </td>
        </tr>
         <tr>
            <td colspan="3">**********************************************</td>
        </tr>
        @foreach ($reportes_empaquetado as $reporte_empaquetado)
            <tr>
                <td>{{ $reporte_empaquetado['empleado'] }}</td>
                <td>1</td>
                <td>{{ $reporte_empaquetado['respuestas_concatenadas'] }}</td>
            </tr>
        @endforeach
    </table>
</body>

</html>
