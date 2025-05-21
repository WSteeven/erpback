@php
    use Src\Shared\Utils;
@endphp
<table>
    <tr>
        <td colspan="8" style="text-align: center; font-weight: bold;">
            <img  class="logo" src="{{ Utils::getImagePath($configuracion->logo_claro) }}" alt="JPCONSTRUCRED Logo" style="height: 20px;">
            ORDEN TRABAJO PARA CONSTRUCCIÓN DE REDES DE FIBRA ÓPTICA<br>
            FOR FO 01 Ver 03 (28-05-2021)
        </td>
    </tr>
    <tr><td colspan="8">DATOS GENERALES</td></tr>
    <tr>
        <td>Orden de Trabajo No:</td>
        <td colspan="2">COLOCAR MANUALMENTE</td>
        <td>FECHA INICIO:</td>
        <td colspan="2">{{ $progresiva->fecha_instalacion }}</td>
        <td>FECHA FINALIZACIÓN:</td>
        <td>{{ $progresiva->metadatos['Entry']['CompleteTime'] ?? '' }}</td>
    </tr>
    <tr>
        <td>TAREA No:</td>
        <td colspan="2">{{ $progresiva->num_tarea ?? '' }}</td>
        <td>CIUDAD:</td>
        <td colspan="4">{{ $progresiva->ciudad ?? '' }}</td>
    </tr>
    <!-- ... continúa con el resto de celdas ... -->

    <tr>
        <td colspan="8">TENDIDO - DATOS DE FIBRA</td>
    </tr>
    <tr>
        <td colspan="8">
            TIPO DE FIBRA: SM:_____ MM:_____ Cantidad Hilos: {{ $progresiva->hilos ?? '' }} HILOS
        </td>
    </tr>
    <tr>
        <td colspan="8">
            TRAMO No1 - {{ $progresiva->proyecto ?? '' }} / COD BOBINA: {{ $progresiva->cod_bobina ?? '' }}
        </td>
    </tr>
    <tr>
        <td>Marca Inicial:</td>
        <td>{{ $progresiva->mt_inicial ?? '' }}</td>
        <td>Marca Final:</td>
        <td>{{ $progresiva->mt_final ?? '' }}</td>
        <td>Subtotal:</td>
        <td colspan="3">{{ $progresiva->fo_instalada ?? '' }}</td>
    </tr>

    <tr>
        <th>Coordenadas POSTE/POZO</th>
        <th>Coordenada X</th>
        <th>Coordenada Y</th>
        <th>Tipo de Herraje</th>
        <th colspan="4">Reserva/Manga/Caja</th>
    </tr>

    @foreach ($progresiva->registros ?? [] as $registro)
        @php $coordenadas = explode(' ', $registro['ubicacion_gps']); @endphp
        <tr>
            <td>{{ $registro['num_elemento'] ?? '' }}</td>
            <td>{{ $coordenadas[0] ?? '' }}</td>
            <td>{{ $coordenadas[1] ?? '' }}</td>
            <td>{{ $registro['tipo_poste'] ?? '' }}</td>
            <td colspan="4"></td>
        </tr>
    @endforeach
</table>
