@php
    use App\Models\Empleado;
    use Src\Shared\Utils;
    $fecha = new Datetime();
@endphp
<table
    style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:18px;">
    <tr class="row" style="width:auto">
        <td style="width: 10%;">
            <div class="col-md-3"><img src="{{ Utils::getImagePath($configuracion->logo_claro) }}" width="90"
                                       alt="logo"></div>
        </td>
        <td colspan="8" style="width: 100%; text-align: center;">
            <b style="font-size: 75%;">REPORTE VALIJAS DEL {{$peticion['fecha_inicio']}} AL {{$peticion['fecha_fin']}}</b>
        </td>
    </tr>
</table>
<hr>
<table
    class="main-table">
    <thead>
        <tr>
        <th style="font-weight: bold">ID</th>
        <th style="font-weight: bold">Fecha</th>
        <th style="font-weight: bold">Gasto ID</th>
        <th style="font-weight: bold">N° Factura</th>
        <th style="font-weight: bold">Empleado Envía</th>
        <th style="font-weight: bold">Departamento Destino</th>
        <th style="font-weight: bold">Descripción</th>
        <th style="font-weight: bold">Destinatario</th>
        <th style="font-weight: bold">Imagen</th>
    </tr>
    </thead>
    <tbody>
    @foreach($reporte as $valija)
        <tr>
            <td>{{ $valija->id }}</td>
            <td>{{ $valija->created_at }}</td>
            <td>{{ $valija->gasto_id }}</td>
            <td>{{ $valija->gasto->factura ?? $valija->gasto->num_comprobante ?? '' }}</td>
            <td>{{ Empleado::extraerNombresApellidos($valija->empleado) }}</td>
            <td>{{ $valija->departamento?->nombre }}</td>
            <td>{{ $valija->descripcion }}</td>
            <td>{{ Empleado::extraerNombresApellidos($valija->destinatario) ?? Empleado::extraerNombresApellidos($valija->departamento->responsable) ?? 'N/A' }}</td>
            <td style="vertical-align: middle; overflow: hidden">
                @if($valija->imagen_evidencia)
                    <a href="{{ url($valija->imagen_evidencia) }}" target="_blank">
                        <img src="{{ Utils::getImagePath($valija->imagen_evidencia) }}" alt="Evidencia"
                             width="100">
                    </a>
                @else
                    Sin imagen
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
