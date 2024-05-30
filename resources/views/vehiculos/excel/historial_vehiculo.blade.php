<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Historial de Vehículo</title>

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
                            <img src="{{ public_path($configuracion['logo_claro']) }}" width="90">
                        </td>
                        <td width="83%" style="font-size:16px; font-weight:bold">
                            <div align="center">{{ $configuracion['razon_social'] }}</div>
                        </td>
                    </tr>
                    <tr>
                        <td width="17%">
                            <div align="center"></div>
                        </td>
                        <td width="83%" style="font-size:12px">
                            <div align="center"><strong>HISTORIAL DE VEHICULO {{ $reporte['vehiculo']['placa'] }}
                                </strong>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td width="17%"></td>
                        <td width="83%">
                            <div align="center">Desde {{ $request->fecha_inicio }} hasta {{ $request->fecha_fin }}</div>
                        </td>
                    </tr>
                </table>
            </div>
        </tr>
        <tr>
            <td>
                <div align="center">
                    <table width="100%">
                        <tr>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                        </tr>
                        {{-- INFORMACION DEL VEHICULO --}}
                        <tr>
                            <td colspan="7" style="background-color: #DBDBDF;">
                                <strong>1. DATOS DEL VEHICULO</strong>
                            </td>
                        </tr>
                        <tr>
                            <td style="background-color: #DBDBDB"><strong>MARCA</strong></td>
                            <td style="background-color: #DBDBDB"><strong>MODELO</strong></td>
                            <td style="background-color: #DBDBDB"><strong>N° CHASIS</strong></td>
                            <td style="background-color: #DBDBDB"><strong>N° MOTOR</strong></td>
                            <td style="background-color: #DBDBDB"><strong>TIPO VEHICULO</strong></td>
                            <td style="background-color: #DBDBDB"><strong>TRACCION</strong></td>
                            <td style="background-color: #DBDBDB"><strong>CILINDRAJE</strong></td>
                        </tr>
                        <tr>
                            <td>{{ $reporte['vehiculo']['modelo']['marca']['nombre'] }}</td>
                            <td>{{ $reporte['vehiculo']['modelo']['nombre'] }}</td>
                            <td>{{ $reporte['vehiculo']['num_chasis'] }}</td>
                            <td>{{ $reporte['vehiculo']['num_motor'] }}</td>
                            <td>{{ $reporte['vehiculo']['tipoVehiculo']['nombre'] }}</td>
                            <td>{{ $reporte['vehiculo']['traccion'] }}</td>
                            <td>{{ $reporte['vehiculo']['cilindraje'] }} cc</td>
                        </tr>
                        <tr>
                            <td></td>
                        </tr>
                        {{-- HISTORIAL DE CUSTODIOS --}}
                        @if ($reporte['custodios'])
                            <tr>
                                <td colspan="7" style="background-color: #DBDBDF;">
                                    <strong>2. HISTORIAL DE CUSTODIOS</strong>
                                </td>
                            </tr>
                            <tr>
                                <td style="background-color: #DBDBDB"><strong>FECHA ENTREGA</strong></td>
                                <td style="background-color: #DBDBDB"><strong>CIUDAD</strong></td>
                                <td style="background-color: #DBDBDB"><strong>ENTREGA</strong></td>
                                <td style="background-color: #DBDBDB"><strong>RESPONSABLE</strong></td>
                                <td colspan="2" style="background-color: #DBDBDB"><strong>OBSERVACION
                                        ENTREGA</strong>
                                </td>
                                <td style="background-color: #DBDBDB"><strong>ESTADO</strong></td>
                            </tr>
                            @foreach ($reporte['custodios'] as $custodio)
                                <tr>
                                    <td>{{ $custodio['fecha_entrega'] }}</td>
                                    <td>{{ $custodio['canton']['canton'] }}</td>
                                    <td>{{ $custodio['entrega']['empleado']['nombres'] }}
                                        {{ $custodio['entrega']['empleado']['apellidos'] }}</td>
                                    <td>{{ $custodio['responsable']['empleado']['nombres'] }}
                                        {{ $custodio['responsable']['empleado']['apellidos'] }}</td>
                                    <td colspan="2">{{ $custodio['observacion_entrega'] }}</td>
                                    <td>{{ $custodio['estado'] }}</td>
                                </tr>
                            @endforeach
                        @endif
                        <tr>
                            <td></td>
                        </tr>
                        {{-- HISTORIAL DE MANTENIMIENTOS --}}
                        @if ($reporte['mantenimientos'])
                            <tr>
                                <td colspan="7" style="background-color: #DBDBDF;">
                                    <strong>3. MANTENIMIENTOS REALIZADOS</strong>
                                </td>
                            </tr>
                            @if ($reporte['mantenimientos']['programados'])
                                <tr>
                                    <td colspan="7" style="background-color: #DBDBDF;">
                                        <strong>3.1. PROGRAMADOS/PREVENTIVOS/PERIODICOS</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="background-color: #DBDBDB"><strong>EMPLEADO REALIZA</strong></td>
                                    <td style="background-color: #DBDBDB"><strong>FECHA REALIZADO</strong></td>
                                    <td style="background-color: #DBDBDB"><strong>SERVICIO</strong></td>
                                    <td style="background-color: #DBDBDB"><strong>KM REALIZADO</strong></td>
                                    <td style="background-color: #DBDBDB"><strong>ESTADO</strong></td>
                                    <td style="background-color: #DBDBDB"><strong>KM RETRASADO</strong></td>
                                    <td style="background-color: #DBDBDB"><strong>OBSERVACION</strong></td>
                                </tr>
                                @foreach ($reporte['mantenimientos']['programados'] as $mantenimiento)
                                    <tr>
                                        <td>{{ $mantenimiento['empleado']['nombres'] }}
                                            {{ $mantenimiento['empleado']['apellidos'] }}</td>
                                        <td>{{ $mantenimiento['fecha_realizado'] }}</td>
                                        <td>{{ $mantenimiento['servicio']['nombre'] }}</td>
                                        <td>{{ $mantenimiento['km_realizado'] }}</td>
                                        <td>{{ $mantenimiento['estado'] }}</td>
                                        <td>{{ $mantenimiento['km_retraso'] }}</td>
                                        <td>{{ $mantenimiento['observacion'] }}</td>
                                    </tr>
                                @endforeach
                                <tr></tr>
                            @endif
                            @if ($reporte['mantenimientos']['correctivos'])
                                <tr>
                                    <td colspan="7" style="background-color: #DBDBDF;">
                                        <strong>3.2. CORRECTIVOS</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="background-color: #DBDBDB"><strong>EMPLEADO SOLICITANTE</strong></td>
                                    <td style="background-color: #DBDBDB"><strong>FECHA</strong></td>
                                    <td style="background-color: #DBDBDB"><strong>SERVICIOS</strong></td>
                                    <td style="background-color: #DBDBDB"><strong>KM REALIZADO</strong></td>
                                    <td style="background-color: #DBDBDB"><strong>ESTADO</strong></td>
                                    <td colspan="2" style="background-color: #DBDBDB"><strong>OBSERVACION</strong>
                                    </td>
                                </tr>
                                @foreach ($reporte['mantenimientos']['correctivos'] as $mantenimiento)
                                    <tr>
                                        <td>{{ $mantenimiento['solicitante']['nombres'] }}
                                            {{ $mantenimiento['solicitante']['apellidos'] }}</td>
                                        <td>{{ $mantenimiento['fecha'] }}</td>
                                        <td>{{ $mantenimiento['servicios'] }}</td>
                                        <td>{{ $mantenimiento['km_realizado'] }}</td>
                                        <td>{{ $mantenimiento['autorizacion']['nombre'] }}</td>
                                        <td colspan="2">{{ $mantenimiento['observacion'] }}</td>
                                    </tr>
                                @endforeach
                                <tr></tr>
                            @endif
                        @endif
                        <tr>
                            <td></td>
                        </tr>
                        {{-- HISTORIAL DE INCIDENTES --}}
                        @if ($reporte['incidentes'])
                            <tr>
                                <td colspan="7" style="background-color: #DBDBDF;">
                                    <strong>4. INCIDENTES</strong>
                                </td>
                            </tr>
                            <tr>
                                <td style="background-color: #DBDBDB"><strong>FECHA</strong></td>
                                <td style="background-color: #DBDBDB"><strong>PERSONA REPORTA</strong></td>
                                <td style="background-color: #DBDBDB"><strong>TIPO</strong></td>
                                <td style="background-color: #DBDBDB"><strong>GRAVEDAD</strong></td>
                                <td colspan="2" style="background-color: #DBDBDB"><strong>DESCRIPCION</strong></td>
                                <td style="background-color: #DBDBDB"><strong>APLICA SEGURO</strong>
                                </td>
                            </tr>
                            @foreach ($reporte['incidentes'] as $incidente)
                                <tr>
                                    <td>{{ $incidente['fecha'] }}</td>
                                    <td>{{ $incidente['personaReporta']['nombres'] }}
                                        {{ $incidente['personaReporta']['apellidos'] }}</td>
                                    <td>{{ $incidente['tipo'] }}</td>
                                    <td>{{ $incidente['gravedad'] }}</td>
                                    <td colspan="2">{{ $incidente['descripcion'] }}</td>
                                    <td>{{ $incidente['aplica_seguro'] ? 'SI' : 'NO' }}</td>
                                </tr>
                            @endforeach
                        @endif
                        <tr>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="7" style="background-color: #DBDBDF;">
                                <strong>5. ALERTAS DEL VEHICULO</strong>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>

    </table>


</body>

</html>
