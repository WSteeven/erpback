@php
    use Src\Shared\Utils;
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Calificación del Proveedor</title>
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

        table .td {
            text-align: center !important;
        }
    </style>
</head>

<body>
    <table
        style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px;margin-top: 20px;">
        <tr>
            <div class="header">
                <table
                    style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px;page-break-inside: avoid;">
                    <tr>
                        <td width="17%">
                            <img src="{{ Utils::getImagePath($configuracion['logo_claro']) }}" width="90">
                        </td>
                        <td width="83%" style="font-size:16px; font-weight:bold">
                            <div align="center">{{$configuracion['razon_social']}}</div>
                        </td>
                    </tr>
                    <tr>
                        <td width="17%">
                            <div align="center"></div>
                        </td>
                        <td width="83%" style="font-size:12px">
                            <div align="center"><strong>CALIFICACION DE PROVEEDOR - {{ $reporte[0]['ruc'] }} -
                                    {{ $reporte[0]['sucursal'] }}
                                </strong>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </tr>
        {{-- Datos del proveedor --}}
        <tr>
            <div class="header">
                <table
                    style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px;page-break-inside: avoid;">
                    <tr>
                        <td align="center" style="background-color: #DBDBDB;" colspan="2"><strong>INFORMACIÓN DEL
                                PROVEEDOR</strong>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>RUC:</strong> </td>
                        <td>{{ $reporte[0]['ruc'] }}</td>
                    </tr>
                    <tr>
                        <td><strong>RAZON SOCIAL:</strong> </td>
                        <td>{{ $reporte[0]['razon_social'] }}</td>
                    </tr>
                    <tr>
                        <td><strong>SUCURSAL:</strong> </td>
                        <td>{{ $reporte[0]['sucursal'] }}</td>
                    </tr>
                    <tr>
                        <td><strong>CIUDAD:</strong> </td>
                        <td>{{ $reporte[0]['ciudad'] }}</td>
                    </tr>
                    <tr>
                        <td><strong>DIRECCION:</strong> </td>
                        <td>{{ $reporte[0]['direccion'] }}</td>
                    </tr>
                    <tr>
                        <td><strong>CATEGORIAS:</strong> </td>
                        <td>{{ $reporte[0]['categorias'] }}</td>
                    </tr>
                    <tr>
                        <td><strong>CALIFICACION TOTAL:</strong> </td>
                        <td>{{ $reporte[0]['calificacion_total'] }}</td>
                    </tr>
                </table>
            </div>
        </tr>
        @foreach ($reporte as $rpt)
            <tr>
                <td><strong>DEPARTAMENTO:</strong> </td>
                <td>{{ $rpt['departamento'] }}</td>
            </tr>
            <tr>
                <td><strong>EMPLEADO:</strong> </td>
                <td>{{ $rpt['empleado'] }}</td>
            </tr>
            <tr>
                <td><strong>CALIFICACION:</strong> </td>
                <td>{{ $rpt['calificacion'] }}</td>
            </tr>
            <tr>
                <td><strong>FECHA DE CALIFICACION: </strong></td>
                <td>{{ $rpt['fecha_calificacion'] }}</td>
            </tr>
            @empty(!$rpt['calificaciones_bienes'])
                <tr>
                    <td></td>
                </tr>
                <tr>
                    <td><strong>CALIFICACIONES DE BIENES</strong></td>
                </tr>
                <tr>
                    <td style="background-color: #DBDBDB;">CRITERIO</td>
                    <td style="background-color: #DBDBDB;">COMENTARIO</td>
                    <td style="background-color: #DBDBDB;">PESO (%)</td>
                    <td style="background-color: #DBDBDB;">PUNTAJE (1-5)</td>
                    <td style="background-color: #DBDBDB;">CALIFICACION</td>
                </tr>
                @foreach ($rpt['calificaciones_bienes'] as $cal)
                    <tr>
                        <td>{{ $cal['criterio'] }}</td>
                        <td>{{ isset($cal['comentario']) ? $cal['comentario'] : null }}</td>
                        <td>{{ $cal['peso'] }}</td>
                        <td>{{ $cal['puntaje'] }}</td>
                        <td>{{ $cal['calificacion'] }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td style="background-color: #DBDBDB;"><strong>CALIFICACION:</strong> </td>
                    <td style="background-color: #DBDBDB;"><strong>{{ $rpt['calificacion'] }}</strong></td>
                </tr>
                <tr></tr>
                <tr></tr>
            @endempty
            @empty(!$rpt['calificaciones_servicios'])
                <tr>
                    <td><strong>CALIFICACIONES DE SERVICIOS</strong></td>
                </tr>
                <tr>
                    <td style="background-color: #DBDBDB;">CRITERIO</td>
                    <td style="background-color: #DBDBDB;">COMENTARIO</td>
                    <td style="background-color: #DBDBDB;">PESO (%)</td>
                    <td style="background-color: #DBDBDB;">PUNTAJE (1-5)</td>
                    <td style="background-color: #DBDBDB;">CALIFICACION</td>
                </tr>
                @foreach ($rpt['calificaciones_servicios'] as $cal)
                    <tr>
                        <td>{{ $cal['criterio'] }}</td>
                        <td>{{ isset($cal['comentario']) ? $cal['comentario'] : null }}</td>
                        <td>{{ $cal['peso'] }}</td>
                        <td>{{ $cal['puntaje'] }}</td>
                        <td>{{ $cal['calificacion'] }}</td>
                    </tr>
                @endforeach
                <tr></tr>
            @endempty
        @endforeach

    </table>


</body>

</html>
