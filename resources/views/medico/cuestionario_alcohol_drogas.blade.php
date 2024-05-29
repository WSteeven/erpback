<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>C. Alcohol y Drogas</title>
</head>

<style>
    .bg-gris {
        background-color: #808080;
    }

    .bg-azul {
        background-color: #305496;
    }

    .bg-celeste {
        background-color: #2f75b5;
    }

    .bg-naranja {
        background-color: #c65911;
    }

    .bg-dorado {
        background-color: #bf8f00;
    }
</style>

<body>
    <table
        style="color:#FFF; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px;margin-top: 20px;">
        <thead>
            <tr height="50"></tr>
            <tr></tr>
            <tr>
                <th valign="center" height="60" style="background-color: #808080; padding: 8px;" color="#fff">1. FECHA
                    DEL DIAGNÓSTICO</th>
                <th valign="center" style="background-color: #305496; padding: 8px;">2. NOMBRE DE LA EMPRESA /
                    INSTITUCIÓN</th>
                <th valign="center" style="background-color: #305496; padding: 8px;">2.1. RUC</th>
                <th valign="center" style="background-color: #305496; padding: 8px;">2.2. CARGO / PUESTO DEL TRABAJADOR
                </th>
                <th valign="center" style="background-color: #2f75b5; padding: 8px;">3. CÉDULA / PASAPORTE DEL EMPLEADO
                </th>
                <th valign="center" style="background-color: #2f75b5; padding: 8px;">3.1. AÑO DE NACIMIENTO</th>
                <th valign="center" style="background-color: #2f75b5; padding: 8px;">3.2. TIPO DE AFILIACIÓN SEGURIDAD
                    SOCIAL</th>
                <th valign="center" style="background-color: #2f75b5; padding: 8px;">3.3. ESTADO CIVIL</th>
                <th valign="center" style="background-color: #2f75b5; padding: 8px;">3.4. GÉNERO</th>
                <th valign="center" style="background-color: #2f75b5; padding: 8px;">3.5. NIVEL DE INSTRUCCIÓN</th>
                <th valign="center" style="background-color: #2f75b5; padding: 8px;">3.6. NÚMERO DE HIJOS</th>
                <th valign="center" style="background-color: #2f75b5; padding: 8px;">3.7. AUTOIDENTIFICACIÓN ÉTNICA</th>
                <th valign="center" style="background-color: #2f75b5; padding: 8px;">3.8. DISCAPACIDAD</th>
                <th valign="center" style="background-color: #2f75b5; padding: 8px;">3.9. PORCENTAJE DE DISCAPACIDAD
                </th>
                <th valign="center" style="background-color: #2f75b5; padding: 8px;">3.10. EL EMPLEADO ES "TRABAJADOR
                    SUSTITUTO"</th>
                <th valign="center" style="background-color: #2f75b5; padding: 8px;">3.10. ENFERMEDADES PRE-EXISTENTES
                </th>
                @foreach ($preguntas as $pregunta)
                    <th valign="center" style="background-color: #305496; color: #fff; padding: 8px;">
                        {{ $pregunta->codigo . '.- ' . $pregunta->pregunta }}</th>
                @endforeach
                <th valign="center" style="background-color: #c65911; color: #fff; padding: 8px;">5. TRATAMIENTO</th>
                <th valign="center" style="background-color: #808080; color: #fff; padding: 8px;">6. PERSONAL HA
                    RECIBIDO SENSIBILIZACIÓN, CAPACITACIÓN, CHARLAS</th>
                <th valign="center" style="background-color: #bf8f00; color: #fff; padding: 8px;">7. EMPLEADO CUENTA CON
                    EXÁMEN PRE-OCUPACIONAL</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reportes_empaquetado as $reporte_empaquetado)
                <tr>
                    <td valign="center">{{ $reporte_empaquetado['fecha_diagnostico'] }}</td>
                    <td valign="center">{{ $configuracion['razon_social'] }}</td>
                    <td valign="center">{{ $configuracion['ruc'] }}</td>
                    <td valign="center">{{ $reporte_empaquetado['cargo'] }}</td>
                    <td valign="center">{{ $reporte_empaquetado['identificacion'] }}</td>
                    <td valign="center">{{ $reporte_empaquetado['anio_nacimiento'] }}</td>
                    <td valign="center">{{ $reporte_empaquetado['tipo_afiliacion_seguridad_social'] }}</td>
                    <td valign="center">{{ $reporte_empaquetado['estado_civil'] }}</td>
                    <td valign="center">{{ $reporte_empaquetado['genero'] }}</td>
                    <td valign="center">{{ $reporte_empaquetado['nivel_academico'] }}</td>
                    <td valign="center">{{ $reporte_empaquetado['numero_hijos'] }}</td>
                    <td valign="center">{{ $reporte_empaquetado['autoidentificacion_etnica'] }}</td>
                    <td valign="center">{{ $reporte_empaquetado['discapacidad'] }}</td>
                    <td valign="center">{{ $reporte_empaquetado['porcentaje_discapacidad'] }}</td>
                    <td valign="center">{{ $reporte_empaquetado['trabajador_sustituto'] }}</td>
                    <td valign="center">{{ $reporte_empaquetado['enfermedades_preexistentes'] }}</td>
                    <td valign="center">{{ $reporte_empaquetado['principal_droga_consume'] }}</td>
                    <td valign="center">{{ $reporte_empaquetado['otra_droga_especifique'] }}</td>
                    <td valign="center">{{ $reporte_empaquetado['otra_droga_consume'] }}</td>
                    <td valign="center">{{ $reporte_empaquetado['frecuencia_consumo'] }}</td>
                    <td valign="center">{{ $reporte_empaquetado['reconoce_tener_problema_consumo'] }}</td>
                    <td valign="center">{{ $reporte_empaquetado['factores_psicosociales_consumo'] }}</td>
                    <td valign="center">{{ $reporte_empaquetado['tratamiento'] }}</td>
                    <td valign="center">{{ $reporte_empaquetado['personal_recibio_capacitacion'] }}</td>
                    <td valign="center">{{ $reporte_empaquetado['cuenta_con_examen_preocupacional'] }}</td>


                    {{-- @foreach ($reporte_empaquetado['cuestionario'] as $cuestionario)
                        <td>{{ $cuestionario['respuesta']['valor'] . '. ' . $cuestionario['respuesta']['respuesta'] }}
                        </td>
                    @endforeach --}}
                    {{-- <td valign="center">{{ $reporte_empaquetado['empleado'] }}</td>
                    <td valign="center">{{ $reporte_empaquetado['provincia'] }}</td>
                    <td valign="center">{{ $reporte_empaquetado['ciudad'] }}</td>
                    <td valign="center">{{ $reporte_empaquetado['area'] }}</td>
                    <td valign="center">{{ $reporte_empaquetado['antiguedad'] }}</td>
                    <td valign="center">{{ $reporte_empaquetado['edad'] }}</td> --}}
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
