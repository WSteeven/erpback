<html>


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>FICHA DE APTITUD</title>
    <style>
        @page {
            margin: 40px 40px 0 40px;
            /* 15px 10px 15px;*/
        }

        /** Definir las reglas del encabezado **/
        header {
            position: fixed;
            top: 0cm;
            left: 0cm;
            right: 0cm;
            height: 2cm;

            /** Estilos extra personales **/
            text-align: center;
            line-height: 1.5cm;
        }

        /** Definir las reglas del pie de página **/
        footer {
            position: fixed;
            bottom: 0px;
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
            margin-bottom: 7cm;
            font-size: 12px;
        }

        div {
            color: #000000 !important;
        }

        h1 {
            text-align: center;
            text-transform: uppercase;
        }

        .firma {
            table-layout: fixed;
            width: 100%;
            line-height: normal;
            font-size: 12px;
            /* position: inherit; */
            /* top: 140px; */
        }


        .row {
            width: 100%;
        }

        /* Mis estilos */
        th {
            background: #ccffcc;
        }

        .bg-green {
            background: #ccffcc;
        }

        td,
        th {
            padding: 4px;
            word-wrap: break-word;
            border: 1px solid #000;
        }

        .titulo-seccion {
            background: #ccccff;
            border: 1px solid #000;
            padding: 4px;
            font-size: 14px;
            font-weight: bold;
        }

        .cuadrado {
            border: 1px solid #000;
            padding: 4px;
        }

        * {
            font-family: Verdana, Arial, Helvetica, sans-serif;
            font-size: 12px;
        }

        .px-4 {
            padding: 0 4px;
        }

        .pa-8 {
            padding: 8px;
        }

        .pa-12 {
            padding: 12px;
        }

        .pt-8 {
            padding-top: 8px;
        }

        .px-8 {
            padding-right: 8px;
            padding-left: 8px;
        }

        .pl-8 {
            padding-left: 8px;
        }

        .mr-8 {
            margin-right: 8px;
        }

        .mr-4 {
            margin-right: 4px;
        }

        .mb-8 {
            margin-bottom: 8px;
        }

        .mb-16 {
            margin-bottom: 8px;
        }

        .font-text {
            font-size: 12px;
        }

        .font-text-10 {
            font-size: 10px;
        }

        .border {
            border: 1px solid #000;
        }

        .text-bold {
            font-weight: bold;
        }

        span {
            display: inline-block;
            /* Mostrar en línea */
            vertical-align: middle;
            /* Alinear verticalmente al centro */
        }

        /* Estilo para el elemento después del span */
        .elemento-despues {
            display: block;
            /* Mostrar en bloque (debajo del span) */
            text-align: center;
            /* Alinear texto al centro */
        }
    </style>
</head>


