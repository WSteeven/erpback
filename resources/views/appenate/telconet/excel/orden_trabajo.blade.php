@php
    use Carbon\Carbon;
    use Src\App\Appenate\Telconet\ProgresivaService;use Src\Shared\Utils;
@endphp
<table>
    <tr>
        <td colspan="7" style="text-align: center; font-weight: bold;">
            <img class="logo" src="{{ Utils::getImagePath($configuracion->logo_claro) }}" alt="JPCONSTRUCRED Logo"
                 width="80">
            ORDEN TRABAJO PARA CONSTRUCCIÓN DE REDES DE FIBRA ÓPTICA<br>
            FOR FO 01 Ver 03 (28-05-2021)
        </td>
    </tr>
    <tr>
        <td colspan="7">DATOS GENERALES</td>
    </tr>
    <tr>
        <td colspan="2">Orden de Trabajo No:</td>
        <td colspan="5">COLOCAR MANUALMENTE</td>
    </tr>
    <tr>
        <td colspan="2">FECHA INICIO:</td>
        <td colspan="1">{{ $progresiva->fecha_instalacion }}</td>
        <td colspan="2">FECHA FINALIZACIÓN:</td>
        <td colspan="2">{{ Carbon::parse($progresiva->metadatos['Entry']['CompleteTime'])->format('Y-m-d H:i:s') ?? '' }}</td>
    </tr>
    <tr>
        <td colspan="2">TAREA No:</td>
        <td colspan="1">{{ $progresiva->num_tarea ?? '' }}</td>
        <td colspan="2">CIUDAD:</td>
        <td colspan="2">{{ $progresiva->ciudad ?? '' }}</td>
    </tr>
    <!-- ... continúa con el resto de celdas ... -->
    <tr>
        <td colspan="2">DIRECCIÓN:</td>
        <td colspan="5">{{ $progresiva->ciudad ?? '' }}</td>
    </tr>
    <tr>
        <td colspan="2">PROYECTO / RUTA:</td>
        <td colspan="5">{{ $progresiva->ciudad ?? '' }}</td>
    </tr>
    <tr>
        <td colspan="2">TIPO DE INSTALACIÓN:</td>
        <td><strong>Subterranea:</strong></td>
        <td colspan="2"><strong>Aerea:</strong></td>
        <td colspan="2"><strong>Otros:</strong></td>
    </tr>
    <tr>
        <td colspan="2">TENDIDO DE FO:</td>
        <td></td>
        <td colspan="2">COLOCACIÓN DE CAJAS:</td>
        <td colspan="2">X</td>
    </tr>
    <tr>
        <td colspan="2">COORDINADOR:</td>
        <td></td>
        <td colspan="2">FISCALIZADOR</td>
        <td colspan="2">X</td>
    </tr>
    <tr>
        <td colspan="2">CONTRATISTA A CARGO:</td>
        <td COLSPAN="5"></td>
    </tr>


    <tr>
        <td colspan="7">TENDIDO</td>
    </tr>
    <tr>
        <td colspan="7">DATOS DE FIBRA</td>
    </tr>
    <tr>
        <td rowspan="2">TIPO DE FIBRA:</td>
        <td>SM:________</td>
        <td>MM:________</td>
        <td colspan="4">Cantidad Hilos: {{ $progresiva->hilos ?? '' }} HILOS</td>
    </tr>
    <tr>
        <td colspan="2">ADSS_____X F8_____ Droop_____</td>
        <td colspan="4">Retiro Mensajero: SI______ NO_____X</td>
    </tr>
    <tr>
        <td>TRAMO No:1</td>
        <td colspan="3">NOMBRE DE RUTA: {{ $progresiva->proyecto ?? '' }} </td>
        <td colspan="3">COD BOBINA: {{ $progresiva->cod_bobina ?? '' }}</td>
    </tr>
    <tr>
        <td>Marca Inicial:</td>
        <td>{{ $progresiva->mt_inicial ?? '' }}</td>
        <td>Marca Final:</td>
        <td>{{ $progresiva->mt_final ?? '' }}</td>
        <td>Subtotal:</td>
        <td colspan="2">{{ $progresiva->fo_instalada ?? '' }}</td>
    </tr>

    <tr>
        <th colspan="2">Coordenadas POSTE/POZO</th>
        <th>Coordenada X</th>
        <th>Coordenada Y</th>
        <th>Tipo de Herraje</th>
        <th colspan="2">Reserva/Manga/Caja</th>
    </tr>

    @foreach ($progresiva->registros ?? [] as $registro)
        @php $coordenadas = explode(' ', $registro['ubicacion_gps']); @endphp
        <tr>
            <td colspan="2">{{ ProgresivaService::castearNombrePosteEnProgresivas($registro) ?? '' }}</td>
            <td>{{ $coordenadas[0] ?? '' }}</td>
            <td>{{ $coordenadas[1] ?? '' }}</td>
            <td></td>
            <td colspan="2"></td>
        </tr>
    @endforeach
    <tr></tr>
    <tr></tr>
    <tr></tr>
    <tr>
        <th colspan="4" align="center"><strong>LISTADO DE MATERIALES UTILIZADOS</strong></th>
        <th colspan="3" align="center"><strong>RESUMEN DE TRABAJO</strong></th>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td colspan="2"></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td colspan="2"></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td colspan="2"></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td colspan="2"></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td colspan="2"></td>
        <td></td>
    </tr>
{{--    aqui va el total de fibra utilizada --}}
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td rowspan="5" colspan="2"></td>
        <td rowspan="5"></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>

    <tr>
        <td colspan="2"><strong>TOTAL FIBRA UTILIZADA:</strong></td>
        <td colspan="2"></td>
    </tr>
    <tr>
        <td colspan="7"><strong>NOTA:</strong></td>
    </tr>
</table>
