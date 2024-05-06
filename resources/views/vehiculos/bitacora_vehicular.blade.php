<!DOCTYPE html>
<html lang="en">
@php
    $fecha = new Datetime();
    $logo_principal =
        'data:image/png;base64,' . base64_encode(file_get_contents(public_path() . $configuracion['logo_claro']));
    $logo_watermark =
        'data:image/png;base64,' . base64_encode(file_get_contents(public_path() . $configuracion['logo_marca_agua']));
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bitácora vehicular</title>
</head>

<style>
    @page {
        margin: 0cm 15px;
    }

    body {
        background-image: url({{ $logo_watermark }});
        background-repeat: no-repeat;
        background-position: center;
        background-size: contain;

        /** Defina ahora los márgenes reales de cada página en el PDF **/
        margin-top: 3cm;
        margin-left: 1cm;
        margin-right: 1cm;
        margin-bottom: 2cm;

        /** Define el texto **/
        font-family: Arial, sans-serif;
    }

    /** Definir las reglas del encabezado **/
    header {
        position: fixed;
        top: 0cm;
        left: 0cm;
        right: 0cm;
        height: 2cm;
        margin-top: 5px;

        /** Estilos extra personales **/
        text-align: center;
    }

    /** Definir las reglas del pie de página **/
    footer {
        position: fixed;
        bottom: 0px;
        left: 0cm;
        right: 0cm;
        height: 2cm;
        margin-bottom: 5px;

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
        left: 0cm;
        right: 0cm;

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
</style>

<body>
    <header>
        <table
            style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:18px;">
            <tr class="row" style="width:auto">
                <td>
                    <div class="col-md-3"><img src="{{ $logo_principal }}" width="90"></div>
                </td>
                <td>
                    <div class="col-md-7" align="center"><b>CONTROL DE VEHICULOS</b></div>
                </td>
                <td>
                    <div class="col-md-2" align="right">FIRSTRED </div>
                </td>
            </tr>
        </table>
    </header>
    <footer>
        <table class="firma" style="width: 100%;">
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
        </table>
        <table style="width: 100%;">
            <tr>
                <td class="page">Página </td>
                <td style="line-height: normal;">
                    <div style="margin: 0%; margin-bottom: 0px; margin-top: 0px;" align="center">
                        {{ $configuracion['razon_social'] }}</div>
                    <div style="margin: 0%; margin-bottom: 0px; margin-top: 0px;" align="center">Generado por el
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
        </table>
        <br>
        <table style="width: 100%; border: #000000; border-collapse: collapse;" border="1">
            <thead>
                <th style="width: 20%">Fecha y Hora</th>
                <th style="width: 80%">Actividad</th>
            </thead>
            <tbody>
                @foreach ($bitacora['actividadesRealizadas'] as $actividad)
                    <tr>
                        <td>{{ $actividad['fecha_hora'] }}</td>
                        <td>{{ $actividad['actividad_realizada'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br>
        <table style="width: 100%; border: #000000; border-collapse: collapse;" border="1">
            <tr>
                <th colspan="4" style="background-color: rgba(143, 235, 198, 0.267)" align="left">1. INTERIOR</th>
            </tr>
            <tr style="width: auto">
                <th>VIDRIOS (VENTANAS/PARABRISAS)</th>
                <th>LIMPIA PARABRISAS</th>
                <th>LUCES</th>
                <th>AA/CC</th>
            </tr>
            <tr style="width: auto">
                <td>{{ $bitacora['checklistVehiculo']['parabrisas'] }}</td>
                <td>{{ $bitacora['checklistVehiculo']['limpiaparabrisas'] }}</td>
                <td>{{ $bitacora['checklistVehiculo']['luces_interiores'] }}</td>
                <td>{{ $bitacora['checklistVehiculo']['aire_acondicionado'] }}</td>
            </tr>
            <tr>
                <td style="width: 10%" class="header-title">OBSERVACION</td>
                <td style="width: 90%">{{ $bitacora['checklistVehiculo']['observacion_checklist_interior'] }}</td>
            </tr>

            <tr>
                <th colspan="4" style="background-color: rgba(143, 235, 198, 0.267)" align="left">2. BAJO EL CAPÓ
                </th>
            </tr>
            <tr>
                <th>ACEITE DE MOTOR</th>
                <th>ACEITE HIDRAULICO</th>
                <th>LIQUIDO DE FRENO</th>
                <th>LIQUIDO REFRIGERANTE</th>
            </tr>
            <tr>
                <td>{{ $bitacora['checklistVehiculo']['aceite_motor'] }}</td>
                <td>{{ $bitacora['checklistVehiculo']['aceite_hidraulico'] }}</td>
                <td>{{ $bitacora['checklistVehiculo']['liquido_freno'] }}</td>
                <td>{{ $bitacora['checklistVehiculo']['liquido_refrigerante'] }}</td>
            </tr>
            <tr>
                <th>AGUA PLUMAS/RADIADOR</th>
                <th>FILTRO COMBUSTIBLE</th>
                <th>BATERIA</th>
                <th>CABLES Y CONEXIONES</th>
            </tr>
            <tr>
                <td>{{ $bitacora['checklistVehiculo']['agua_plumas_radiador'] }}</td>
                <td>{{ $bitacora['checklistVehiculo']['filtro_combustible'] }}</td>
                <td>{{ $bitacora['checklistVehiculo']['bateria'] }}</td>
                <td>{{ $bitacora['checklistVehiculo']['cables_conexiones'] }}</td>
            </tr>
            <tr>
                <td style="width: 10%" class="header-title">OBSERVACION</td>
                <td style="width: 90%">{{ $bitacora['checklistVehiculo']['observacion_checklist_bajo_capo'] }}</td>
            </tr>
        </table>
        <div class="justificado">
        </div>
    </main>
</body>

</html>
