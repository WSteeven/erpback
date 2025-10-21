<!DOCTYPE html>
<html lang="es" xmlns="http://www.w3.org/1999/html">
{{-- Aquí codigo PHP --}}
@php
    use Src\Shared\Utils;
    use App\Models\Empleado;
    $fecha = new Datetime();
@endphp

<head>
    <meta charset="utf-8">
    <title>Desvinculación de Empleado</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
            background-image: url({{ Utils::urlToBase64(url($configuracion->logo_marca_agua)) }});
            background-size: 50% auto;
            background-repeat: no-repeat;
            background-position: center;
        }

        h1, h2, h3 {
            color: #003366;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header img {
            width: 130px;
            margin-bottom: 10px;
        }

        .section {
            margin-bottom: 20px;
        }

        .section-title {
            background: #003366;
            color: white;
            padding: 6px 10px;
            font-weight: bold;
            border-radius: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 6px;
            text-align: left;
        }

        .suggestion {
            background: #f7faff;
            border-left: 4px solid #0074D9;
            padding: 8px;
            margin-top: 5px;
            font-size: 11px;
        }

        .badge-ok {
            background: #28a745;
            color: white;
            padding: 2px 6px;
            border-radius: 4px;
        }

        .badge-warning {
            background: #e3cc2b;
            color: white;
            padding: 2px 6px;
            border-radius: 4px;
        }

        .badge-alert {
            background: #dc3545;
            color: white;
            padding: 2px 6px;
            border-radius: 4px;
        }
    </style>
</head>


<body>
<header>
    <table
        style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10pt;" border="0" >
        <tr class="row" style="width:auto">
            <td>
                <div class="header">
                    <img src="{{ Utils::urlToBase64(url($configuracion->logo_claro)) }}" alt="logo"
                         width="90">
                    <h1>Resumen de Desvinculación</h1>
                </div>
            </td>
        </tr>
    </table>
</header>
<footer>
</footer>

