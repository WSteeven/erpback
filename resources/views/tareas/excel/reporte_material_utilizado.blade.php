<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>C. Alcohol y Drogas</title>
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
                <td colspan="{{ count($reporte['encabezado_egresos_bodega']) }}">EGRESOS BODEGA</td>
                <td colspan="{{ count($reporte['encabezado_subtareas']) }}">SUBTAREAS</td>
                <th rowspan="2" valign="center" bgcolor="#daf1f3">TOTAL UTILIZADO</th>
                <td colspan="{{ count($reporte['encabezado_transferencias_recibidas']) }}">TRANSFERENCIAS RECIBIDAS</td>
                <th rowspan="2" valign="center" bgcolor="#c6efce">TOTAL TRANSFERENCIAS RECIBIDAS</th>
                <th rowspan="2" valign="center" bgcolor="#ffeb9c">TOTAL TRANSFERENCIAS ENVIADAS</th>
            </tr>

            <tr>
                {{-- Egresos bodega --}}
                @foreach ($reporte['encabezado_egresos_bodega'] as $subtarea)
                    <th>{{ $subtarea['codigo_egreso'] }}</th>
                @endforeach

                {{-- Subtareas --}}
                @foreach ($reporte['encabezado_subtareas'] as $subtarea)
                    <th>{{ $subtarea['codigo_subtarea'] }}</th>
                @endforeach

                @foreach ($reporte['encabezado_transferencias_recibidas'] as $transferencia)
                    <th>{{ $transferencia['codigo_transferencia'] }}</th>
                @endforeach
            </tr>
        </thead>

        <tbody>
            @foreach ($reporte['todos_materiales'] as $material)
                <tr>
                    <td>{{ $material['detalle_id'] }}</td>
                    <td>{{ $material['detalle'] }}</td>
                    <td>{{ $material['unidad'] }}</td>
                    <td>{{ $material['cliente'] }}</td>

                    {{-- Egresos bodega --}}
                    @foreach ($reporte['encabezado_egresos_bodega'] as $egreso)
                        <td>{{ $service->obtenerCantidadMaterialPorEgreso($material['detalle_id'], $material['cliente_id'], $egreso['id']) }}
                        </td>
                    @endforeach

                    {{-- Subtareas --}}
                    @foreach ($reporte['encabezado_subtareas'] as $subtarea)
                        <td>{{ $service->obtenerCantidadMaterialPorSubtarea($material['detalle_id'], $material['cliente_id'], $subtarea['id']) }}
                        </td>
                    @endforeach

                    {{-- TOTAL UTILIZADO --}}
                    <td bgcolor="#daf1f3">
                        {{ $service->obtenerSumaMaterialPorDetalleCliente($material['detalle_id'], $material['cliente_id']) }}
                    </td>

                    {{-- Transferencias recibidas --}}
                    @foreach ($reporte['encabezado_transferencias_recibidas'] as $transferencia)
                        <td>{{ $service->obtenerCantidadMaterialPorTransferencia($material['detalle_id'], $material['cliente_id'], $transferencia['id']) }}
                        </td>
                    @endforeach

                    {{-- TOTAL TRANSFERENCIAS RECIBIDAS --}}
                    <td bgcolor="#c6efce">
                        {{ $service->obtenerSumaTransferenciasRecibidas($material['detalle_id'], $material['cliente_id']) }}
                    </td>

                    {{-- TOTAL TRANSFERENCIAS ENVIADAS --}}
                    <td bgcolor="#ffeb9c">
                        {{ $service->obtenerSumaTransferenciasEnviadas($material['detalle_id'], $material['cliente_id']) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
