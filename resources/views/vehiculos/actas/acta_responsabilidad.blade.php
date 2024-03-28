<!DOCTYPE html>
<html lang="es">

@php
    $fecha = new Datetime();
    $fecha_entrega = new Datetime($asignacion['fecha_entrega']);
    $logo_principal =
        'data:image/png;base64,' . base64_encode(file_get_contents(public_path() . $configuracion['logo_claro']));
    $logo_watermark =
        'data:image/png;base64,' . base64_encode(file_get_contents(public_path() . $configuracion['logo_marca_agua']));
@endphp

<head>
    <meta charset="utf-8">
    <title>Acta de Responsabilidad {{ $vehiculo['placa'] }}</title>
    <style>
        @page {
            margin: 0cm 15px;
        }

        body {
            background-image: url({{ $logo_watermark }});
            background-repeat: no-repeat;
            background-position: center;
            background-size: contain;
        }

        /** Definir las reglas del encabezado **/
        header {
            position: fixed;
            top: 0cm;
            left: 0cm;
            right: 0cm;
            /* height: 2cm; */

            /** Estilos extra personales **/
            text-align: center;
            /* line-height: 1cm; */
        }

        /** Definir las reglas del pie de página **/
        footer {
            position: fixed;
            bottom: 93px;
            left: 0cm;
            right: 0cm;
            height: 2cm;

            /** Estilos extra personales **/
            text-align: center;
            color: #000000;
            line-height: 1.5cm;
        }

        footer .page:after {
            content: counter(page);
        }

        main {
            position: relative;
            top: 80px;
            left: 0cm;
            right: 0cm;
            margin: 2cm;
            margin-bottom: 7cm;
            font-size: 14px;
        }

        div {
            color: #000000 !important;
        }

        h1 {
            text-align: center;
            text-transform: uppercase;
        }

        /* .firma {
            table-layout: fixed;
            width: 100%;
            line-height: normal;
        } */

        .justificado {
            text-align: justify;
            text-justify: inter-word;
        }


        .row {
            width: 100%;
        }
    </style>
</head>

<body>
    <header>
        {{-- border="1" --}}
        <table
            style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:14px;">
            <tr class="row" style="width:auto">
                <td>
                    <div class="col-md-3" style="width:25%"><img src="{{ $logo_principal }}" width="90"></div>
                </td>
                <td>
                    <div class="col-md-7" style="width:50%" align="center"><b>ACTA DE ENTREGA/RECEPCION DE VEHICULO</b>
                    </div>
                </td>
                <td>
                    <div class="col-md-2" style="width:25%" align="center"><b>FIRSTRED v1.0 </b></div>
                </td>
            </tr>
        </table>
        <hr>
    </header>
    {{-- Pie de pagina --}}
    <footer>
        <table style="width: 100%;">
            <tr>
                <td class="page">Página </td>
                <td style="line-height: normal;">
                    <div style="margin: 20%; margin-bottom: 0px; margin-top: 0px;" align="center">La información
                        contenida en este documento es confidencial y de uso exclusivo de
                        {{ $configuracion['razon_social'] }}.
                    </div>
                    <div style="margin: 0%; margin-bottom: 0px; margin-top: 0px;" align="center">Impreso por el
                        Usuario:
                        {{ auth('sanctum')->user()->empleado->nombres }}
                        {{ auth('sanctum')->user()->empleado->apellidos }} el
                        {{ $fecha->format('d/m/Y H:i') }}
                    </div>
                </td>
                <td>
                    {{-- <div align="right"><img src="data:image/svg;base64,{!! base64_encode(QrCode::format('svg')->encoding('UTF-8')->size(70)->generate($mensaje_qr)) !!}"></div> --}}
                </td>
            </tr>
        </table>
    </footer>
    {{-- Cuerpo --}}
    <main>
        <div class="justificado">
            <p>En la ciudad de {{ $asignacion['canton'] }}, al {{ $fecha_entrega->format('d') }} de {{ $mes }}
                de {{ $fecha_entrega->format('Y') }},</p>

            <p>{{ $configuracion['razon_social'] }}, con domicilio en {{ $configuracion['direccion_principal'] }}, por
                medio de la presente acta hace constar la entrega del vehículo
                identificado a continuación:</p>

            <ul>
                <li><strong>Vehículo Marca y Modelo:</strong> {{ $vehiculo['marca'] }}, {{ $vehiculo['modelo'] }}</li>
                <li><strong>Año de Fabricación:</strong> {{ $vehiculo['anio_fabricacion'] }}</li>
                <li><strong>Color:</strong> {{ $vehiculo['color'] }}</li>
                <li><strong>Número de Placa:</strong> {{ $vehiculo['placa'] }}</li>
                <li><strong>Número de Motor:</strong> {{ $vehiculo['num_motor'] }}</li>
                <li><strong>Número de Chasis:</strong> {{ $vehiculo['num_chasis'] }}</li>
            </ul>

            <p>La entrega de dicho vehículo se realiza a {{ $asignacion['responsable'] }} con cédula de identidad
                número {{ $responsable['identificacion'] }},
                quien asume la responsabilidad completa sobre el mismo a partir de este momento.</p>

            <p>Con la entrega del vehículo, {{ $configuracion['razon_social'] }} declara que el mismo se encuentra en
                buen estado y condiciones
                de funcionamiento, sin presentar defectos ni averías visibles.</p>

            <p>{{ $asignacion['responsable'] }} declara haber recibido el vehículo en las condiciones mencionadas y se
                compromete a
                utilizarlo de manera adecuada y responsable, respetando las normas de tránsito y las políticas de la
                empresa relacionadas con el uso de vehículos.</p>

            <p>Cualquier daño o avería que se produzca en el vehículo durante el período de responsabilidad de
                {{ $asignacion['responsable'] }} será de su exclusiva responsabilidad, debiendo informar de manera
                inmediata a {{ $configuracion['nombre_empresa'] }}
                sobre cualquier incidente.</p>

            <p>La presente acta se firma por duplicado, quedando un ejemplar en posesión de "La Empresa" y el otro en
                posesión de "El Responsable".</p>

            <br><br><br><br><br>
            <table class="firma" style="width: 100%;">
                <thead>
                    <th align="center">___________________</th>
                    <th align="center"></th>
                    <th align="center">___________________</th>
                </thead>
                <tbody>
                    <tr align="center">
                        <td><b>ENTREGA</b></td>
                        <td><b></b></td>
                        <td><b>RESPONSABLE</b></td>
                    </tr>
                    <tr>
                        <td style="padding-left: 60px;">Nombre: </td>
                        <td></td>
                        <td style="padding-left: 60px;">Nombre:</td>
                    </tr>
                    <tr>
                        <td style="padding-left: 60px;">C.I: </td>
                        <td></td>
                        <td style="padding-left: 60px;">C.I:</td>
                    </tr>
                </tbody>
            </table>
            <table class="firma" style="width: 100%">
                <thead>
                    <th align="center">___________________</th>
                </thead>
                <tbody>
                    <tr align="center">
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
            <p><strong>Firma del Representante de la Empresa:</strong> ___________________________</p>
            <p>[Nombre del Representante de la Empresa]</p>
            <p>[Cargo del Representante de la Empresa]</p>

            <p><strong>Firma del Responsable:</strong> ___________________________</p>
            <p>[Nombre del Responsable]</p>
        </div>
    </main>
</body>

</html>
