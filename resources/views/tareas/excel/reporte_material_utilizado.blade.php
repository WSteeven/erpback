<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {{-- <title>Material de Proyecto/Etapa/Tarea</title> --}}
</head>

<body>
    <table
        style="color:#000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px;margin-top: 20px;">
        <thead>
            <tr>
                <th rowspan="2" valign="center">{{ 'CÓDIGO DE ARTÍCULO' }}</th>
                <th rowspan="2" valign="center">{{ 'DESCRIPCIÓN' }}</th>
                <th rowspan="2" valign="center">{{ 'UNIDAD' }}</th>
                <th rowspan="2" valign="center">{{ 'CLIENTE' }}</th>
                <td colspan="{{ count($reporte['encabezado_preingresos']) }}">INGRESOS BODEGA</td>
                <th rowspan="2" valign="center" bgcolor="#daf1f3">RECIBIDO</th>
                <td colspan="{{ count($reporte['encabezado_egresos_bodega']) }}">EGRESOS BODEGA</td>
                <th rowspan="2" valign="center" bgcolor="#daf1f3">RECIBIDO</th>
                <td colspan="{{ count($reporte['encabezado_subtareas']) }}">SUBTAREAS</td>
                <th rowspan="2" valign="center" bgcolor="#daf1f3">TOTAL UTILIZADO</th>
                <td colspan="{{ count($reporte['encabezado_transferencias_recibidas']) }}">TRANSFERENCIAS RECIBIDAS</td>
                <th rowspan="2" valign="center" bgcolor="#c6efce">TOTAL TRANSFERENCIAS RECIBIDAS</th>
                <th rowspan="2" valign="center" bgcolor="#ffeb9c">TOTAL TRANSFERENCIAS ENVIADAS</th>
                <th rowspan="2" valign="center" bgcolor="#ffeb9c">DEVOLUCIONES</th>
            </tr>

            <tr>
                {{-- Ingresos bodega --}}
                @if (count($reporte['encabezado_preingresos']))
                    @foreach ($reporte['encabezado_preingresos'] as $encabezado)
                        <th>{{ $encabezado['codigo_ingreso'] }}</th>
                    @endforeach
                @else
                    <td>{{ '-' }}</td>
                @endif

                {{-- Egresos bodega --}}
                @if (count($reporte['encabezado_egresos_bodega']))
                    @foreach ($reporte['encabezado_egresos_bodega'] as $encabezado)
                        <th>{{ $encabezado['codigo_egreso'] }}</th>
                    @endforeach
                @else
                    <td>{{ '-' }}</td>
                @endif

                {{-- Subtareas --}}
                @if (count($reporte['encabezado_subtareas']))
                    @foreach ($reporte['encabezado_subtareas'] as $encabezado)
                        <th>{{ $encabezado['codigo_subtarea'] }}</th>
                    @endforeach
                @else
                    <td>{{ '-' }}</td>
                @endif

                @if (count($reporte['encabezado_transferencias_recibidas']))
                    @foreach ($reporte['encabezado_transferencias_recibidas'] as $encabezado)
                        <th>{{ $encabezado['codigo_transferencia'] }}</th>
                    @endforeach
                @else
                    <td>{{ '-' }}</td>
                @endif

                {{-- Devoluciones --}}
                @if (count($reporte['encabezado_devoluciones']))
                    @foreach ($reporte['encabezado_devoluciones'] as $encabezado)
                        <th>{{ $encabezado['codigo_devolucion'] }}</th>
                    @endforeach
                @else
                    <td>{{ '-' }}</td>
                @endif
            </tr>
        </thead>

        <tbody>
            @foreach ($reporte['todos_materiales'] as $material)
                <tr>
                    <td>{{ $material['detalle_id'] }}</td>
                    <td>{{ $material['detalle'] }}</td>
                    <td>{{ $material['unidad'] }}</td>
                    <td>{{ $material['cliente'] }}</td>

                    {{-- Preingresos bodega --}}
                    @if (count($reporte['encabezado_preingresos']))
                        @foreach ($reporte['encabezado_preingresos'] as $ingreso)
                            <td>{{ $service->obtenerCantidadMaterialPorPreingreso($material['detalle_id'], $material['cliente_id'], $ingreso['id']) }}
                            </td>
                        @endforeach
                    @else
                        <td>{{ '-' }}</td>
                    @endif

                    {{-- TOTAL INGRESO BODEGA (RECIBIDO) --}}
                    @if (count($reporte['encabezado_preingresos']))
                        <td bgcolor="#daf1f3">
                            {{ $service->obtenerSumaMaterialPorPreingreso($material['detalle_id'], $material['cliente_id']) }}
                        </td>
                    @else
                        <td bgcolor="#daf1f3">{{ '-' }}</td>
                    @endif

                    {{-- Egresos bodega --}}
                    @if (count($reporte['encabezado_egresos_bodega']))
                        @foreach ($reporte['encabezado_egresos_bodega'] as $egreso)
                            <td>{{ $service->obtenerCantidadMaterialPorEgreso($material['detalle_id'], $material['cliente_id'], $egreso['id']) }}
                            </td>
                        @endforeach
                    @else
                        <td>{{ '-' }}</td>
                    @endif

                    {{-- TOTAL EGRESO BODEGA (RECIBIDO) --}}
                    <td bgcolor="#daf1f3">
                        {{ $service->obtenerSumaMaterialPorEgreso($material['detalle_id'], $material['cliente_id']) }}
                    </td>

                    {{-- Subtareas --}}
                    @if (count($reporte['encabezado_subtareas']))
                        @foreach ($reporte['encabezado_subtareas'] as $subtarea)
                            <td>{{ $service->obtenerCantidadMaterialPorSubtarea($material['detalle_id'], $material['cliente_id'], $subtarea['id']) }}
                            </td>
                        @endforeach
                    @else
                        <td>{{ '-' }}</td>
                    @endif

                    {{-- TOTAL UTILIZADO --}}
                    <td bgcolor="#daf1f3">
                        {{ $service->obtenerSumaMaterialPorDetalleCliente($material['detalle_id'], $material['cliente_id']) }}
                    </td>

                    {{-- Transferencias recibidas --}}
                    @if (count($reporte['encabezado_transferencias_recibidas']))
                        @foreach ($reporte['encabezado_transferencias_recibidas'] as $transferencia)
                            <td>{{ $service->obtenerCantidadMaterialPorTransferencia($material['detalle_id'], $material['cliente_id'], $transferencia['id']) }}
                            </td>
                        @endforeach
                    @else
                        <td>{{ '-' }}</td>
                    @endif

                    {{-- TOTAL TRANSFERENCIAS RECIBIDAS --}}
                    <td bgcolor="#c6efce">
                        {{ $service->obtenerSumaTransferenciasRecibidas($material['detalle_id'], $material['cliente_id']) }}
                    </td>

                    {{-- TOTAL TRANSFERENCIAS ENVIADAS --}}
                    <td bgcolor="#ffeb9c">
                        {{ $service->obtenerSumaTransferenciasEnviadas($material['detalle_id'], $material['cliente_id']) }}
                    </td>

                    @if (count($reporte['encabezado_devoluciones']))
                        @foreach ($reporte['encabezado_devoluciones'] as $devolucion)
                            <td>{{ $service->obtenerCantidadMaterialPorDevolucion($material['detalle_id'], $material['cliente_id'], $devolucion['id']) }}
                            </td>
                        @endforeach
                    @else
                        <td>{{ '-' }}</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
