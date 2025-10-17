<!DOCTYPE html>
<html lang="es">
{{-- Aquí codigo PHP --}}
@php
    use Src\Shared\Utils;
    use App\Models\Empleado;
    $fecha = new Datetime();
@endphp

<head>
    <meta charset="utf-8">
    <title>Desvinculación de Empleado</title>
    <meta charset="UTF-8">
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
        style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10pt;">
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
        @if(!empty($resumen['gastos_pendientes_aprobacion']))
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
        <div class="section-title">Gastos pendientes de aprobación por parte del empleado</div>
        @if(!empty($resumen['gastos_pendientes_mi_aprobacion']))
            <p><span class="badge-alert">Pendiente</span> Se encontraron gastos aún no aprobados al personal
                subordinado.</p>
            <div class="suggestion">
                <strong>Sugerencia:</strong> El encargado de contabilidad debe revisar y aprobar o rechazar o reasignar
                estos gastos del personal subordinado del empleado saliente al nuevo jefe a cargo o jefe inmediato
                superior.
            </div>
        @else
            <p><span class="badge-ok">Sin gastos pendientes para aprobar</span></p>
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
                <strong>Sugerencia:</strong> Coordinar con el área de Compras para que autorice o anule las órdenes de compra del empleado saliente.
            </div>
        @else
            <p><span class="badge-ok">Sin órdenes de compra pendientes de autorización</span></p>
        @endif
    </div>
    {{--    pendientes de revision compras --}}
    <div class="section">
        <div class="section-title">Órdenes de compra autorizadas pendientes de revisión por departamento de <strong>COMPRAS</strong>.</div>
        @if(count($resumen['ordenes_compras_pendientes_revision_compras'] ?? []) > 0)
            <p><span class="badge-alert">Pendiente</span> Existen {{ count($resumen['ordenes_compras_pendientes_revision_compras']??[]) }} órdenes de compra pendientes de
                de revisión por el departamento de compras.</p>
            <div class="suggestion">
                <strong>Sugerencia:</strong> Coordinar con el área de Compras para que revise y realice las ordenes de compra.
            </div>
        @else
            <p><span class="badge-ok">Sin órdenes de compra pendientes de revisión por departamento de compras</span></p>
        @endif
    </div>
    {{--    pendientes de realizar --}}
    <div class="section">
        <div class="section-title">Órdenes de compra pendientes de realizar</div>
        @php
            $pendientesOC = + count($resumen['ordenes_compras_pendientes_realizar'] ?? []);
        @endphp
        @if($pendientesOC > 0)
            <p><span class="badge-alert">Pendiente</span> Existen {{ $pendientesOC }} órdenes de compra pendientes de
                realizar.</p>
            <div class="suggestion">
                <strong>Sugerencia:</strong> Coordinar con el área de Compras para que las órdenes aprobadas y revisadas puedan ser realizadas o marcadas como realizadas según corresponda.
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
    <div class="section">
        <div class="section-title">Tickets pendientes</div>
        @if(!empty($resumen['tickets']))
            <p><span class="badge-alert">Pendiente</span> Se detectaron tickets en curso.</p>
            <div class="suggestion">
                <strong>Sugerencia:</strong> Transferir la propiedad de los tickets a otro responsable o cerrar los
                tickets abiertos.
            </div>
        @else
            <p><span class="badge-ok">Sin tickets pendientes</span></p>
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
            <p><span class="badge-alert">Pendiente</span> Se encontraron tickets activos relacionados con el empleado.</p>

            <table>
                <thead>
                <tr>
                    <th style="width: 8%">Código</th>
                    <th>Asunto</th>
                    <th style="width: 12%">Prioridad</th>
                    <th style="width: 12%">Estado</th>
                    <th style="width: 20%">Fecha límite</th>
                </tr>
                </thead>
                <tbody>
                @foreach($solicitados as $t)
                    <tr>
                        <td>{{ $t['codigo'] ?: '-' }}</td>
                        <td>{{ Str::limit(strip_tags($t['asunto']), 70) }}</td>
                        <td>{{ ucfirst(strtolower($t['prioridad'])) }}</td>
                        <td>{{ ucfirst(strtolower($t['estado'])) }}</td>
                        <td>
                            {{ $t['fecha_hora_limite'] ? \Carbon\Carbon::parse($t['fecha_hora_limite'])->format('d/m/Y') : 'Sin fecha' }}
                        </td>
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
                    <td>Tickets solicitados</td>
                    <td>{{ count($solicitados) }}</td>
                </tr>
                <tr>
                    <td>Tickets reasignados</td>
                    <td>{{ count($reasignados) }}</td>
                </tr>
                <tr>
                    <td>Tickets ejecutando</td>
                    <td>{{ count($ejecutando) }}</td>
                </tr>
                <tr>
                    <td>Tickets pausados</td>
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
                    <li>Coordinar con el área de <b>Soporte o Proyectos</b> la transferencia de los tickets <b>en ejecución</b>.</li>
                    <li>Validar con el supervisor los motivos de los <b>tickets pausados</b> y si pueden ser cerrados.</li>
                    <li>Generar un reporte de cierre de tickets solicitados por el empleado antes de procesar la desvinculación.</li>
                </ul>
            </div>

        @else
            <p><span class="badge-ok">Sin tickets pendientes</span></p>
        @endif
    </div>

 {{-- Tickets y tareas --}}
    <div class="section">
        <div class="section-title">Tickets y tareas pendientes</div>
        @if(!empty($resumen['tickets']) || !empty($resumen['tareas_pendientes']))
            <p><span class="badge-alert">Pendiente</span> Se detectaron tareas o tickets en curso.</p>
            <div class="suggestion">
                <strong>Sugerencia:</strong> Transferir la propiedad de las tareas a otro responsable y cerrar los
                tickets abiertos.
            </div>
        @else
            <p><span class="badge-ok">Sin pendientes operativos</span></p>
        @endif
    </div>

    {{-- Departamento responsable --}}
    <div class="section">
        <div class="section-title">Departamento responsable del cierre</div>
        <p>{{ strtoupper($resumen['departamento_responsable'] ?? 'NO DEFINIDO') }}</p>
    </div>

    <hr>
    <p style="font-size: 10px; color: #777; text-align: center;">
        Este reporte fue generado automáticamente por el sistema de gestión de empleados.
    </p>

</main>


</body>

</html>
