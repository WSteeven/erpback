@php
    $materiales_unicos = $progresiva->materiales()->pluck('material')->unique()->values();
@endphp
<table>
    <thead>
    <tr>
        <td colspan="4" style="text-align: left;">NOMBRE DEL PROYECTO:</td>
        <td colspan="4">{{$progresiva->proyecto}}:</td>
        <td colspan="4" style="text-align: left;">CÓDIGO DE BOBINA:</td>
        <td colspan="2">{{$progresiva->cod_bobina}}:</td>
        <td>CANT. HILOS:</td>
        <td colspan="2">{{$progresiva->hilos}}</td>
    </tr>
    <tr>
        <td colspan="4" style="text-align: left;">ENLACE:</td>
        <td colspan="4">{{$progresiva->enlace}}:</td>
        <td colspan="4" style="text-align: left;">NÚMERO DE MT INICIAL:</td>
        <td colspan="2">{{$progresiva->mt_inicial}}:</td>
        <td colspan="3" rowspan="3" style="text-align: left; vertical-align: top;">RESPONSABLE:</td>
    </tr>
    <tr>
        <td colspan="4" style="text-align: left;">FECHA DE INSTALACIÓN:</td>
        <td colspan="4" style="text-align: left;">{{$progresiva->fecha_instalacion}}:</td>
        <td colspan="4" style="text-align: left;">NÚMERO DE MT FINAL:</td>
        <td colspan="2">{{$progresiva->mt_final}}:</td>
    </tr>
    <tr>
        <td colspan="4" style="text-align: left;">CANTIDAD DE FO INSTALADA:</td>
        <td colspan="4" style="text-align: left;">{{$progresiva->fo_instalada}}:</td>
        <td colspan="4" style="text-align: left;">TAREA N°:</td>
        <td colspan="2" style="text-align: left;">{{$progresiva->num_tarea}}:</td>
    </tr>
    <tr>
        <th rowspan="5">ELEMENTO</th>
        <th rowspan="5">PROPIETARIO</th>
        <th rowspan="5">MATERIAL</th>
        @foreach($materiales_unicos as $nombre_material)
            <th rowspan="5">{{$nombre_material}}</th>
        @endforeach
        <th rowspan="5" colspan="3">OBSERVACIONES</th>
    </tr>
    <tr></tr>
    <tr></tr>
    <tr></tr>
    <tr></tr>
    <tr>
        <th colspan="3">UNIDAD DE MEDIDA</th>
        @foreach($materiales_unicos as $nombre_material)
            <th>{{ str_contains($nombre_material, 'PROGRESIVA') || str_contains($nombre_material, 'CINTA') ? 'm' : 'u' }}</th>
        @endforeach
        <th colspan="3">u</th>
    </tr>
    </thead>
    <tbody>
    {{-- Aquí iteras con tus datos si es necesario --}}
    @foreach($progresiva->registros??[] as $registro)
        <tr>
            <td>{{ $registro->num_elemento }}</td>
            <td>{{ $registro->propietario }}</td>
            <td>{{ $registro->material_poste }}</td>
            @foreach($materiales_unicos as $material)
                @php
                    $mat = $registro->materiales->where('material', $material)->first();
                @endphp
                <td>{{$mat->cantidad??''}}</td>
            @endforeach
            <td colspan="3">{{ $registro->observaciones }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
