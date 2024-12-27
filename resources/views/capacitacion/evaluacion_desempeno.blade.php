<!DOCTYPE html>
<html lang="es">

@php
    use Carbon\Carbon;
    use Illuminate\Support\Facades\Log;

    $fecha = Carbon::now();
    Log::channel('testing')->info('Log', ['Evaluación en blade', $evaluacion]);
    $fecha_creacion = Carbon::parse($evaluacion->created_at)->format('Y-m-d');
    $logo_watermark ='data:image/png;base64,' . base64_encode(file_get_contents(public_path() . $configuracion->logo_marca_agua));
@endphp

<head>
    <meta charset="utf-8">
    <title>Evaluación de desempeño operativo</title>
    <style>
        @page {
            margin: 0 15px;
        }

        body {
            background-image: url({{ $logo_watermark }});
            background-repeat: no-repeat;
            background-position: center;
            background-size: contain;

            /** Defina ahora los márgenes reales de cada página en el PDF **/
            margin: 3cm 2cm 2cm;

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

        td {
            line-height: 0.1cm;
            vertical-align: center;
        }

        .header-table td {
            line-height: normal;
            vertical-align: center;
        }

        .custom-table td {
            line-height: normal;
            border: 1px solid #000;
        }

    </style>
</head>

<body>
{{-- Encabezado --}}
<header>
    <table class="header-table"
           style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:14px;">
        <tr class="row" style="width:auto">
            <td style="width: 10%">
                <div class="col-md-3">
                    @if(file_exists(public_path($configuracion->logo_claro)))
                        <img src="{{ url($configuracion->logo_claro) }}" width="90" alt="Logo">
                    @endif
                </div>
            </td>
            <td style="width: 68%">
                <div style="text-align: center"><b>EVALUACIÓN DE DESEMPEÑO OPERATIVO - PERIODO DE PRUEBA</b>
                </div>
            </td>
            <td style="width: 22%">
                <div style="text-align: center"><b>FOR FIRSTRED 006 <br> 30 12 2024 </b></div>
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
                <div style="margin: 0; text-align: center">La información
                    contenida en este documento es confidencial y de uso exclusivo de
                    {{ $configuracion['razon_social'] }}
                </div>
                <div style="margin: 0;text-align:center">Impreso por:
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
        <p><strong>Fecha de evaluación: </strong> {{$fecha_creacion}}</p>
        <p><strong>1). DEL EVALUADO </strong></p>
        <p><strong>Nombres y Apellidos: </strong>{{$evaluacion->evaluado->nombres}} {{$evaluacion->evaluado->apellidos}}
        </p>
        <p><strong>Cargo: </strong>{{$evaluacion->evaluado->cargo->nombre}}</p>
        <p><strong>Área de Trabajo: </strong>{{$evaluacion->evaluado->area->nombre}}</p>
        <p><strong>Escala:</strong></p>
        <ol>
            <li>Excelente</li>
            <li>Muy Bueno</li>
            <li>Aceptable</li>
            <li>Susceptible de Mejoras</li>
            <li>Deficiente</li>
        </ol>
        @foreach($evaluacion->respuestas as $respuesta)
            @switch($respuesta['type'])
                @case('textblock')
                <h6 style="text-align: center">{{$respuesta['label']}}</h6>
                @case('tip')
                <small style="text-align: center; background-color: #2f75b5">{{$respuesta['label']}}</small>
                @case('text')
                <p ><strong>{{$respuesta['label']}}</strong></p>
                                    <p>{{$respuesta['value']}}</p>
                @default
                    <table>
                    <tr>
                        <td>{{$respuesta['label']}}</td>
                        <td>{{$respuesta['valor']}}</td>
                    </tr>
                    </table>
            @endswitch
        @endforeach


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
