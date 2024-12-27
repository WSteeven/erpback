<!DOCTYPE html>
<html lang="es">

@php
    $fecha = new Datetime();
    $fecha_entrega = new Datetime($transferencia['fecha_entrega']);
    $logo_principal =
        'data:image/png;base64,' . base64_encode(file_get_contents(public_path() . $configuracion['logo_claro']));
    $logo_watermark =
        'data:image/png;base64,' . base64_encode(file_get_contents(public_path() . $configuracion['logo_marca_agua']));
@endphp

<head>
    <meta charset="utf-8">
    <title>Acta de Transferencia {{ $vehiculo['placa'] }}</title>
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
            margin-left: 2cm;
            margin-right: 2cm;
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
            font-size: 14px;
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
    </style>
</head>

<body>
    {{-- Encabezado --}}
    <header>
        <table
            style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:14px;">
            <tr class="row" style="width:auto">
                <td style="width: 10%">
                    <div class="col-md-3"><img src="{{ $logo_principal }}" width="90"></div>
                </td>
                <td style="width: 68%">
                    <div align="center"><b>ACTA DE TRANSFERENCIA DE VEHICULOS</b>
                    </div>
                </td>
                <td style="width: 22%">
                    <div align="center"><b>FOR FIRSTRED 002 <br> 01 04 2024 </b></div>
                </td>
            </tr>
        </table>
    </header>
    {{-- Pie de pagina --}}
    <footer>
        <hr>
        <table style="width: 100%;">
            <tr>
                <td></td>
                <td style="width: 80%; line-height: normal;">
                    <div style="margin: 0%; margin-bottom: 0px; margin-top: 0px;" align="center">La información
                        contenida en este documento es confidencial y de uso exclusivo de
                        {{ $configuracion['razon_social'] }}
                    </div>
                    <div style="margin: 0%; margin-bottom: 0px; margin-top: 0px;" align="center">Impreso por:
                        {{ auth('sanctum')->user()->empleado->nombres }}
                        {{ auth('sanctum')->user()->empleado->apellidos }} el
                        {{ $fecha->format('Y-m-d H:i') }}
                    </div>
                </td>
                <td></td>
            </tr>
        </table>
    </footer>
    {{-- Cuerpo --}}
    <main>
        <div class="justificado">
            <p style="text-align: right">En la ciudad de {{ $transferencia['canton'] }},
                {{ $fecha_entrega->format('d') }}
                de {{ $mes }} de {{ $fecha_entrega->format('Y') }}.</p>
            <p></p>
            <p></p>
            <p><strong>A). CONSTANCIA DE ENTREGA</strong> </p>
            <p>{{ $configuracion['razon_social'] }}, con domicilio en {{ $configuracion['direccion_principal'] }}, con
                el objetivo del normal cumplimiento de las actividades de la empresa, por
                medio de la presente acta hace constar la transferencia del vehículo identificado a continuación:</p>

            <ul>
                <li><strong>Vehículo Marca y Modelo:</strong> {{ $vehiculo['marca'] }}, {{ $vehiculo['modelo'] }}</li>
                <li><strong>Año:</strong> {{ $vehiculo['anio_fabricacion'] }}</li>
                <li><strong>Color:</strong> {{ $vehiculo['color'] }}</li>
                <li><strong>Número de Placa:</strong> {{ $vehiculo['placa'] }}</li>
                <li><strong>Número de Motor:</strong> {{ $vehiculo['num_motor'] }}</li>
                <li><strong>Número de Chasis:</strong> {{ $vehiculo['num_chasis'] }}</li>
            </ul>

            <p>La entrega de dicho vehículo se realiza con motivo {{ $transferencia['motivo'] }} por parte del señor
                {{ $transferencia['entrega'] }} con cédula de identidad número {{ $entrega['identificacion'] }} al
                señor {{ $transferencia['responsable'] }} con
                cédula de identidad número {{ $responsable['identificacion'] }}, quien asume la responsabilidad
                completa sobre el mismo a partir de este momento.
            </p>
            <p></p>
            <p><strong>B) CONDICIONES</strong> </p>
            <p>El vehículo se entrega en las siguientes condiciones:</p>
            <ul>
                <li>Estado de carrocería:
                    @foreach ($transferencia['estado_carroceria'] as $estado)
                        {{ $estado }},
                    @endforeach
                </li>
                <li>Estado de mecánico:
                    @foreach ($transferencia['estado_mecanico'] as $estado)
                        {{ $estado }},
                    @endforeach
                </li>
                <li>Estado Sistema Eléctrico y A/AC:
                    @foreach ($transferencia['estado_electrico'] as $estado)
                        {{ $estado }},
                    @endforeach
                </li>
            </ul>

            <p></p>
            <p><strong>C) ACCESORIOS Y HERRAMIENTAS</strong> </p>
            <p>Junto con el vehículo se entregan los siguientes accesorios y/o herramientas: </p>
            <ul>
                @foreach ($transferencia['accesorios'] as $accesorio)
                    <li>{{ $accesorio }}</li>
                @endforeach
            </ul>

            <p></p>
            <p></p>
            <p><strong>D) RESPONSABILIDADES</strong> </p>
            <p>{{ $transferencia['responsable'] }} declara haber recibido el vehículo en las condiciones mencionadas y
                se
                compromete a utilizarlo de manera adecuada y responsable, respetando las normas de tránsito y las
                políticas de la empresa relacionadas con el uso de vehículos.</p>

            <p>El chofer asignado será responsable de llevar un control de mantenimiento mecánico del vehículo y
                reportar periódicamente a su jefe inmediato alguna novedad inherente al funcionamiento del mismo,
                desgaste de partes y piezas que podría causar un daño mayor en el futuro al vehículo y por ende a las
                labores diarias</p>

            <p>Cualquier daño o avería que se produzca en el vehículo durante el período de responsabilidad de
                {{ $transferencia['responsable'] }}, que NO sea imputable al desgaste normal, será de su exclusiva
                responsabilidad, debiendo informar de manera inmediata a {{ $configuracion['nombre_empresa'] }} sobre
                cualquier novedad presentada.</p>
            <p> </p>
            <p><strong>E) ACEPTACION DE LAS PARTES</strong> </p>
            <p>Para constancia, y de conformidad con lo estipulado, firman el presente documento los que en el
                intervienen.</p>

            <br><br><br><br><br>
            <table class="firma" style="width: 100%;">
                <thead align="center">
                <th>
                    @if(file_exists(public_path($entrega['firma_url'])))
                        <img src="{{ url($entrega['firma_url']) }}" width="100%" height="50"/>
                    @else
                        ___________________
                    @endif
                </th>
                <th></th>
                <th>
                    @if(file_exists(public_path($responsable['firma_url'])))
                        <img src="{{ url($responsable['firma_url']) }}" width="100%" height="50"/>
                    @else
                        ___________________
                    @endif
                </th>
                </thead>
                <tbody>
                    <tr align="center">
                        <td><b>ENTREGA</b></td>
                        <td><b></b></td>
                        <td><b>RESPONSABLE</b></td>
                    </tr>
                    <tr align="center">
                        <td class="col-4">{{ $entrega['nombres'] }} {{ $entrega['apellidos'] }} <br>
                            {{ $entrega['identificacion'] }}
                        </td>
                        <td class="col-4"></td>
                        <td class="col-4">{{ $responsable['nombres'] }} {{ $responsable['apellidos'] }} <br>
                            {{ $responsable['identificacion'] }}</td>
                    </tr>
                </tbody>
            </table>
            <br><br><br><br>
            <table class="firma" style="width: 100%">
                <thead>
                    <th align="center">___________________</th>
                </thead>
                <tbody align="center">
                    <tr>
                        <td><b>EMPLEADOR</b></td>
                    </tr>
                    <tr>
                        <td>{{ $configuracion['razon_social'] }}</td>
                    </tr>
                    <tr>
                        <td>{{ $configuracion['ruc'] }}</td>
                    </tr>
                </tbody>
            </table>
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
