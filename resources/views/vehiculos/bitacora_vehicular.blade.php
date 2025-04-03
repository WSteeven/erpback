<!DOCTYPE html>
<html lang="en">
@php
    use App\Models\Empleado;
    use App\Models\Vehiculos\BitacoraVehicular;
    use Src\Shared\Utils;

        $fecha = new Datetime();

        $bitacoraModel = BitacoraVehicular::find($bitacora['id']);

        function determinarClase($valor)
        {
            switch ($valor) {
                case 'BUENO':
                    return 'verde'; // Clase CSS para el estado "correcto"
                case 'LLENO':
                    return 'verde'; // Clase CSS para el estado "correcto"
                case 'CORRECTO':
                    return 'verde'; // Clase CSS para el estado "correcto"
                case 'ADVERTENCIA':
                    return 'naranja'; // Clase CSS para el estado "advertencia"
                case 'CADUCADO':
                    return 'naranja'; // Clase CSS para el estado "advertencia"
                case 'PELIGRO':
                    return 'rojo'; // Clase CSS para el estado "peligro"
                case 'VACIO':
                    return 'rojo'; // Clase CSS para el estado "peligro"
                case 'MALO':
                    return 'rojo'; // Clase CSS para el estado "peligro"
                default:
                    return ''; // Clase CSS por defecto
            }
        }
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bitácora vehicular</title>
</head>

<style>
    @page {
        margin: 0 15px;
    }

    body {
        background-image: url({{ Utils::urlToBase64(url($configuracion->logo_marca_agua)) }});
        background-repeat: no-repeat;
        background-position: center;
        background-size: contain;

        /** Defina ahora los márgenes reales de cada página en el PDF **/
        margin: 3cm 1cm 2cm;

        /** Define el texto **/
        font-family: Arial, sans-serif;
    }

    /** Definir las reglas del encabezado **/
    header {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        height: 2cm;
        margin-top: 5px;

        /** Estilos extra personales **/
        text-align: center;
    }

    /** Definir las reglas del pie de página **/
    footer {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        margin-bottom: 5px;
        /* height: 2cm; */

        /** Estilos extra personales **/
        text-align: center;
        color: #000000;
    }

    footer .page:after {
        content: counter(page);
    }

    main {
        position: relative;
        font-size: 12px;
        left: 0;
        right: 0;

    }

    .firma {
        width: 100%;
        line-height: normal;
        font-size: 14px;
    }

    .justificado {
        text-align: justify;
        text-justify: inter-word;
        line-height: 0.6cm;
    }

    .header-title {
        font-weight: bold;
    }

    .custom-table th,
    .custom-table td {
        width: 25%;
    }

    /* cambios de colores según los estados */
    .verde {
        color: green;
        /* Color verde */
    }

    .naranja {
        color: orange;
        /* Color naranja */
    }

    .rojo {
        color: red;
        /* Color rojo */
    }
</style>

<body>
<header>
    <table
        style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:18px;">
        <tr class="row" style="width:auto">
            <td>
                <div class="col-md-3">
                    @if($configuracion->logo_claro)
                        <img src="{{ Utils::urlToBase64(url($configuracion->logo_claro)) }}" width="90" alt="Logo">
                    @endif
                </div>
            </td>
            <td>
                <div class="col-md-7" align="center"><b>CONTROL DE VEHICULOS</b></div>
            </td>
            <td>
                <div class="col-md-2" align="right">FIRSTRED</div>
            </td>
        </tr>
    </table>
</header>
<footer>
    {{-- <table class="firma" style="width: 100%;">
        <thead>
            <th align="center"></th>
            <th align="center">___________________</th>
            <th align="center"></th>
        </thead>
        <tbody>
            <tr align="center">
                <td><b></b></td>
                <td><b>RESPONSABLE</b></td>
                <td><b></b></td>
            </tr>
            <tr align="center">
                <td></td>
                <td style="padding-left: 60px;">{{ $bitacora['chofer'] }} </td>
                <td></td>
            </tr>
            <tr align="center">
                <td></td>
                <td style="padding-left: 60px;">{{ $chofer->identificacion }}</td>
                <td></td>
            </tr>
        </tbody>
    </table> --}}
    <table style="width: 100%;">
        <tr>
            <td style="line-height: normal;">
                <div
                    style="margin: 0%; margin-bottom: 0px; margin-top: 0px; margin-left: 80px;margin-right: 80px; font-size: 14px"
                    align="center">La
                    información contenida en este documento es confidencial y de uso exclusivo de
                    {{ $configuracion['razon_social'] }}
                </div>
                <div style="margin: 0%; margin-bottom: 0px; margin-top: 0px; font-size: 12px" align="center">
                    Generado por el
                    Usuario:
                    {{ auth('sanctum')->user()->empleado->nombres }}
                    {{ auth('sanctum')->user()->empleado->apellidos }} el
                    {{ $fecha->format('d/m/Y H:i') }}
                </div>
            </td>
            <td>
            </td>
        </tr>
    </table>