<body>
    {{-- A. DATOS DEL ESTABLECIMIENTO - EMPRESA Y USUARIO --}}
    <div class="titulo-seccion">A. DATOS DEL ESTABLECIMIENTO - EMPRESA Y USUARIO</div>
    <table
        style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px;"
        border="1" cellpadding="0" cellspacing="0">
        <tr>
            <th style="width: 20%;">INSTITUCIÓN DEL SISTEMA O NOMBRE DE LA EMPRESA</th>
            <th style="width: 15%;">RUC</th>
            <th style="width: 10%;">CIIU</th>
            <th style="width: 15%;">ESTABLECIMIENTO DE SALUD</th>
            <th style="width: 10%;">NÚMERO DE HISTORIA</th>
            <th style="width: 10%;">NÚMERO DE ARCHIVO</th>
        </tr>
        <tr>
            <td align="center">{{ $configuracion['razon_social'] }}</td>
            <td align="center">{{ $configuracion['ruc'] }}</td>
            <td align="center">{{ $configuracion['ciiu'] }}</td>
            <td align="center">{{ 'CONSULTORIO MÉDICO' }}</td>
            <td align="center">{{ $empleado['identificacion'] }}</td>
            <td align="center">{{ $ficha_aptitud['numero_archivo'] }}</td>
        </tr>
    </table>

    <table
        style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px;"
        border="1" cellpadding="0" cellspacing="0" class="mb-8">
        <tr>
            <th style="width: 20%;">PRIMER APELLIDO</th>
            <th style="width: 15%;">SEGUNDO APELLIDO</th>
            <th style="width: 10%;">PRIMER NOMBRE</th>
            <th style="width: 15%;">SEGUNDO NOMBRE</th>
            <th style="width: 10%;">SEXO</th>
            <th style="width: 10%;">PUESTO DE TRABAJO (CIUO)</th>
        </tr>
        <tr>
            <td align="center">{{ explode(' ', $empleado['apellidos'])[0] }}</td>
            <td align="center">{{ explode(' ', $empleado['apellidos'])[1] }}</td>
            <td align="center">{{ explode(' ', $empleado['nombres'])[0] }}</td>
            <td align="center">{{ explode(' ', $empleado['nombres'])[1] }}</td>
            <td align="center">{{ $empleado['genero'] }}</td>
            <td align="center">{{ $empleado['cargo']->nombre }}</td>
        </tr>
    </table>

    {{-- B. DATOS GENERALES --}}
    <div class="titulo-seccion">B. DATOS GENERALES</div>
    <div class="border mb-8">
        <div class="pa-8 font-text-10">
            <span style="margin-right: 24px; display: inline-block;">FECHA DE EMISIÓN:</span>
            <span><span class="cuadrado">{{ $ficha_aptitud['fecha_emision']->year }}</span><small class="elemento-despues">aaaa</small></span>
            <span><span class="cuadrado">{{ $ficha_aptitud['fecha_emision']->format('m') }}</span></br>mm</span>
            <span><span class="cuadrado">{{ $ficha_aptitud['fecha_emision']->format('d') }}</span></br>dd</span>
        </div>
        <div class="pa-8 font-text-10 row items-center">
            <span style="margin-right: 24px; display: inline-block;">EVALUACIÓN:</span>
            <span class="mr-4">INGRESO</span>
            @if ($tipo_proceso_examen === 'INGRESO')
                <span class="cuadrado mr-8">{{ 'X' }}</span>
            @else
                <span class="cuadrado mr-8">&nbsp;&nbsp;</span>
            @endif
            <span class="mr-4">PERIÓDICO</span>
            @if ($tipo_proceso_examen === 'PERIODICO')
                <span class="cuadrado mr-8">{{ 'X' }}</span>
            @else
                <span class="cuadrado mr-8">&nbsp;&nbsp;</span>
            @endif
            <span class="mr-4">REINTEGRO</span>
            @if ($tipo_proceso_examen === 'REINTEGRO')
                <span class="cuadrado mr-8">{{ 'X' }}</span>
            @else
                <span class="cuadrado mr-8">&nbsp;&nbsp;</span>
            @endif
            <span class="mr-4">RETIRO</span>
            @if ($tipo_proceso_examen === 'RETIRO')
                <span class="cuadrado mr-8">{{ 'X' }}</span>
            @else
                <span class="cuadrado mr-8">&nbsp;&nbsp;</span>
            @endif
        </div>
    </div>

    {{-- C. APTITUD MÉDICA LABORAL --}}
    <div class="titulo-seccion">C. APTITUD MÉDICA LABORAL</div>
    <div class="border mb-8">
        <p class="px-4">Después de la valoración médica ocupacional se certifica que la persona en mención, es
            calificada como:</p>
        <table
            style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px;"
            border="1" cellpadding="0" cellspacing="0" class="mb-8">
            <tr>
                @foreach ($tipos_aptitudes_medicas_laborales as $tipo)
                    <th class="pa-4">{{ $tipo->nombre }}</th>
                    @if ($tipo->seleccionado)
                        <td class="pa-4" align="center">{{ 'X' }}</td>
                    @else
                        <td class="pa-4">&nbsp;&nbsp;</td>
                    @endif
                @endforeach
            </tr>
        </table>
        <p class="px-8"><b>DETALLE DE OBSERVACIONES:</b></p>
        <p class="px-8">{{ $ficha_aptitud['observaciones_aptitud_medica'] }}</p>
    </div>

    {{-- D. EVALUACIÓN MÉDICA DE RETIRO --}}
    <div class="titulo-seccion">D. EVALUACIÓN MÉDICA DE RETIRO</div>
    <div class="border mb-8 pt-8 pl-8">
        @foreach ($opcionesRespuestasTipoEvaluacionMedicaRetiro as $tipo)
            <div class="font-text mb-16">
                <span style="margin-right: 16px; display: inline-block; width: 45%;"
                    class="pa-4">{{ $tipo['nombre'] }}</span>
                @foreach ($tipo['posibles_respuestas'] as $posible_respuesta)
                    <span class="bg-green pa-8 font-text-10"
                        style="width: 10%; display: inline-block; text-align: right;">{{ $posible_respuesta }}</span>
                    @if ($posible_respuesta === $tipo['respuesta'])
                        <span class="pa-4 cuadrado" align="center"
                            style="display: inline-block;">{{ 'X' }}</span>
                    @else
                        <span class="pa-4 cuadrado" style="display: inline-block;">&nbsp;&nbsp;</span>
                    @endif
                @endforeach
            </div>
        @endforeach
    </div>

    {{-- E. RECOMENDACIONES --}}
    <div class="titulo-seccion">E. RECOMENDACIONES</div>
    <div class="border mb-8 pt-8 pl-8">
        <div class="text-bold">Descripción</div>
        <p>{{ $ficha_aptitud['recomendaciones'] }}</p>
    </div>

    <div class="border bg-green pa-8 mb-8">
        Con este documento certifico que el trabajador se ha sometido a la evaluación médica requerida para (el ingreso
        /la ejecución / el reintegro y retiro) al puesto laboral
        y se ha informado sobre los riesgos relacionado con el trabajo emitiendo recomendaciones relacionadas con su
        estado de salud.
    </div>

    <div class="mb-16">
        <b>NOTA:</b>
        La presente certificación se expide con base en la historia ocupacional del usuario (a), la cual tiene carácter
        de confidencialidad.
        <q-separator class="q-my-md" color="black" />
        <br>
        <br>
    </div>

    <br>

    {{-- F. DATOS DEL PROFESIONAL DE SALUD --}}
    <span style="width: 72%; display: inline-block;" class="border mr-8">
        <div class="titulo-seccion">F. DATOS DEL PROFESIONAL DE SALUD</div>
        <table style="table-layout:fixed; width: 100%;" border="1" cellpadding="0" cellspacing="0">
            <tr>
                <td class="bg-green font-text-10" style="padding: 6px;">NOMBRE Y APELLIDO</td>
                <td style="width: 20%" class="font-text-10">
                    {{ $profesionalSalud->empleado->nombres . ' ' . $profesionalSalud->empleado->apellidos }}</td>
                <td class="bg-green font-text-10">CÓDIGO</td>
                <td style="width: 20%">{{ $profesionalSalud->codigo }}</td>
                <td class="bg-green font-text-10">FIRMA Y SELLO</td>
                <td style="width: 20%">
                    @isset($firmaProfesionalMedico)
                        <img src="{{ $firmaProfesionalMedico }}" alt="" width="100%" height="40">
                    @endisset
                    @empty($firmaProfesionalMedico)
                        &nbsp;<br />
                    @endempty
                </td>
                {{-- <td style="width: 20%">&nbsp;</td> --}}
            </tr>
        </table>
    </span>

    {{-- G. FIRMA DEL USUARIO --}}
    <span style="width: 25%; display: inline-block;" class="border">
        <div class="titulo-seccion">G. FIRMA DEL USUARIO</div>
        <div class="pa-12">
            &nbsp;
            <br />
            <br />
            {{-- @isset($firmaPaciente)
                <img src="{{ $firmaPaciente }}" alt="" width="100%" height="40">
            @endisset
            @empty($firmaPaciente)
                &nbsp;<br />
            @endempty --}}
        </div>
    </span>

    <footer>
        <b style="float: left;">SNS-MSP / Form. CERT. 081 / 2019</b>
        <b style="float: right;">CERTIFICADO DE SALUD EN EL TRABAJO</b>
    </footer>
</body>

</html>
