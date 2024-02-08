<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>C.PisicoSocial</title>
</head>

<body>
    <table class="table">
        <thead>
            <tr>
                <th>NOMBRES Y APELLIDOS</th>
                <th>PROVINCIA</th>
                <th>CIUDAD</th>
                <th>AREA</th>
                <th>NIVEL ACADEMICO MAS ALTO</th>
                <th>ANTIGUEDAD</th>
                <th>EDAD</th>
                <th>GENERO</th>
                @foreach ($preguntas as $pregunta)
                    <th>{{ $pregunta->codigo . '.- ' . $pregunta->pregunta }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($reportes_empaquetado as $reporte_empaquetado)
                <tr>
                    <td>{{ $reporte_empaquetado['empleado'] }}</td>
                    <td>{{ $reporte_empaquetado['provincia'] }}</td>
                    <td>{{ $reporte_empaquetado['ciudad'] }}</td>
                    <td>{{ $reporte_empaquetado['area'] }}</td>
                    <td>{{ $reporte_empaquetado['nivel_academico'] }}</td>
                    <td>{{ $reporte_empaquetado['antiguedad'] }}</td>
                    <td>{{ $reporte_empaquetado['edad'] }}</td>
                    <td>{{ $reporte_empaquetado['genero'] }}</td>
                    @foreach ($reporte_empaquetado['cuestionario'] as $cuestionario)
                        <td>{{ $cuestionario['respuesta']['valor'].'. '. $cuestionario['respuesta']['respuesta'] }}</td>
                    @endforeach
                    <td></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
