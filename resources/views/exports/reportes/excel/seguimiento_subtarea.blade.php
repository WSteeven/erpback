<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte</title>
    <style>
        @page {
            margin: 100px 25px;
        }

        /* .header {
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
        } */
        table {
            border-collapse: collapse;
        }

        .tabla-border-1 td,
        .tabla-border-1 th {
            border: 1px solid #000;
        }

        .borde-celda-1 {
            border: 1px solid #000;
        }

        .borde-celda-2 {
            border: 2px solid #000;
        }
    </style>
</head>

<body>

    <table style="border: 1px solid #000;">
        <thead>
            <tr>
                <th rowspan="4" colspan="2"
                    style="border: 2px solid #000; vertical-align: middle; text-align: center; width: 40px;">
                    <img src="img/logo.png" height="40px" style="margin: 0 auto;" />
                </th>
                <th rowspan="4" colspan="2"
                    style="border: 2pt solid #000; vertical-align: middle; text-align: center; width: 800px; font-size: 14pt; font-weight: bold;">
                    {{'REPORTE DE ASISTENCIA TÉCNICA OPERATIVA'}} <br>
                    {{'NEDETEL/UFINET - OUTSOURCING O&M'}}</th>
                <th rowspan="4" colspan="2"
                    style="border: 2px solid #000; vertical-align: middle; text-align: center; width: 100px;">FOR TEC
                    003 <br> V.02 11 07 2023</th>
            </tr>
        </thead>

        <tbody style="border: 2px solid #000; background: #fff;">
            <tr></tr>
            <tr></tr>
            <tr></tr>
            <tr></tr>

            <tr>
                <td style="width: 30px;"></td>
                <td style="font-weight: bold;">TICKET/TAREA ASIGNADA:</td>
                <td style="width: 360px;"></td>
                <td colspan="2" style="text-align: center; border: 1px solid #000; width: 200px;">{{
                    $subtarea->codigo_subtarea }}</td>
                <td style="width: 30px; border-right: 8pt solid #000;"></td>
            </tr>

            <tr>
                <td></td>
                <td style="font-weight: bold;">REGIONAL:</td>
                <td></td>
                <td colspan="2" style="text-align: center; border: 1px solid #000;">{{
                    $subtarea->grupoResponsable?->region }}</td>
                <td></td>
            </tr>

            <tr>
                <td></td>
                <td style="font-weight: bold;">CONTRATISTA:</td>
                <td></td>
                <td colspan="2" style="text-align: center; border: 1px solid #000;">JP CONSTRUCRED</td>
                <td></td>
            </tr>

            <tr>
                <td></td>
                <td style="font-weight: bold;">CUADRILLA ASIGNADA:</td>
                <td></td>
                <td colspan="2" style="text-align: center; border: 1px solid #000;">{{
                    $subtarea->grupoResponsable?->nombre }}</td>
                <td></td>
            </tr>

            <tr>
                <td></td>
                <td style="font-weight: bold;">FECHA DE ELABORACIÓN DEL REPORTE:</td>
                <td></td>
                <td colspan="2" style="text-align: center; border: 1px solid #000;">{{ $fecha_actual }}</td>
                <td></td>
            </tr>

            <tr>
                <td></td>
                <td style="font-weight: bold;">REPORTE ELABORADO POR:</td>
                <td></td>
                <td colspan="2" style="text-align: center; border: 1px solid #000;">{{ $reporte_generado_por }}</td>
                <td></td>
            </tr>

            <tr></tr>

            <tr>
                <td></td>
                <td style="font-weight: bold;">PERSONAL TÉCNICO OPERATIVO:</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>

            <tr></tr>

            <tr>
                <td></td>
                <td colspan="4">
                    <table>
                        <tr>
                            <th style="border: 1px solid #000; text-align: center; font-weight: bold;">N°</th>
                            <th colspan="3" style="border: 1px solid #000; text-align: center; font-weight: bold;">
                                NOMBRES Y
                                APELLIDOS</th>
                        </tr>

                        @foreach ($empleados_designados as $empleado)
                        <tr>
                            <td style="border: 1px solid #000; text-align: center;">{{ $loop->index + 1 }}</td>
                            <td colspan="3" style="border: 1px solid #000;">{{ $empleado }}</td>
                        </tr>
                        @endforeach
                    </table>
                </td>
                <td></td>
            </tr>

            <tr>
                <td></td>
                <td style="font-weight: bold;">ATENCIÓN (INTERURBANO/URBANO):</td>
                <td></td>
                <td colspan="2" style="text-align: center; border: 1px solid #000;">{{ $subtarea->atencion }}</td>
                <td></td>
            </tr>

            <tr>
                <td></td>
                <td style="font-weight: bold;">TIPO DE INTERVENCIÓN:</td>
                <td></td>
                <td colspan="2" style="text-align: center; border: 1px solid #000;">{{
                    $subtarea->tipo_trabajo->descripcion }}</td>
                <td></td>
            </tr>

            <tr>
                <td></td>
                <td style="font-weight: bold;">TRAMO AFECTADO/OBSERVADO:</td>
                <td></td>
                <td colspan="2" style="text-align: center; border: 1px solid #000;">{{
                    $subtarea->tarea->rutaTarea?->ruta
                    }}</td>
                <td></td>
            </tr>

            <tr>
                <td></td>
                <td style="font-weight: bold;">CAUSA DE INTERVENCIÓN:</td>
                <td></td>
                <td colspan="2" style="text-align: center; border: 1px solid #000;">{{
                    $subtarea->causaIntervencion?->nombre
                    }}</td>
                <td></td>
            </tr>

            <tr></tr>

            <tr>
                <td></td>
                <td style="font-weight: bold;">CONTACTO NEDETEL/UFINET:</td>
                <td></td>
                <td colspan="2" style="text-align: center; border: 1px solid #000;">{{
                    $subtarea->tarea->clienteFinal?->nombres . $subtarea->tarea->clienteFinal?->apellidos
                    }}</td>
                <td></td>
            </tr>

            <tr></tr>

            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td style="font-weight: bold; text-align: center;">FECHA</td>
                <td style="font-weight: bold; text-align: center;">HORA</td>
                <td></td>
            </tr>

            <tr>
                <td></td>
                <td style="font-weight: bold;">HORA DE REPORTE DEL PROBLEMA:</td>
                <td></td>
                <td style="text-align: center; border: 1px solid #000;">{{
                    \Carbon\Carbon::parse($subtarea->tarea->fecha_solicitud)->format('d/m/Y');
                    }}</td>
                <td style="text-align: center; border: 1px solid #000;"></td>
                <td></td>
            </tr>

            <tr>
                <td></td>
                <td style="font-weight: bold;">HORA DE ARRIBO:</td>
                <td></td>
                <td style="text-align: center; border: 1px solid #000;">{{
                    $fecha_hora_arribo_personal ? \Carbon\Carbon::parse($fecha_hora_arribo_personal)->format('d/m/Y') :
                    '';
                    }}</td>
                <td style="text-align: center; border: 1px solid #000;">{{
                    $fecha_hora_arribo_personal ? \Carbon\Carbon::parse($fecha_hora_arribo_personal)->format('H:i') :
                    '';
                    }}</td>
                <td></td>
            </tr>

            <tr>
                <td></td>
                <td style="font-weight: bold;">HORA DE FIN DE REPARACIÓN:</td>
                <td></td>
                <td style="text-align: center; border: 1px solid #000;">{{
                    \Carbon\Carbon::parse($subtarea->fecha_hora_realizado)->format('d/m/Y');
                    }}</td>
                <td style="text-align: center; border: 1px solid #000;">{{
                    \Carbon\Carbon::parse($subtarea->fecha_hora_realizado)->format('H:i');
                    }}</td>
                <td></td>
            </tr>

            <tr>
                <td></td>
                <td style="font-weight: bold;">HORA DE RETIRO DE PERSONAL:</td>
                <td></td>
                <td style="text-align: center; border: 1px solid #000;">{{
                    $fecha_hora_retiro_personal ? \Carbon\Carbon::parse($fecha_hora_retiro_personal)->format('d/m/Y') :
                    '';
                    }}</td>
                <td style="text-align: center; border: 1px solid #000;">{{
                    $fecha_hora_retiro_personal ? \Carbon\Carbon::parse($fecha_hora_retiro_personal)->format('H:i') :
                    '';
                    }}</td>
                <td></td>
            </tr>

            <tr>
                <td></td>
                <td style="font-weight: bold;">TIEMPO DE ESPERA ADICIONALES (TERCEROS/CLIMA/SECTOR):</td>
                <td></td>
                <td></td>
                <td style="text-align: center; border: 1px solid #000;"></td>
                <td></td>
            </tr>

            <tr></tr>

            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td style="font-weight: bold; text-align: center;">ESTACIÓN DE REFERENCIA</td>
                <td style="font-weight: bold; text-align: center;">DISTANCIA</td>
                <td></td>
            </tr>

            <tr>
                <td></td>
                <td style="font-weight: bold;">{{ 'DISTANCIA DE LA AFECTACIÓN' }}</td>
                <td></td>
                <td style="border: 1px solid #000;"></td>
                <td style="border: 1px solid #000;"></td>
                <td></td>
            </tr>

            <tr></tr>

            <tr>
                <td></td>
                <td style="font-weight: bold;">CRONOLOGÍA DE TRABAJOS REALIZADOS:</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>

            <tr></tr>

            <tr>
                <td></td>
                <td colspan="4">
                    <table>
                        <tr>
                            <th style="text-align: center; font-weight: bold;">{{ 'HORA' }}</th>
                            <th colspan="3" style="text-align: center; font-weight: bold;">{{ 'ACCIONES REALIZADAS' }}
                            </th>
                        </tr>

                        @foreach ($subtarea->trabajosRealizados as $trabajo)
                        <tr>
                            <td style="border: 1px solid #000; text-align: center;">{{
                                \Carbon\Carbon::parse($trabajo->fecha_hora)->format('H:i'); }}</td>
                            <td colspan="3" style="border: 1px solid #000;">{{ $trabajo->trabajo_realizado }}</td>
                        </tr>
                        @endforeach
                    </table>
                </td>
                <td></td>
            </tr>

            <tr>
                <td></td>
                <td colspan="4" style="font-weight: bold;">{{ 'OBSERVACIONES/MEJORAS/PENDIENTES:' }}</td>
                <td></td>
            </tr>

            <tr>
                <td></td>
                <td colspan="4" style="border: 1px solid #000;"></td>
                <td></td>
            </tr>

            <tr>
                <td></td>
                <td colspan="4" style="border: 1px solid #000;"></td>
                <td></td>
            </tr>

            <tr>
                <td></td>
                <td colspan="4" style="border: 1px solid #000;"></td>
                <td></td>
            </tr>

            <tr></tr>

            <tr>
                <td></td>
                <td colspan="4" style="font-weight: bold;">{{ 'MATERIALES UTILIZADOS:' }}</td>
                <td></td>
            </tr>

            <tr>
                <td></td>
                <td>
                    hola
                </td>
                <td></td>
            </tr>

            <tr>
                <td></td>
                <td colspan="4">
                    <table>
                        <tr>
                            <th style="text-align: center; font-weight: bold;">{{ 'CANTIDAD' }}</th>
                            <th colspan="3" style="text-align: center; font-weight: bold;">{{ 'DESCRIPCIÓN DEL MATERIAL'
                                }}
                            </th>
                        </tr>

                        @foreach ($materiales_tarea_usados as $material)
                        <tr>
                            <td style="border: 1px solid #000; text-align: center;">{{ $material->cantidad_utilizada }}
                            </td>
                            <td colspan="3" style="border: 1px solid #000;">{{ $material->descripcion }}</td>
                        </tr>
                        @endforeach
                    </table>
                </td>
                <td></td>
            </tr>
        </tbody>


    </table>


</body>

</html>
