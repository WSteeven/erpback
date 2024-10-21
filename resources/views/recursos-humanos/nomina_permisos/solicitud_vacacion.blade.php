<!DOCTYPE html>
<html lang="es">

@php
    use Carbon\Carbon;
    $fecha = new Datetime();
    $logo_principal =
        'data:image/png;base64,' . base64_encode(file_get_contents(public_path() . $configuracion['logo_claro']));
    $logo_watermark =
        'data:image/png;base64,' . base64_encode(file_get_contents(public_path() . $configuracion['logo_marca_agua']));
    if ($empleado->firma_url) {
        $empleado_firma = 'data:image/png;base64,' . base64_encode(file_get_contents(substr($empleado->firma_url, 1)));
    }
    if ($autorizador->firma_url) {
        $autorizador_firma = 'data:image/png;base64,' . base64_encode(file_get_contents(substr($autorizador->firma_url, 1)));
    }
@endphp

<head>
    <meta charset="utf-8">
    <title>Solicitud de vacaciones {{ $vacacion['empleado_info'] }}</title>
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
                <div align="center"><b>SOLICITUD DE TRÁMITE DE VACACIONES</b>
                </div>
            </td>
            <td style="width: 22%">
                <div align="center"><b>FOR FIRSTRED 002 <br> 23 09 2024 </b></div>
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
        <p style="text-align: left">Lugar y fecha: {{ ucfirst($empleado->canton->canton) }}, {{ Carbon::parse($vacacion['created_at'])->format('Y-m-d') }}.</p>
        <p></p>
        <p><strong>SECCION DEL SOLICITANTE</strong></p>
        <p>El/la suscrit@: <strong>{{$empleado->nombres}} {{$empleado->apellidos}}</strong> con número de
            cédula: {{ $empleado->identificacion }}, que presto mis servicios
            en {{$configuracion->razon_social}}.</p>
        <p>Departamento: {{ $empleado->departamento?->nombre }}.</p>
        @if($empleado->grupo)
            <p>Grupo: {{ $empleado->grupo->nombre }}.</p>
        @endif
        <p>Cargo: {{ $empleado->cargo?->nombre }}.</p>

        <p>Solicito se me conceda hacer uso de: {{ $vacacion['numero_rangos'] == 1 ? $vacacion['numero_dias'] : $vacacion['numero_dias_rango1']+$vacacion['numero_dias_rango2'] }}
            días como vacaciones anuales:</p>
        @if($vacacion['numero_rangos']==2)
            <p><strong>Rango 1: </strong> Desde el {{ $vacacion['fecha_inicio_rango1_vacaciones'] }} hasta el {{ $vacacion['fecha_fin_rango1_vacaciones'] }}.</p>
            <p><strong>Rango 2: </strong> Desde el {{ $vacacion['fecha_inicio_rango2_vacaciones'] }} hasta el {{ $vacacion['fecha_fin_rango2_vacaciones'] }}.</p>
        @else
            <p>Desde el {{ $vacacion['fecha_inicio'] }} hasta el: {{ $vacacion['fecha_fin'] }}.</p>
        @endif

        <br>
        <p><strong>SECCION DEL AUTORIZADOR</strong></p>
        @if($vacacion['estado']==2)
            <p>Analizadas las necesidades del personal, para el normal desenvolvimiento de las funciones del área, se
                determina que la presente solicitud de permiso con cargo a las vacaciones es:
                <strong>{{$vacacion['estado']===2? 'ACEPTADA':'NEGADA'}}</strong>.</p>
        @else
            @php
                $estado = match ($vacacion['estado']){
                  3 => 'NEGADA',
                  2 => 'ACEPTADA',
                  default => 'PENDIENTE'
                };
            @endphp
            <p>La autorización de las vacaciones por parte de {{$autorizador->nombres}} {{$autorizador->apellidos}} es:
                <strong>{{$estado}}</strong>.</p>
        @endif

        <br>
        <p><strong>ACEPTACION DE LAS PARTES</strong></p>
        <p>Para constancia, y de conformidad con lo estipulado, firman el presente documento los que en el
            intervienen.</p>


        <br><br><br><br><br>
        <table class="firma" style="width: 100%;">
            <thead align="center">
            <th>

                @isset($empleado_firma)
                    <img src="{{ $empleado_firma }}" alt="" width="100%" height="40">
                @endisset
                @empty($empleado_firma)
                    ___________________<br/>
                @endempty
            </th>
            <th></th>
            <th>
                @if ($vacacion['estado']==2)
                    @isset($autorizador_firma)
                        <img src="{{ $autorizador_firma }}" alt="" width="100%" height="40">
                    @endisset
                @endif
                @empty($autorizador_firma)
                    ___________________<br/>
                @endempty
            </th>
            </thead>
            <tbody>
            <tr align="center">
                <td><b>EMPLEADO</b></td>
                <td><b></b></td>
                <td><b>AUTORIZADOR</b></td>
            </tr>
            <tr align="center">
                <td class="col-4">{{ $empleado->nombres }} {{ $empleado->apellidos }} <br>
                    {{ $empleado->identificacion }}
                </td>
                <td class="col-4"></td>
                <td class="col-4">{{ $autorizador->nombres }} {{$autorizador->apellidos}} <br>
                    {{ $autorizador->identificacion }}</td>
            </tr>
            </tbody>
        </table>
        <br><br><br><br>
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
