<!DOCTYPE html>
<html lang="es">

@php
$fecha = new Datetime();
$logo_principal = 'data:image/png;base64,' . base64_encode(file_get_contents('img/logo.png'));
$logo_watermark = 'data:image/png;base64,' . base64_encode(file_get_contents('img/logoBN10.png'));
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte</title>
    <style>
        @page {
            margin: 100px 25px;
        }

        .header {
            position: fixed;
            top: -55px;
            left: 0px;
            right: 0px;
            height: 80px;
            text-align: center;
            line-height: 35px;
        }

        .footer {
            position: fixed;
            bottom: -50px;
            left: 0px;
            right: 0px;
            height: 50px;
            color: #333333;
            text-align: center;
            line-height: 35px;
            font-size: 10px;
            font-style: italic;
        }

        table,
        td,
        th {
            border: 1px solid #000;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }
    </style>
</head>

<body>
    <table style="color: #000; width: 100%; table-layout: fixed;" border="1" bordercolor="#000">
        <tbody>

            {{-- Fila 0 --}}
            <tr>
                <td>&nbsp;</td>
                <td><img src="{{ public_path('img/logo.png') }}" width="90" /></td>
                <td>&nbsp;</td>
            </tr>

            {{-- Fila 1 --}}
            <tr>
                <td>&nbsp;</td>
                <td align="center"><b>INFORME DE SEGUIMIENTO</b></td>
                <td>&nbsp;</td>
            </tr>

            {{-- Fila 2 --}}
            <tr>
                <td>&nbsp;</td>
                <td align="center"><b>Módulo de tareas</b></td>
                <td>&nbsp;</td>
            </tr>

            {{-- Fila 3 --}}
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>

            {{-- Fila 4 --}}
            <tr>
                <td>Cronologia de trabajos realizados</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>

            <tr></tr>

            <tr>
                <td></td>
                <td>hola</td>
                <td></td>
            </tr>

            {{-- ############### --}}

            {{-- Fila 5 --}}
            <tr>

                <table>

                    <tr>
                        <th>Hora</th>
                        <th width="60">Actividad</th>
                        <th></th>
                    </tr>

                    @foreach ($trabajo_realizado as $trabajo)
                    <tr>
                        <td>{{ $trabajo['hora'] }}</td>
                        <td>{{ $trabajo['actividad'] }}</td>
                        <td>&nbsp;</td>
                    </tr>
                    @endforeach

                </table>


<br>


                <table>
                    <tr>
                        <th>Detalle de producto</th>
                        <th>Medida</th>
                        <th>Cantidad utilizada</th>
                    </tr>

                    @foreach ($materiales_ocupados as $material)
                    <tr>
                        <td>
                            <div>
                                {{ $material['detalle_producto'] }}
                            </div>
                        </td>
                        <td>{{ $material['medida'] }}</td>
                        <td>{{ $material['cantidad_utilizada'] }}</td>
                    </tr>
                    @endforeach
                </table>

            </tr>

            <tr></tr>

            {{-- Fila 4 --}}
            <tr>
                <td><b>Materiales designados para la tarea</b></td>
            </tr>
        </tbody>
    </table>



</body>

</html>
