<!DOCTYPE html>
<html lang="es">
@php
    use Src\Shared\Utils;
        $fecha = new Datetime();
        $suma_salario =0;

        function determinarClase($valor)
        {
            return match ($valor) {
                'Finalizado' => 'verde',
                'En Proceso' => 'naranja',
                default => 'gris',
            };
        }
        function empleado(int $id){
            $empleado = \App\Models\Empleado::find($id);
            return \App\Models\Empleado::extraerNombresApellidos($empleado);
        }
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Planificacion {{$plan->id}} - {{$plan->nombre}}</title>
    <style>
        @page {
            margin: 0cm 15px;
            size: landscape;
        }

        body {
            background-image: url({{ Utils::urlToBase64(url($configuracion->logo_marca_agua)) }});
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

        .gris {
            color: grey;
            /* Color rojo */
        }
    </style>
</head>
<body>
<header>
    <table
        style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:18px;">
        <tr class="row" style="width:auto">
            <td style="width: 10%;">
                <div class="col-md-3"><img
                        src="{{ Utils::urlToBase64(url($configuracion->logo_claro)) }}"
                        width="90" alt="logo"></div>
            </td>
            <td style="width: 100%">
                <div class="col-md-7" align="center"><b style="font-size: 75%">PLANIFICACION</b>
                </div>
            </td>
            <td>
                <div class="col-md-2" align="right">FIRSTRED</div>
            </td>
        </tr>
    </table>
</header>
<footer>
    <table style="width: 100%;">
        <tr>
            <td style="line-height: normal;">
                <div style="margin: 0%; margin-bottom: 0px; margin-top: 0px;" align="center">Esta informacion es
                    propiedad de JPCONSTRUCRED C.LTDA. - Prohibida su divulgacion
                </div>
                <div style="margin: 0%; margin-bottom: 0px; margin-top: 0px;" align="center">Generado por el
                    Usuario:
                    {{ auth('sanctum')->user()->empleado->nombres }}
                    {{ auth('sanctum')->user()->empleado->apellidos }} el
                    {{ $fecha->format('d-m-Y H:i') }}
                </div>
            </td>
        </tr>
    </table>
</footer>
<main>
    <div style="text-align: center !important; font-size: 24px; background-color: #DBDBDB;"
         colspan="7">{{$plan->nombre}}</div>
    <div align="right">Completado {{$plan->completado}}%</div>
    <br>
    <table style="width: 100%; border: #000000; border-collapse: collapse;" border="1">

        <tr></tr>
        <tr></tr>
        <tr></tr>
        <tr style="background-color: #84200d; font-weight: bold; color: white">
            <td>Actividades</td>
            <td>Responsable</td>
            <td>Fecha Inicio</td>
            <td>Fecha Fin</td>
            <td>Estado</td>
            <td>Periodicidad</td>
            <td>Observaciones</td>
            <td>% Completado</td>
        </tr>
        @foreach($plan->actividades as $index=>$actividad)
            <tr style="font-weight: bold; text-transform: uppercase; background-color: #DBDBDB;">
                <td>{{$index+1}}.- {{$actividad['nombre']}}</td>
                <td>{{$plan->empleado->nombres}} {{$plan->empleado->apellidos}}</td>
                <td colspan="6" align="right">{{$actividad['completado']}}%</td>
            </tr>
            @foreach($actividad['subactividades'] as $subactividad)
                <tr>
                    <td>{{$subactividad['nombre']}}</td>
                    <td>{{empleado($subactividad['responsable'])}}</td>
                    <td>{{$subactividad['fecha_inicio']}}</td>
                    <td>{{$subactividad['fecha_fin']}}</td>
                    <td class="{{determinarClase($subactividad['estado_avance'])}}">{{$subactividad['estado_avance']}}</td>
                    <td>{{$subactividad['periodicidad']}}</td>
                    <td>{{$subactividad['observaciones']}}</td>
                    <td></td>
                </tr>
            @endforeach
        @endforeach
    </table>
    <br>
</main>
<script type="text/php">
    if (isset($pdf)) {
            $text = "Pág {PAGE_NUM} de {PAGE_COUNT}";
            $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
            $pdf->page_text(10,785, $text, $font, 12);
    }
</script>
</body>

</html>