</footer>

{{-- Cuerpo del documento --}}
<main>
    <table style="width: 100%; border: #000000; border-collapse: collapse;" border="1">
        <tr>
            <td class="header-title">N° BITACORA</td>
            <td>{{ $bitacora['id'] }}</td>
            <td class="header-title">TIPO VEHICULO</td>
            <td>{{ $vehiculo['tipo_vehiculo'] }}</td>
            <td class="header-title">MARCA</td>
            <td>{{ $vehiculo['marca'] }}</td>
        </tr>
        <tr>
            <td class="header-title">MODELO</td>
            <td>{{ $vehiculo['modelo'] }}</td>
            <td class="header-title">PLACA</td>
            <td>{{ $vehiculo['placa'] }}</td>
            <td class="header-title">FECHA INICIO</td>
            <td>{{ $bitacora['fecha'] }} {{ $bitacora['hora_salida'] }}</td>
        </tr>
        <tr>
            <td class="header-title">FECHA FIN</td>
            <td>
                @if ($bitacora['hora_llegada'] < $bitacora['hora_salida'])
                    @php
                        $fechaBitacora = new Datetime($bitacora['fecha']);
                        $nuevaFecha = $fechaBitacora->modify('+1 day');
                        $nuevaFecha = $nuevaFecha->format('Y-m-d');
                    @endphp
                    {{ $nuevaFecha }}{{ $bitacora['hora_llegada'] }}
                @else
                    {{ $bitacora['fecha'] }} {{ $bitacora['hora_llegada'] }}
                @endif
            </td>
            <td class="header-title">TANQUE INICIO</td>
            <td>{{ $bitacora['tanque_inicio'] }} %</td>
            <td class="header-title">TANQUE FIN</td>
            <td>{{ $bitacora['tanque_final'] }} %</td>
        </tr>
        <tr>
            <td colspan="2" class="header-title">CHOFER RESPONSABLE</td>
            <td colspan="2">{{ $bitacora['chofer'] }}</td>
            <td class="header-title">FINALIZADA</td>
            <td>{{ $bitacora['firmada'] ? 'SI' : 'NO' }}</td>
        </tr>
        <tr>
            <td colspan="2" class="header-title">FECHA FINALIZADA</td>
            <td colspan="4">{{ $bitacora['fecha_finalizacion'] }}</td>
        </tr>
    </table>
    <br>
    @if (count($bitacoraModel->tanqueos) > 0)
        <p style="text-align: center"><strong>REGISTRO DE TANQUEO DE COMBUSTIBLE</strong></p>
        <table style="width: 100%; border: #000000; border-collapse: collapse;" border="1">
            <thead style="background-color:#F2F2F2 ">
            <th>SOLICITANTE</th>
            <th>KILOMETRAJE</th>
            <th>MONTO ($)</th>
            <th>COMBUSTIBLE</th>
            <th>IMG COMPROBANTE</th>
            <th>IMG TABLERO</th>
            </thead>
            <tbody>
            @foreach ($bitacoraModel->tanqueos as $tanqueo)
                <tr>
                    <td>{{ Empleado::extraerNombresApellidos($tanqueo->solicitante) }}</td>
                    <td>{{ $tanqueo->km_tanqueo }}</td>
                    <td>{{ $tanqueo->monto }}</td>
                    <td>{{ $tanqueo->combustible->nombre }}</td>
                    <td>
                        @if($tanqueo->imagen_comprobante)
                            <a href="{{ url($tanqueo->imagen_comprobante) }}" target="_blank" title="comprobante"
                               style="display: inline-block">
                                <img src="{{Utils::urlToBase64(url($tanqueo->imagen_comprobante))}}" alt="Imagen de comprobante" width="100"
                                     height="100">
                            </a>
                        @else
                            <p>Imagen no disponible</p>
                        @endif
                    </td>
                    <td>
                        @if($tanqueo->imagen_tablero)
                            <a href="{{ url($tanqueo->imagen_tablero) }}" target="_blank" title="comprobante"
                               style="display: inline-block">
                                <img src="{{Utils::urlToBase64(url($tanqueo->imagen_tablero))}}" alt="imagen_tablero" width="100"
                                     height="100">
                            </a>
                        @else
                            <p>Imagen no disponible</p>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
 <br>
    @if (count($bitacora['actividadesRealizadas']) > 0)

        <table style="width: 100%; border: #000000; border-collapse: collapse;" border="1">
            <thead style="background-color:#F2F2F2 ">
            <th style="width: 20%">FECHA Y HORA</th>
            <th style="width: 50%">ACTIVIDAD</th>
            <th style="width: 15%">TAREA</th>
            <th style="width: 15%">KILOMETRAJE</th>
            </thead>
            <tbody>
            @foreach ($bitacora['actividadesRealizadas'] as $actividad)
                <tr>
                    <td>{{ $actividad['fecha_hora'] }}</td>
                    <td>{{ strtoupper($actividad['actividad_realizada']) }}</td>
                    <td style="text-align: center">{{ $actividad['tarea_id'] }}</td>
                    <td style="text-align: center">{{ $actividad['kilometraje'] }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
    <br>
    <table class="custom-table" style="width: 100%; border: #000000; border-collapse: collapse;" border="1">
        <tr>
            <th colspan="4" align="center">CHECKLIST DEL VEHICULO</th>
        </tr>
        <tr>
            <th colspan="4" style="background-color: #F2F2F2" align="left">1. INTERIOR</th>
        </tr>
        <tr>
            <th>VIDRIOS (VENTANAS/PARABRISAS)</th>
            <th>LIMPIA PARABRISAS</th>
            <th>LUCES</th>
            <th>AA/CC</th>
        </tr>
        <tr>
            <td class="{{ determinarClase($bitacora['checklistVehiculo']['parabrisas']) }}">
                {{ $bitacora['checklistVehiculo']['parabrisas'] }}</td>
            <td class="{{ determinarClase($bitacora['checklistVehiculo']['limpiaparabrisas']) }}">
                {{ $bitacora['checklistVehiculo']['limpiaparabrisas'] }}</td>
            <td class="{{ determinarClase($bitacora['checklistVehiculo']['luces_interiores']) }}">
                {{ $bitacora['checklistVehiculo']['luces_interiores'] }}</td>
            <td class="{{ determinarClase($bitacora['checklistVehiculo']['aire_acondicionado']) }}">
                {{ $bitacora['checklistVehiculo']['aire_acondicionado'] }}</td>

        </tr>
        <tr>
            <td style="width: 10%" class="header-title">OBSERVACION</td>
            <td style="width: 90%" colspan="3">
                {{ $bitacora['checklistVehiculo']['observacion_checklist_interior'] }}</td>
        </tr>
        {{-- <tr>
            <td colspan="4"></td>
        </tr> --}}
        <tr>
            <th colspan="4" style="background-color:#F2F2F2 " align="left">2. BAJO EL CAPÓ
            </th>
        </tr>
        <tr>
            <th>ACEITE DE MOTOR</th>
            <th>ACEITE HIDRAULICO</th>
            <th>LIQUIDO DE FRENO</th>
            <th>LIQUIDO REFRIGERANTE</th>
        </tr>
        <tr>
            <td class="{{ determinarClase($bitacora['checklistVehiculo']['aceite_motor']) }}">
                {{ $bitacora['checklistVehiculo']['aceite_motor'] }}</td>
            <td class="{{ determinarClase($bitacora['checklistVehiculo']['aceite_hidraulico']) }}">
                {{ $bitacora['checklistVehiculo']['aceite_hidraulico'] }}</td>
            <td class="{{ determinarClase($bitacora['checklistVehiculo']['liquido_freno']) }}">
                {{ $bitacora['checklistVehiculo']['liquido_freno'] }}</td>
            <td class="{{ determinarClase($bitacora['checklistVehiculo']['liquido_refrigerante']) }}">
                {{ $bitacora['checklistVehiculo']['liquido_refrigerante'] }}</td>
        </tr>
        <tr>
            <th>AGUA PLUMAS/RADIADOR</th>
            <th>FILTRO COMBUSTIBLE</th>
            <th>BATERIA</th>
            <th>CABLES Y CONEXIONES</th>
        </tr>
        <tr>
            <td class="{{ determinarClase($bitacora['checklistVehiculo']['agua_plumas_radiador']) }}">
                {{ $bitacora['checklistVehiculo']['agua_plumas_radiador'] }}</td>
            <td class="{{ determinarClase($bitacora['checklistVehiculo']['filtro_combustible']) }}">
                {{ $bitacora['checklistVehiculo']['filtro_combustible'] }}</td>
            <td class="{{ determinarClase($bitacora['checklistVehiculo']['bateria']) }}">
                {{ $bitacora['checklistVehiculo']['bateria'] }}</td>
            <td class="{{ determinarClase($bitacora['checklistVehiculo']['cables_conexiones']) }}">
                {{ $bitacora['checklistVehiculo']['cables_conexiones'] }}</td>
        </tr>
        <tr>
            <td style="width: 10%" class="header-title">OBSERVACION</td>
            <td style="width: 90%" colspan="3">
                {{ $bitacora['checklistVehiculo']['observacion_checklist_bajo_capo'] }}</td>
        </tr>
        {{-- <tr>
            <td colspan="4"></td>
        </tr> --}}
        <tr>
            <th colspan="4" style="background-color:#F2F2F2 " align="left">3. BAJO EL
                VEHICULO Y EXTERIOR
            </th>
        </tr>
        <tr>
            <th>LUCES EXTERIORES</th>
            <th>FRENOS (PASTILLAS/ZAPATAS)</th>
            <th>AMORTIGUADORES</th>
            <th>LLANTAS</th>
        </tr>
        <tr>
            <td class="{{ determinarClase($bitacora['checklistVehiculo']['parabrisas']) }}">
                {{ $bitacora['checklistVehiculo']['parabrisas'] }}</td>
            <td class="{{ determinarClase($bitacora['checklistVehiculo']['limpiaparabrisas']) }}">
                {{ $bitacora['checklistVehiculo']['limpiaparabrisas'] }}</td>
            <td class="{{ determinarClase($bitacora['checklistVehiculo']['luces_interiores']) }}">
                {{ $bitacora['checklistVehiculo']['luces_interiores'] }}</td>
            <td class="{{ determinarClase($bitacora['checklistVehiculo']['aire_acondicionado']) }}">
                {{ $bitacora['checklistVehiculo']['aire_acondicionado'] }}</td>
        </tr>
        <tr>
            <td style="width: 10%" class="header-title">OBSERVACION</td>
            <td style="width: 90%" colspan="3">
                {{ $bitacora['checklistVehiculo']['observacion_checklist_interior'] }}</td>
        </tr>
    </table>
    <br>
    <table class="custom-table" style="width: 100%; border: #000000; border-collapse: collapse;" border="1">
        <tr>
            <th colspan="4" align="center">CHECKLIST DE ACCESORIOS DEL VEHICULO</th>
        </tr>
        <tr>
            <th>BOTIQUIN</th>
            <th>CAJA DE HERRAMIENTAS</th>
            <th>TRIANGULOS/CONOS SEGURIDAD</th>
            <th>LLANTA EMERGENCIA</th>
        </tr>
        <tr>
            <td class="{{ determinarClase($bitacora['checklistAccesoriosVehiculo']['botiquin']) }}">
                {{ $bitacora['checklistAccesoriosVehiculo']['botiquin'] }}</td>
            <td class="{{ determinarClase($bitacora['checklistAccesoriosVehiculo']['caja_herramientas']) }}">
                {{ $bitacora['checklistAccesoriosVehiculo']['caja_herramientas'] }}</td>
            <td class="{{ determinarClase($bitacora['checklistAccesoriosVehiculo']['triangulos']) }}">
                {{ $bitacora['checklistAccesoriosVehiculo']['triangulos'] }}</td>
            <td class="{{ determinarClase($bitacora['checklistAccesoriosVehiculo']['llanta_emergencia']) }}">
                {{ $bitacora['checklistAccesoriosVehiculo']['llanta_emergencia'] }}</td>
        </tr>
        <tr>
            <th>CINTURONES SEGURIDAD</th>
            <th>GATA HIDRAULICA</th>
            <th>PORTAESCALERA</th>
            <th>EXTINTOR</th>
        </tr>
        <tr>
            <td class="{{ determinarClase($bitacora['checklistAccesoriosVehiculo']['cinturones']) }}">
                {{ $bitacora['checklistAccesoriosVehiculo']['cinturones'] }}</td>
            <td class="{{ determinarClase($bitacora['checklistAccesoriosVehiculo']['gata']) }}">
                {{ $bitacora['checklistAccesoriosVehiculo']['gata'] }}</td>
            <td class="{{ determinarClase($bitacora['checklistAccesoriosVehiculo']['portaescalera']) }}">
                {{ $bitacora['checklistAccesoriosVehiculo']['portaescalera'] }}</td>
            <td class="{{ determinarClase($bitacora['checklistAccesoriosVehiculo']['extintor']) }}">
                {{ $bitacora['checklistAccesoriosVehiculo']['extintor'] }}</td>
        </tr>
        <tr>
            <td style="width: 10%" class="header-title">OBSERVACION</td>
            <td style="width: 90%" colspan="3">
                {{ $bitacora['checklistAccesoriosVehiculo']['observacion_accesorios_vehiculo'] }}</td>
        </tr>
    </table>
    <br>
    <table class="custom-table" style="width: 100%; border: #000000; border-collapse: collapse;" border="1">
        <tr>
            <th colspan="4" align="center">CHECKLIST DE IMAGENES DEL VEHICULO</th>
        </tr>
        <tr>
            <th>DELANTERA</th>
            <th>TRASERA</th>
            <th>LATERAL IZQ.</th>
            <th>LATERAL DER.</th>
        </tr>
        <tr>
            <td>
                <a href="{{ $bitacora['checklistImagenVehiculo']['imagen_frontal'] ? url($bitacora['checklistImagenVehiculo']['imagen_frontal']) : '#' }}"
                   style="display: block;" target="_blank" title="Imagen Frontal">
                    <img
                        src="{{ !is_null($bitacora['checklistImagenVehiculo']['imagen_frontal']) ? Utils::urlToBase64(url($bitacora['checklistImagenVehiculo']['imagen_frontal'])) : '' }}" alt="imagen_frontal"
                        height="150" width="150" style="object-fit: contain;"/>
                </a>
            </td>
            <td>
                <a href="{{ $bitacora['checklistImagenVehiculo']['imagen_trasera'] ? url($bitacora['checklistImagenVehiculo']['imagen_trasera']) : '#' }}"
                   style="display: block;" target="_blank" title="Imagen Trasera">
                    <img
                        src="{{ !is_null($bitacora['checklistImagenVehiculo']['imagen_trasera']) ? Utils::urlToBase64(url( $bitacora['checklistImagenVehiculo']['imagen_trasera'])) : '' }}" alt="imagen_trasera"
                        height="150" width="150" style="object-fit: fill;"/>
                </a>
            </td>
            <td>
                <a href="{{ $bitacora['checklistImagenVehiculo']['imagen_lateral_izquierda'] ? url($bitacora['checklistImagenVehiculo']['imagen_lateral_izquierda']) : '#' }}"
                   style="display: block;" target="_blank" title="Imagen Lateral Izquierda">
                    <img
                        src="{{ !is_null($bitacora['checklistImagenVehiculo']['imagen_lateral_izquierda']) ? Utils::urlToBase64(url($bitacora['checklistImagenVehiculo']['imagen_lateral_izquierda'])) : '' }}" alt="imagen_lateral_izquierda"
                        height="150" width="150" style="object-fit: scale-down;"/>
                </a>
            </td>
            <td>
                <a href="{{ $bitacora['checklistImagenVehiculo']['imagen_lateral_derecha'] ? url($bitacora['checklistImagenVehiculo']['imagen_lateral_derecha']) : '#' }}"
                   style="display: block;" target="_blank" title="Imagen Lateral Derecha">
                    <img
                        src="{{ !is_null($bitacora['checklistImagenVehiculo']['imagen_lateral_derecha']) ? Utils::urlToBase64(url($bitacora['checklistImagenVehiculo']['imagen_lateral_derecha'])) : '' }}" alt="imagen_lateral_derecha"
                        height="150" width="150" style="object-fit: cover;"/>
                </a>
            </td>
        </tr>
        <tr>
            <th>TABLERO(KM)</th>
            <th>TABLERO(RADIO)</th>
            <th>ASIENTOS</th>
            <th>HERRAMIENTAS/ACCESORIOS</th>
        </tr>
        <tr>
            <td>
                <a href="{{ $bitacora['checklistImagenVehiculo']['imagen_tablero_km'] ? url($bitacora['checklistImagenVehiculo']['imagen_tablero_km']) : '#' }}"
                   style="display: block;" target="_blank" title="Imagen Frontal">
                    <img
                        src="{{ !is_null($bitacora['checklistImagenVehiculo']['imagen_tablero_km']) ? Utils::urlToBase64(url($bitacora['checklistImagenVehiculo']['imagen_tablero_km'])) : '' }}" alt="imagen_tablero_km"
                        height="150" width="150" style="object-fit: cover;"/>
                </a>
            </td>
            <td>
                <a href="{{ $bitacora['checklistImagenVehiculo']['imagen_tablero_radio'] ? url($bitacora['checklistImagenVehiculo']['imagen_tablero_radio']) : '#' }}"
                   style="display: block;" target="_blank" title="Imagen Frontal">
                    <img
                        src="{{ !is_null($bitacora['checklistImagenVehiculo']['imagen_tablero_radio']) ? Utils::urlToBase64(url($bitacora['checklistImagenVehiculo']['imagen_tablero_radio'])) : '' }}" alt="imagen_tablero_radio"
                        height="150" width="150" style="object-fit: cover;"/>
                </a>
            </td>
            <td>
                <a href="{{ $bitacora['checklistImagenVehiculo']['imagen_asientos'] ? url($bitacora['checklistImagenVehiculo']['imagen_asientos']) : '#' }}"
                   style="display: block;" target="_blank" title="Imagen Frontal">
                    <img
                        src="{{ !is_null($bitacora['checklistImagenVehiculo']['imagen_asientos']) ? Utils::urlToBase64(url($bitacora['checklistImagenVehiculo']['imagen_asientos'])) : '' }}"
                        height="150" width="150" style="object-fit: cover;"/>
                </a>
            </td>
            <td>
                <a href="{{ $bitacora['checklistImagenVehiculo']['imagen_accesorios'] ? url($bitacora['checklistImagenVehiculo']['imagen_accesorios']) : '#' }}"
                   style="display: block;" target="_blank" title="Imagen Frontal">
                    <img
                        src="{{ !is_null($bitacora['checklistImagenVehiculo']['imagen_accesorios']) ? Utils::urlToBase64(url($bitacora['checklistImagenVehiculo']['imagen_accesorios'])) : '' }}"
                        height="150" width="150" style="object-fit: cover;"/>
                </a>
            </td>
        </tr>
        <tr>
            <td style="width: 10%" class="header-title">OBSERVACION</td>
            <td style="width: 90%" colspan="3">
                {{ strtoupper($bitacora['checklistImagenVehiculo']['observacion']) }}</td>
        </tr>
    </table>
    <br><br><br><br><br><br><br><br><br>
    <table style="width: 100%;">
        <thead>
        <th align="center"></th>
        <th align="center">
            @if ($bitacora['firmada'])
                @if($chofer->firma_url)
                    <img src="{{ Utils::urlToBase64(url($chofer->firma_url)) }}" width="100" height="100"
                         alt="firma chofer responsable"/>
                @else
                    ___________________
                @endif
            @else
                ___________________ <br>
            @endif
        </th>
        <th align="center"></th>
        </thead>
        <tbody>
        <tr align="center">
            <td></td>
            <td><b>RESPONSABLE</b></td>
            <td></td>
        </tr>
        <tr align="center">
            <td></td>
            <td>{{ $bitacora['chofer'] }} </td>
            <td></td>
        </tr>
        <tr align="center">
            <td></td>
            <td>{{ $chofer->identificacion }}</td>
            <td></td>
        </tr>
        </tbody>
    </table>
    <div class="justificado">
    </div>
</main>
<script type="text/php">
    $pdf->page_script('
        $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
        $pdf->text(10, $pdf->get_height() - 25, "Pág $PAGE_NUM de $PAGE_COUNT", $font, 12);
    ');
</script>
</body>

</html>