<!-- aqui va el contenido del document<br><br>o -->
<main>
    <p><strong>Empleado:</strong> {{ Empleado::extraerNombresApellidos($empleado) }}</p>
    <p><strong>Motivo:</strong> {{ $empleado->motivo_desvinculacion }}</p>
    <p><strong>Fecha de salida:</strong> {{ $empleado->fecha_salida }}</p>
    {{--    <p><strong>Fecha de salida:</strong> {{ $empleado->fecha_salida->format('Y-m-d') }}</p>--}}

    <hr>

    {{-- Sección de préstamos --}}
    <div class="section">
        <div class="section-title">Préstamos empresariales activos</div>
        @php $prestamos = $resumen['prestamos_empresariales_activos'] ?? []; @endphp
        @if(isset($prestamos['cantidad_prestamos']) && $prestamos['cantidad_prestamos'] > 0)
            <p><span class="badge-alert">Pendiente</span> Existen {{ $prestamos['cantidad_prestamos'] }} préstamo(s) con
                saldo total de ${{ number_format($prestamos['saldo_acumulado'], 2) }}.</p>
            <div class="suggestion">
                <strong>Sugerencia:</strong> Solicitar la regularización del saldo con Contabilidad antes de finalizar
                el proceso.
            </div>
        @else
            <p><span class="badge-ok">Sin préstamos activos</span></p>
        @endif
    </div>

    {{-- Sección de descuentos --}}
    <div class="section">
        <div class="section-title">Descuentos activos</div>
        @php $desc = $resumen['descuentos_activos'] ?? []; @endphp
        @if(isset($desc['cantidad_descuentos']) && $desc['cantidad_descuentos'] > 0)
            <p><span class="badge-alert">Pendiente</span> Se encontraron {{ $desc['cantidad_descuentos'] }} descuentos
                activos.</p>
            <div class="suggestion">
                <strong>Sugerencia:</strong> Confirmar con Talento Humano el plan de descuentos o deducciones finales en
                rol de salida.
            </div>
        @else
            <p><span class="badge-ok">Sin descuentos pendientes</span></p>
        @endif
    </div>

    {{-- Sección de gastos --}}
    <div class="section">
        <div class="section-title">Gastos del empleado pendientes de aprobación</div>
        @if(!empty($resumen['gastos_pendientes_aprobacion']) && count($resumen['gastos_pendientes_aprobacion']) > 0)
            <p><span class="badge-alert">Pendiente</span> Se encontraron gastos aún no aprobados por el jefe inmediato o
                autorizador del gasto.</p>
            <div class="suggestion">
                <strong>Sugerencia:</strong> Revisar y aprobar o rechazar estos gastos antes de la liquidación para
                evitar retenciones de valores.
            </div>
        @else
            <p><span class="badge-ok">Sin gastos pendientes para que me aprueben </span></p>
        @endif
    </div>

    <div class="section">
        <div class="section-title">Gastos que requieren aprobación por parte del empleado saliente</div>
        @php
            $gastosMiAprobacion = $resumen['gastos_pendientes_mi_aprobacion'] ?? [];
        @endphp

        @if(count($gastosMiAprobacion) > 0)
            <p><span class="badge-alert">Pendiente</span> Se encontraron {{ count($gastosMiAprobacion) }} gasto(s) aún
                no aprobados al personal subordinado.</p>

            <table>
                <thead>
                <tr>
                    <th style="width:8%">ID</th>
                    <th style="width:15%">Fecha</th>
                    <th>Observación</th>
                    <th style="width:15%">Solicitante</th>
                    <th style="width:15%">Valor ($)</th>
                </tr>
                </thead>
                <tbody>
                @foreach($gastosMiAprobacion as $gasto)
                    <tr>
                        <td>{{ $gasto['id'] }}</td>
                        <td>{{ $gasto['fecha_viat'] }}</td>
                        <td>{{ Str::limit(strip_tags($gasto['observacion'] ?? '-'), 120) }}</td>
                        <td>{{ Empleado::extraerNombresApellidos(Empleado::find($gasto['id_usuario'])) }}</td>
                        <td>{{ number_format($gasto['total'], 2) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="suggestion">
                <strong>Sugerencia:</strong> El encargado de contabilidad debe revisar y aprobar o reasignar estos
                gastos del personal subordinado del empleado saliente al nuevo jefe o autorizador correspondiente.
            </div>
        @else
            <p><span class="badge-ok">Sin gastos pendientes para aprobar</span></p>
        @endif
    </div>

    {{-- Sección de transferencias pendientes --}}
    <div class="section">
        <div class="section-title">Transferencias pendientes</div>
        @php
            $transferenciasEnviadas = $resumen['transferencias_enviadas_pendientes'] ?? [];
            $transferenciasRecibidas = $resumen['transferencias_recibidas_pendientes'] ?? [];
            $totalTransferencias = count($transferenciasEnviadas) + count($transferenciasRecibidas);
        @endphp

        @if($totalTransferencias > 0)
            <p><span class="badge-alert">Pendiente</span>
                Se encontraron {{ $totalTransferencias }} transferencia(s) aún PENDIENTES.
            </p>

            {{-- Transferencias enviadas --}}
            @if(count($transferenciasEnviadas) > 0)
                <h4 style="margin-top: 10px;">Transferencias enviadas</h4>
                <table>
                    <thead>
                    <tr>
                        <th style="width:8%">ID</th>
                        <th style="width:12%">Fecha</th>
                        <th style="width:15%">Monto ($)</th>
                        <th style="width:20%">Destino</th>
                        <th>Motivo</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($transferenciasEnviadas as $t)
                        <tr>
                            <td>{{ $t['id'] }}</td>
                            <td>{{ $t['fecha'] ?? '-' }}</td>
                            <td>{{ number_format($t['monto'], 2) }}</td>
                            <td>{{ Empleado::extraerNombresApellidos(Empleado::find($t['usuario_recibe_id'])) }}</td>
                            <td>{{ Str::limit(strip_tags($t['observacion'] ?? $t['motivo'] ?? '-'), 80) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif

            {{-- Transferencias recibidas --}}
            @if(count($transferenciasRecibidas) > 0)
                <h4 style="margin-top: 15px;">Transferencias recibidas</h4>
                <table>
                    <thead>
                    <tr>
                        <th style="width:8%">ID</th>
                        <th style="width:12%">Fecha</th>
                        <th style="width:15%">Monto ($)</th>
                        <th style="width:20%">Origen</th>
                        <th>Motivo</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($transferenciasRecibidas as $t)
                        <tr>
                            <td>{{ $t['id'] }}</td>
                            <td>{{ $t['fecha'] ?? '-' }}</td>
                            <td>{{ number_format($t['monto'], 2) }}</td>
                            <td>{{ Empleado::extraerNombresApellidos(Empleado::find($t['usuario_envia_id'])) }}</td>
                            <td>{{ Str::limit(strip_tags($t['observacion'] ?? $t['motivo'] ?? '-'), 80) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif

            <div class="suggestion">
                <strong>Sugerencia:</strong>
                Coordinar con el área de Contabilidad para validar el estado de las transferencias
                antes de la desvinculación del empleado.
                Cerrar o reasignar aquellas que se encuentren pendientes.
            </div>
        @else
            <p><span class="badge-ok">Sin transferencias pendientes</span></p>
        @endif
    </div>


    {{-- Sección de órdenes de compra --}}
    {{--    pendientes de autorización--}}
    <div class="section">
        <div class="section-title">Órdenes de compra pendientes de autorización</div>
        @php
            $pendientesOC = count($resumen['ordenes_compras_pendientes_autorizacion'] ?? []);
        @endphp
        @if($pendientesOC > 0)
            <p><span class="badge-alert">Pendiente</span> Existen {{ $pendientesOC }} órdenes de compra pendientes de
                autorización.</p>
            <div class="suggestion">
                <strong>Sugerencia:</strong> Coordinar con el área de Compras para que autorice o anule las órdenes de
                compra del empleado saliente.
            </div>
        @else
            <p><span class="badge-ok">Sin órdenes de compra pendientes de autorización</span></p>
        @endif
    </div>
    {{--    pendientes de revision compras --}}
    <div class="section">
        <div class="section-title">Órdenes de compra autorizadas pendientes de revisión por departamento de <strong>COMPRAS</strong>.
        </div>
        @if(count($resumen['ordenes_compras_pendientes_revision_compras'] ?? []) > 0)
            <p><span class="badge-alert">Pendiente</span>
                Existen {{ count($resumen['ordenes_compras_pendientes_revision_compras']??[]) }} órdenes de compra
                pendientes de
                de revisión por el departamento de compras.</p>
            <div class="suggestion">
                <strong>Sugerencia:</strong> Coordinar con el área de Compras para que revise y realice las ordenes de
                compra.
            </div>
        @else
            <p><span class="badge-ok">Sin órdenes de compra pendientes de revisión por departamento de compras</span>
            </p>
        @endif
    </div>
    {{--    pendientes de realizar --}}
    <div class="section">
        <div class="section-title">Órdenes de compra pendientes de realizar</div>
        @php
            $pendientesOC = count($resumen['ordenes_compras_pendientes_realizar'] ?? []);
        @endphp
        @if($pendientesOC > 0)
            <p><span class="badge-alert">Pendiente</span> Existen {{ $pendientesOC }} órdenes de compra pendientes de
                realizar.</p>
            <div class="suggestion">
                <strong>Sugerencia:</strong> Coordinar con el área de Compras para que las órdenes aprobadas y revisadas
                puedan ser realizadas o marcadas como realizadas según corresponda.
            </div>
        @else
            <p><span class="badge-ok">Sin órdenes de compra pendientes de realizar</span></p>
        @endif
    </div>

    {{-- Sección de vehículos --}}
    <div class="section">
        <div class="section-title">Vehículos asignados</div>
        @if(count($resumen['vehiculos_asignados'])>0)
            <p><span class="badge-alert">Pendiente</span> Existen vehículos registrados bajo responsabilidad del
                empleado.</p>
            <div class="suggestion">
                <strong>Sugerencia:</strong> Verificar entrega de llaves, documentos y estado físico de los vehículos
                con el área de Logística.
            </div>
        @else
            <p><span class="badge-ok">Sin vehículos asignados</span></p>
        @endif
    </div>

    {{-- Fondos rotativos --}}
    <div class="section">
        <div class="section-title">Fondos rotativos</div>
        <p>Saldo actual: <strong>${{ number_format($resumen['saldo_fondos_rotativos'] ?? 0, 2) }}</strong></p>
        @if(($resumen['saldo_fondos_rotativos'] ?? 0) > 0)
            <div class="suggestion">
                <strong>Sugerencia:</strong> El empleado debe realizar la devolución o regularización del fondo antes de
                su salida.
            </div>
        @endif
    </div>

    {{-- Tickets pendientes --}}
    @php
        $tickets = $resumen['tickets'] ?? [];
        $totalTickets = collect($tickets)->flatten(1)->count();
        $solicitados = $tickets['tickets_solicitados_empleado'] ?? [];
        $reasignados = $tickets['tickets_reasignados'] ?? [];
        $ejecutando = $tickets['ticket_ejecutando'] ?? [];
        $pausados = $tickets['tickets_pausados'] ?? [];
    @endphp

    <div class="section">
        <div class="section-title">Tickets pendientes</div>

        @if($totalTickets > 0)
            <p><span class="badge-alert">Pendiente</span> Se encontraron tickets activos relacionados con el empleado.
            </p>

            <table>
                <thead>
                <tr>
                    <th>N°</th>
                    <th style="width: 8%">Código</th>
                    <th>Asunto</th>
                    <th style="width: 12%">Prioridad</th>
                    <th style="width: 20%">Responsable</th>
                    <th style="width: 12%">Estado</th>
                </tr>
                </thead>
                <tbody>
                @foreach($solicitados as $index=>$t)
                    <tr>
                        <td>{{ $index+1 }}</td>
                        <td>{{ $t['codigo'] ?: '-' }}</td>
                        <td>{{ Str::limit(strip_tags($t['asunto']), 70) }}</td>
                        <td>{{ ucfirst(strtolower($t['prioridad'])) }}</td>
                        <td>
                            {{Empleado::extraerNombresApellidos(Empleado::find($t['responsable_id']))}}
                        </td>
                        <td>{{ ucfirst(strtolower($t['estado'])) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <br>

            {{-- Subresumen --}}
            <table style="width: 70%; margin-top: 10px;">
                <thead>
                <tr>
                    <th>Categoría</th>
                    <th>Cantidad</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>Tickets solicitados a otros empleados</td>
                    <td>{{ count($solicitados) }}</td>
                </tr>
                <tr>
                    <td>Mis tickets reasignados</td>
                    <td>{{ count($reasignados) }}</td>
                </tr>
                <tr>
                    <td>Mis tickets ejecutando</td>
                    <td>{{ count($ejecutando) }}</td>
                </tr>
                <tr>
                    <td>Mis tickets pausados</td>
                    <td>{{ count($pausados) }}</td>
                </tr>
                <tr style="font-weight: bold;">
                    <td>Total general</td>
                    <td>{{ $totalTickets }}</td>
                </tr>
                </tbody>
            </table>

            <div class="suggestion">
                <strong>Sugerencias de acción:</strong>
                <ul style="margin-top: 4px; padding-left: 20px;">
                    <li>Revisar los tickets <b>reasignados</b> y confirmar su cierre con el nuevo responsable.</li>
                    <li>Coordinar con el área a la que pertenecía el <b>Empleado Saliente</b> la transferencia de los
                        tickets <b>en
                            ejecución</b>.
                    </li>
                    <li>Validar con el supervisor los motivos de los <b>tickets pausados</b> y si pueden ser cerrados.
                    </li>
                    <li>Generar un reporte de cierre de tickets solicitados por el empleado antes de procesar la
                        desvinculación.
                    </li>
                </ul>
            </div>

        @else
            <p><span class="badge-ok">Sin tickets pendientes</span></p>
        @endif
    </div>

    {{-- Tareas --}}
    <div class="section">
        <div class="section-title">Tareas pendientes</div>
        @php
            $tareasPendientes = $resumen['tareas_pendientes']['tareas_pendientes'] ?? [];
        @endphp
        @if(count($tareasPendientes)>0)
            <p><span class="badge-alert">Pendiente</span> Se detectaron {{count($tareasPendientes)}} tareas en curso.
            </p>

            {{-- RESUMEN --}}
            <table>
                <thead>
                <tr>
                    <th>N°</th>
                    <th style="width: 8%">Código</th>
                    <th>Título</th>
                    <th style="width: 12%">Fecha Solicitud</th>
                    <th style="width: 20%">Responsable</th>
                    <th style="width: 12%">Finalizada</th>
                </tr>
                </thead>
                <tbody>
                @foreach($tareasPendientes as $index=>$t)
                    <tr>
                        <td>{{ $index+1 }}</td>
                        <td>{{ $t['codigo_tarea'] ?: '-' }}</td>
                        <td>{{ Str::limit(strip_tags($t['titulo']), 70) }}</td>
                        <td>{{ ucfirst(strtolower($t['fecha_solicitud'])) }}</td>
                        <td>
                            {{Empleado::extraerNombresApellidos($empleado)}}
                        </td>
                        <td>{{ $t['finalizado']?'SI':'NO' }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="suggestion">
                <strong>Sugerencia:</strong> Transferir la propiedad de las tareas a otro responsable o solicita al jefe
                técnico que las finalice.
            </div>
        @else
            <p><span class="badge-ok">Sin tareas pendientes</span></p>
        @endif
    </div>

    {{-- Departamento responsable --}}
    <div class="section">
        <div class="section-title">Responsable de Departamento</div>
        @if(!is_null($resumen['departamento_responsable']))
            <p><span class="badge-alert">Pendiente</span> El empleado saliente es responsable del departamento
                <strong>{{$resumen['departamento_responsable'][0]['nombre']}}</strong>.
            </p>

            <div class="suggestion">
                <strong>Sugerencia:</strong> Cambiar el responsable del
                departamento a la nueva persona a cargo.
            </div>
        @else
            <p><span class="badge-ok">No es responsable de departamento</span></p>
        @endif
    </div>

    {{-- Grupo lidera --}}
    <div class="section">
        <div class="section-title">Grupo perteneciente</div>
        @if(!is_null($resumen['grupo_lidera']))
            <p><span class="badge-warning">Es líder de grupo</span> El empleado saliente es líder del grupo
                <strong>{{$resumen['grupo_lidera']['grupo']}}</strong>.
            </p>

            <div class="suggestion">
                <strong>Sugerencia:</strong> Cambiar el rol de líder de grupo a otra persona o desactivar el grupo según
                corresponda.
            </div>
        @else
            <p><span class="badge-ok">No es líder de grupo</span></p>
        @endif
    </div>

    <hr>
    <p style="font-size: 10px; color: #777; text-align: center;">
        Este reporte fue generado automáticamente por FIRSTRED ERP.
    </p>

</main>


</body>

</html>
