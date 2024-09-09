<html>
@php
    $fecha = new Datetime();

    // $firma_profesional_salud = 'data:image/png;base64,' . base64_encode(file_get_contents(substr($profesionalSalud->firma_url, 1)));
    use App\Models\Medico\CategoriaExamenFisico;
    use App\Models\Medico\TipoAntecedenteFamiliar;
    use App\Models\Medico\SistemaOrganico;
    use Carbon\Carbon;
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>FICHA DE PREOCUPACIONAL</title>
    <style>
        @page {
            margin: 40px;
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

        .text-mini {
            font-size: 7px;
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

        .bg-titulo {
            background: #ccccff !important;
        }

        .bg-celeste {
            background: #ccffff !important;
        }

        td,
        th {
            /* padding: 4px; */
            word-wrap: break-word;
            border: 1px solid #000;
            font-size: 9px;
        }

        th {
            text-align: center;
        }

        table.celdas_amplias td,
        table.celdas_amplias th {
            padding: 4px;
            word-wrap: break-word;
            border: 1px solid #000;
            font-size: 9px;
        }

        .border-none {
            border: none !important;
        }

        table {
            border-collapse: collapse;
        }

        .titulo-seccion {
            background: #ccccff;
            border: 1px solid #000;
            padding: 4px;
            font-size: 10px;
            font-weight: bold;
        }

        .subtitulo-seccion {
            background: #ccffcc;
            border: 1px solid #000;
            padding: 4px;
            font-size: 10px;
            font-weight: bold;
        }

        .subtitulo2-seccion {
            background: #ccffff;
            border: 1px solid #000;
            padding: 4px;
            font-size: 10px;
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

        .mb-4 {
            margin-bottom: 4px;
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

        .fs-10 {
            font-size: 10px;
        }

        .fs-9 {
            font-size: 9px !important;
        }

        .fs-8 {
            font-size: 8px !important;
        }

        .border {
            border: 1px solid #000;
        }

        .text-bold {
            font-weight: bold;
        }

        .texto-vertical {
            /* Propiedad para IE */
            filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=2);

            /* Propiedad para otros navegadores */
            -webkit-transform: rotate(-90deg);
            /* Chrome, Safari, Opera */
            -moz-transform: rotate(-90deg);
            /* Firefox */
            -ms-transform: rotate(-90deg);
            /* IE 9+ */
            -o-transform: rotate(-90deg);
            /* Opera */
            transform: rotate(-90deg);
            /* CSS3 */

            white-space: nowrap;
            text-align: center;
            width: max-content;
            display: block;
            /* font-size: 10px; */
        }

        .celda {
            width: 5px !important;
            /* background: #ccffff; */
            position: relative;
        }

        .d-inline-block {
            display: inline-block;
        }
    </style>
</head>


<body>
    {{-- A. DATOS DEL ESTABLECIMIENTO - EMPRESA Y USUARIO --}}
    <div class="titulo-seccion">A. DATOS DEL ESTABLECIMIENTO - EMPRESA Y USUARIO</div>
    <table
        style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px;"
        border="1" cellpadding="0" cellspacing="0" class="celdas_amplias">
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
            <td align="center">{{ '' }}</td>
        </tr>
    </table>

    <table
        style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px;"
        border="1" cellpadding="0" cellspacing="0" class="celdas_amplias">
        <tr>
            <th rowspan="2" style="width: 10%;">PRIMER APELLIDO</th>
            <th rowspan="2" style="width: 10%;">SEGUNDO APELLIDO</th>
            <th rowspan="2" style="width: 10%;">PRIMER NOMBRE</th>
            <th rowspan="2" style="width: 10%;">SEGUNDO NOMBRE</th>
            <th rowspan="2" style="width: 8%;">SEXO</th>
            <th rowspan="2" style="width: 8%;">EDAD <br> <small class="text-mini">AÑOS</small></th>
            <th rowspan="1" style="width: 5%;" colspan="5">RELIGIÓN</th>
            <th rowspan="2" style="width: 10%;">GRUPO SANGUINEO</th>
            <th rowspan="2" style="width: 10%;">LATERALIDAD</th>
        </tr>
        <tr>
            <td class="bg-green celda" style="padding-top: 20px; padding-bottom: 20px; width: 20%;"><small
                    class="text-mini texto-vertical">CATÓLICA</small></td>
            <td class="bg-green celda" style="padding-top: 20px; padding-bottom: 20px; width: 20%;"><small
                    class="text-mini texto-vertical">EVANGELICA</small></td>
            <td class="bg-green celda" style="padding-top: 20px; padding-bottom: 20px; width: 20%;"><small
                    class="text-mini texto-vertical">TESTIGOS DE <br> JEHOVÁ</small></td>
            <td class="bg-green celda" style="padding-top: 20px; padding-bottom: 20px; width: 20%;"><small
                    class="text-mini texto-vertical">MORMONA</small></td>
            <td class="bg-green celda" style="padding-top: 20px; padding-bottom: 20px; width: 20%;"><small
                    class="text-mini texto-vertical">OTRAS</small></td>
        </tr>
        <tr>
            <td align="center">{{ explode(' ', $empleado['apellidos'])[0] }}</td>
            <td align="center">{{ explode(' ', $empleado['apellidos'])[1] }}</td>
            <td align="center">{{ explode(' ', $empleado['nombres'])[0] }}</td>
            <td align="center">{{ explode(' ', $empleado['nombres'])[1] }}</td>
            <td align="center">{{ $empleado['genero'] }}</td>
            <td align="center">{{ $empleado['edad'] }}</td>

            <td align="center">{{ $ficha_preocupacional['religion'] == 1 ? 'x' : '' }}</td>
            <td align="center">{{ $ficha_preocupacional['religion'] == 2 ? 'x' : '' }}</td>
            <td align="center">{{ $ficha_preocupacional['religion'] == 3 ? 'x' : '' }}</td>
            <td align="center">{{ $ficha_preocupacional['religion'] == 4 ? 'x' : '' }}</td>
            <td align="center">{{ $ficha_preocupacional['religion'] == 5 ? 'x' : '' }}</td>

            <td align="center">{{ $empleado['tipo_sangre'] }}</td>
            <td align="center">{{ $empleado['lateralidad'] }}</td>
        </tr>
    </table>

    <table
        style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px;"
        border="1" cellpadding="0" cellspacing="0" class="  mb-8">
        <tr>
            <th rowspan="1" colspan="5" style="width: 10%;">ORIENTACIÓN SEXUAL</th>
            <th rowspan="1" colspan="5" style="width: 10%;">IDENTIDAD DE GÉNERO</th>
            <th rowspan="1" colspan="4" style="width: 20%;">DISCAPACIDAD</th>
            <th rowspan="2" style="width: 10%;">FECHA DE INGRESO AL TRABAJO <br> <small
                    class="text-mini">{{ 'aaaa/mm/dd' }}</small></th>
            <th rowspan="2" style="width: 15%;">PUESTO DE TRABAJO <br> <small class="text-mini">(CIUO)</small></th>
            <th rowspan="2" style="width: 15%;">ÁREA DE TRABAJO</th>
            <th rowspan="2" style="width: 20%;">ACTIVIDADES RELEVANTES AL PUESTO DE TRABAJO A OCUPAR</th>
        </tr>

        <tr>
            <td class="bg-green celda" style="padding-top: 20px; padding-bottom: 20px; width: 20%;"><small
                    class="text-mini texto-vertical">LESBIANA</small></td>
            <td class="bg-green celda" style="padding-top: 20px; padding-bottom: 20px; width: 20%;"><small
                    class="text-mini texto-vertical">GAY</small></td>
            <td class="bg-green celda" style="padding-top: 20px; padding-bottom: 20px; width: 20%;"><small
                    class="text-mini texto-vertical">BISEXUAL</small></td>
            <td class="bg-green celda" style="padding-top: 20px; padding-bottom: 20px; width: 20%;"><small
                    class="text-mini texto-vertical">HETEROSEXUAL</small></td>
            <td class="bg-green celda" style="padding-top: 20px; padding-bottom: 20px; width: 20%;"><small
                    class="text-mini texto-vertical">NO SABE/NO <br> RESPONDE</small></td>
            <td class="bg-green celda" style="padding-top: 20px; padding-bottom: 20px; width: 20%;"><small
                    class="text-mini texto-vertical">FEMENINO</small></td>
            <td class="bg-green celda" style="padding-top: 20px; padding-bottom: 20px; width: 20%;"><small
                    class="text-mini texto-vertical">MASCULINO</small></td>
            <td class="bg-green celda" style="padding-top: 20px; padding-bottom: 20px; width: 20%;"><small
                    class="text-mini texto-vertical">TRANS- <br> FEMENINO</small></td>
            <td class="bg-green celda" style="padding-top: 20px; padding-bottom: 20px; width: 20%;"><small
                    class="text-mini texto-vertical">TRANS- <br> MASCULINO</small></td>
            <td class="bg-green celda" style="padding-top: 20px; padding-bottom: 20px; width: 20%;"><small
                    class="text-mini texto-vertical">NO SABE/NO <br> RESPONDE</small></td>
            <td class="bg-green celda" style="padding-top: 20px; padding-bottom: 20px; width: 20%;"><span
                    class="text-mini texto-vertical">SI</span></td>
            <td class="bg-green celda" style="padding-top: 20px; padding-bottom: 20px; width: 20%;"><span
                    class="text-mini texto-vertical">NO</span></td>
            <td class="bg-green celda" style="padding-top: 20px; padding-bottom: 20px; width: 60%;"><span
                    class="text-mini texto-vertical">TIPO</span></td>
            <td class="bg-green celda" style="padding-top: 20px; padding-bottom: 20px; width: 20%;"><span
                    class="text-mini texto-vertical"> (%) </span></td>
        </tr>

        <tr>
            <td align="center" style="width: 5%;">{{ $ficha_preocupacional['orientacion_sexual'] == 1 ? 'x' : '' }}
            </td>
            <td align="center" style="width: 5%;">{{ $ficha_preocupacional['orientacion_sexual'] == 2 ? 'x' : '' }}
            </td>
            <td align="center" style="width: 5%;">{{ $ficha_preocupacional['orientacion_sexual'] == 3 ? 'x' : '' }}
            </td>
            <td align="center" style="width: 5%;">{{ $ficha_preocupacional['orientacion_sexual'] == 4 ? 'x' : '' }}
            </td>
            <td align="center" style="width: 5%;">{{ $ficha_preocupacional['orientacion_sexual'] == 5 ? 'x' : '' }}
            </td>

            <td align="center" style="width: 5%;">{{ $ficha_preocupacional['identidad_genero'] == 1 ? 'x' : '' }}
            </td>
            <td align="center" style="width: 5%;">{{ $ficha_preocupacional['identidad_genero'] == 2 ? 'x' : '' }}
            </td>
            <td align="center" style="width: 5%;">{{ $ficha_preocupacional['identidad_genero'] == 3 ? 'x' : '' }}
            </td>
            <td align="center" style="width: 5%;">{{ $ficha_preocupacional['identidad_genero'] == 4 ? 'x' : '' }}
            </td>
            <td align="center" style="width: 5%;">{{ $ficha_preocupacional['identidad_genero'] == 5 ? 'x' : '' }}
            </td>

            <td align="center" style="width: 5%;">{{ $empleado['tiene_discapacidad'] ? 'x' : '' }}</td>
            <td align="center" style="width: 5%;">{{ !$empleado['tiene_discapacidad'] ? 'x' : '' }}</td>
            <td style="width: 10%;">
                @foreach ($empleado->tiposDiscapacidades as $discapacidad)
                    <div class="fs-9">{{ $discapacidad->nombre }}</div>
                @endforeach
            </td>

            <td style="width: 10%;">
                @foreach ($empleado->tiposDiscapacidades as $discapacidad)
                    <div class="fs-9">{{ $discapacidad->pivot->porcentaje . '%' }}</div>
                @endforeach
            </td>

            <td style="width: 5%;" align="center">{{ $empleado['fecha_ingreso'] }}</td>
            <td style="width: 5%;" align="center">{{ $empleado['cargo']->nombre }}</td>
            <td style="width: 5%;" align="center">{{ $empleado['departamento']->nombre }}</td>
            <td style="width: 5%;" align="center">{{ '' }}</td>
        </tr>

    </table>

    {{-- B. MOTIVO DE LA CONSULTA --}}
    <div class="titulo-seccion">B. MOTIVO DE LA CONSULTA</div>
    <table
        style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px;"
        border="1" cellpadding="0" cellspacing="0" class="celdas_amplias mb-8">
        <tr>
            <td>{{ $ficha_preocupacional['motivo_consulta'] }}</td>
        </tr>
    </table>

    {{-- C. ANTECEDENTES PERSONALES --}}
    <div class="titulo-seccion">C. ANTECEDENTES PERSONALES</div>
    <table
        style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px;"
        border="1" cellpadding="0" cellspacing="0" class="celdas_amplias">
        <tr>
            <th style="width: 100%; text-align: left;">ANTECEDENTES CLÍNICOS Y QUIRÚRGICOS</th>
        </tr>
        <tr>
            <td>{{ $ficha_preocupacional['antecedente_personal']['antecedentes_quirurgicos'] }}</td>
        </tr>
    </table>

    <table style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif;"
        border="1" cellpadding="0" cellspacing="0" class="celdas_amplias">
        <tr>
            <th style="width: 10%; text-align: left;" colspan="14">ANTECEDENTES GINECO OBSTÉTRICOS</th>
        </tr>

        <tr>
            <td style="width: 10%;" class="bg-celeste fs-8" rowspan="2" colspan="1">{{ 'MENARQUÍA' }}</td>
            <td style="width: 7%;" class="bg-celeste fs-8" rowspan="2" colspan="1">{{ 'CICLOS' }}</td>
            <td style="width: 12%;" class="bg-celeste fs-8" rowspan="2" colspan="1" align="center">
                {{ 'FECHA DE ÚLTIMA MENSTRUACIÓN' }}</td>
            <td style="width: 8%;" class="bg-celeste fs-8" rowspan="2" colspan="1">{{ 'GESTAS' }}</td>
            <td style="width: 8%;" class="bg-celeste fs-8" rowspan="2" colspan="1">{{ 'PARTOS' }}</td>
            <td style="width: 10%;" class="bg-celeste fs-8" rowspan="2" colspan="1">{{ 'CESÁREAS' }}</td>
            <td style="width: 9%;" class="bg-celeste fs-8" rowspan="2" colspan="1">{{ 'ABORTOS' }}</td>
            <td class="bg-celeste fs-8" rowspan="1" colspan="2" align="center">{{ 'HIJOS' }}</td>
            <td class="bg-celeste fs-8" rowspan="1" colspan="2" align="center">{{ 'VIDA SEXUAL ACTIVA' }}
            </td>
            <td style="width: 10%;" class="bg-celeste fs-8" rowspan="1" colspan="3">
                {{ 'MÉTODO DE PLANIFICACIÓN FAMILIAR' }}</td>
        </tr>

        <tr>
            <td style="width: 6%;" class="bg-celeste fs-8" rowspan="1">{{ 'VIVOS' }}</td>
            <td style="width: 8%;" class="bg-celeste fs-8" rowspan="1">{{ 'MUERTOS' }}</td>
            <td style="width: 4%;" class="bg-celeste fs-8" rowspan="1">{{ 'SI' }}</td>
            <td style="width: 4%;" class="bg-celeste fs-8" rowspan="1">{{ 'NO' }}</td>
            <td style="width: 4%;" class="bg-celeste fs-8" rowspan="1">{{ 'SI' }}</td>
            <td style="width: 4%;" class="bg-celeste fs-8" rowspan="1">{{ 'NO' }}</td>
            <td style="width: 20%;" class="bg-celeste fs-8" rowspan="1">{{ 'TIPO' }}</td>
        </tr>

        <tr>
            <td>{{ isset($ficha_preocupacional['antecedentes_gineco_obstetricos']['menarquia']) ? $ficha_preocupacional['antecedentes_gineco_obstetricos']['menarquia'] : '' }}
            </td>
            <td>{{ isset($ficha_preocupacional['antecedentes_gineco_obstetricos']['menarquia']) ? $ficha_preocupacional['antecedentes_gineco_obstetricos']['ciclos'] : '' }}
            </td>
            <td>{{ isset($ficha_preocupacional['antecedentes_gineco_obstetricos']['menarquia']) ? $ficha_preocupacional['antecedentes_gineco_obstetricos']['fecha_ultima_menstruacion'] : '' }}
            </td>
            <td>{{ isset($ficha_preocupacional['antecedentes_gineco_obstetricos']['menarquia']) ? $ficha_preocupacional['antecedentes_gineco_obstetricos']['gestas'] : '' }}
            </td>
            <td>{{ isset($ficha_preocupacional['antecedentes_gineco_obstetricos']['menarquia']) ? $ficha_preocupacional['antecedentes_gineco_obstetricos']['partos'] : '' }}
            </td>
            <td>{{ isset($ficha_preocupacional['antecedentes_gineco_obstetricos']['menarquia']) ? $ficha_preocupacional['antecedentes_gineco_obstetricos']['cesareas'] : '' }}
            </td>
            <td>{{ isset($ficha_preocupacional['antecedentes_gineco_obstetricos']['menarquia']) ? $ficha_preocupacional['antecedentes_gineco_obstetricos']['abortos'] : '' }}
            </td>
            <td>{{ isset($ficha_preocupacional['antecedente_personal']['hijos_vivos']) ? $ficha_preocupacional['antecedente_personal']['hijos_vivos'] : '' }}
            </td>
            <td>{{ isset($ficha_preocupacional['antecedente_personal']['hijos_muertos']) ? $ficha_preocupacional['antecedente_personal']['hijos_muertos'] : '' }}
            </td>
            <td>{{ isset($ficha_preocupacional['antecedente_personal']['vida_sexual_activa']) && $ficha_preocupacional['antecedente_personal']['vida_sexual_activa'] ? 'x' : '' }}
            </td>
            <td>{{ isset($ficha_preocupacional['antecedente_personal']['vida_sexual_activa']) && !$ficha_preocupacional['antecedente_personal']['vida_sexual_activa'] ? 'x' : '' }}
            </td>
            <td>{{ isset($ficha_preocupacional['antecedente_personal']['tiene_metodo_planificacion_familiar']) && $ficha_preocupacional['antecedente_personal']['tiene_metodo_planificacion_familiar'] ? 'x' : '' }}
            </td>
            <td>{{ isset($ficha_preocupacional['antecedente_personal']['tiene_metodo_planificacion_familiar']) && !$ficha_preocupacional['antecedente_personal']['tiene_metodo_planificacion_familiar'] ? 'x' : '' }}
            </td>
            <td>{{ isset($ficha_preocupacional['antecedente_personal']['tipo_metodo_planificacion_familiar']) ? $ficha_preocupacional['antecedente_personal']['tipo_metodo_planificacion_familiar'] : '' }}
            </td>
        </tr>
    </table>

    <table
        style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px;"
        border="1" cellpadding="0" cellspacing="0">
        <tr>
            <td class="bg-celeste" style="width: 20%;">{{ 'EXÁMENES REALIZADOS' }}</td>
            <td class="bg-celeste" style="width: 4%;">{{ 'SI' }}</td>
            <td class="bg-celeste" style="width: 4%;">{{ 'NO' }}</td>
            <td class="bg-celeste" style="width: 11%;">{{ 'TIEMPO' }} <br> <small
                    class="text-mini">{{ '(años)' }}</small> </td>
            <td class="bg-celeste" style="width: 11%;">{{ 'RESULTADO' }}</td>
            <td class="bg-celeste" style="width: 20%;">{{ 'EXÁMENES REALIZADOS' }}</td>
            <td class="bg-celeste" style="width: 4%;">{{ 'SI' }}</td>
            <td class="bg-celeste" style="width: 4%;">{{ 'NO' }}</td>
            <td class="bg-celeste" style="width: 11%;">{{ 'TIEMPO(años)' }}</td>
            <td class="bg-celeste" style="width: 11%;">{{ 'RESULTADO' }}</td>
        </tr>
        <tr>
            {{-- Papanicolaou --}}
            <td>{{ 1 <= count($ficha_preocupacional['examenes_realizados']) && $ficha_preocupacional['examenes_realizados'][0]['tipo'] == 'F' ? $ficha_preocupacional['examenes_realizados'][0]['examen'] : 'PAPANICOLAU' }}
            </td>
            <td>{{ 1 <= count($ficha_preocupacional['examenes_realizados']) && $ficha_preocupacional['examenes_realizados'][0]['tipo'] == 'F' ? ($ficha_preocupacional['examenes_realizados'][0]['resultado'] ? 'x' : '') : '' }}
            </td>
            <td>{{ 1 <= count($ficha_preocupacional['examenes_realizados']) && $ficha_preocupacional['examenes_realizados'][0]['tipo'] == 'F' ? (!$ficha_preocupacional['examenes_realizados'][0]['resultado'] ? 'x' : '') : '' }}
            </td>
            <td>{{ 1 <= count($ficha_preocupacional['examenes_realizados']) && $ficha_preocupacional['examenes_realizados'][0]['tipo'] == 'F' ? $ficha_preocupacional['examenes_realizados'][0]['tiempo'] : '' }}
            </td>
            <td>{{ 1 <= count($ficha_preocupacional['examenes_realizados']) && $ficha_preocupacional['examenes_realizados'][0]['tipo'] == 'F' ? $ficha_preocupacional['examenes_realizados'][0]['resultado'] : '' }}
            </td>
            {{-- Ecomamario --}}
            <td>{{ 3 <= count($ficha_preocupacional['examenes_realizados']) && $ficha_preocupacional['examenes_realizados'][0]['tipo'] == 'F' ? $ficha_preocupacional['examenes_realizados'][2]['examen'] : 'ECO MAMARIO' }}
            </td>
            <td>{{ 3 <= count($ficha_preocupacional['examenes_realizados']) && $ficha_preocupacional['examenes_realizados'][0]['tipo'] == 'F' ? ($ficha_preocupacional['examenes_realizados'][2]['resultado'] ? 'x' : '') : '' }}
            </td>
            <td>{{ 3 <= count($ficha_preocupacional['examenes_realizados']) && $ficha_preocupacional['examenes_realizados'][0]['tipo'] == 'F' ? (!$ficha_preocupacional['examenes_realizados'][2]['resultado'] ? 'x' : '') : '' }}
            </td>
            <td>{{ 3 <= count($ficha_preocupacional['examenes_realizados']) && $ficha_preocupacional['examenes_realizados'][0]['tipo'] == 'F' ? $ficha_preocupacional['examenes_realizados'][2]['tiempo'] : '' }}
            </td>
            <td>{{ 3 <= count($ficha_preocupacional['examenes_realizados']) && $ficha_preocupacional['examenes_realizados'][0]['tipo'] == 'F' ? $ficha_preocupacional['examenes_realizados'][2]['resultado'] : '' }}
            </td>
        </tr>
        <tr>
            {{-- Colposcopia --}}
            <td>{{ 2 <= count($ficha_preocupacional['examenes_realizados']) && $ficha_preocupacional['examenes_realizados'][0]['tipo'] == 'F' ? $ficha_preocupacional['examenes_realizados'][1]['examen'] : 'COLPOSCOPIA' }}
            </td>
            <td>{{ 2 <= count($ficha_preocupacional['examenes_realizados']) && $ficha_preocupacional['examenes_realizados'][0]['tipo'] == 'F' ? ($ficha_preocupacional['examenes_realizados'][1]['resultado'] ? 'x' : '') : '' }}
            </td>
            <td>{{ 2 <= count($ficha_preocupacional['examenes_realizados']) && $ficha_preocupacional['examenes_realizados'][0]['tipo'] == 'F' ? (!$ficha_preocupacional['examenes_realizados'][1]['resultado'] ? 'x' : '') : '' }}
            </td>
            <td>{{ 2 <= count($ficha_preocupacional['examenes_realizados']) && $ficha_preocupacional['examenes_realizados'][0]['tipo'] == 'F' ? $ficha_preocupacional['examenes_realizados'][1]['tiempo'] : '' }}
            </td>
            <td>{{ 2 <= count($ficha_preocupacional['examenes_realizados']) && $ficha_preocupacional['examenes_realizados'][0]['tipo'] == 'F' ? $ficha_preocupacional['examenes_realizados'][1]['resultado'] : '' }}
            </td>
            {{-- Mamografia --}}
            <td>{{ 4 <= count($ficha_preocupacional['examenes_realizados']) && $ficha_preocupacional['examenes_realizados'][0]['tipo'] == 'F' ? $ficha_preocupacional['examenes_realizados'][3]['examen'] : 'MAMOGRAFÍA' }}
            </td>
            <td>{{ 4 <= count($ficha_preocupacional['examenes_realizados']) && $ficha_preocupacional['examenes_realizados'][0]['tipo'] == 'F' ? ($ficha_preocupacional['examenes_realizados'][3]['resultado'] ? 'x' : '') : '' }}
            </td>
            <td>{{ 4 <= count($ficha_preocupacional['examenes_realizados']) && $ficha_preocupacional['examenes_realizados'][0]['tipo'] == 'F' ? (!$ficha_preocupacional['examenes_realizados'][3]['resultado'] ? 'x' : '') : '' }}
            </td>
            <td>{{ 4 <= count($ficha_preocupacional['examenes_realizados']) && $ficha_preocupacional['examenes_realizados'][0]['tipo'] == 'F' ? $ficha_preocupacional['examenes_realizados'][3]['tiempo'] : '' }}
            </td>
            <td>{{ 4 <= count($ficha_preocupacional['examenes_realizados']) && $ficha_preocupacional['examenes_realizados'][0]['tipo'] == 'F' ? $ficha_preocupacional['examenes_realizados'][3]['resultado'] : '' }}
            </td>
        </tr>
    </table>

    {{-- MASCULINO --}}
    <table
        style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px;"
        border="1" cellpadding="0" cellspacing="0" class="celdas_amplias">
        <tr>
            <th style="width: 100%; text-align: left;" colspan="10">ANTECEDENTES REPRODUCTIVOS MASCULINOS</th>
        </tr>
        <tr>
            <td rowspan="2" class="bg-celeste" style="width: 20%;">{{ 'EXÁMENES REALIZADOS' }}</td>
            <td rowspan="2" class="bg-celeste" style="width: 4%;">{{ 'SI' }}</td>
            <td rowspan="2" class="bg-celeste" style="width: 4%;">{{ 'NO' }}</td>
            <td rowspan="2" class="bg-celeste" style="width: 11%;">{{ 'TIEMPO(años)' }}</td>
            <td rowspan="2" class="bg-celeste" style="width: 11%;">{{ 'RESULTADO' }}</td>
            <td style="width: 10%;" class="bg-celeste fs-8" rowspan="1" colspan="3" align="center">
                {{ 'MÉTODO DE PLANIFICACIÓN FAMILIAR' }}</td>
            <td class="bg-celeste fs-8" rowspan="1" colspan="2" align="center">{{ 'HIJOS' }}</td>
        </tr>

        <tr>
            <td style="width: 4%;" class="bg-celeste fs-8" rowspan="1">{{ 'SI' }}</td>
            <td style="width: 4%;" class="bg-celeste fs-8" rowspan="1">{{ 'NO' }}</td>
            <td style="width: 20%;" class="bg-celeste fs-8" rowspan="1">{{ 'TIPO' }}</td>
            <td style="width: 6%;" class="bg-celeste fs-8" rowspan="1">{{ 'VIVOS' }}</td>
            <td style="width: 8%;" class="bg-celeste fs-8" rowspan="1">{{ 'MUERTOS' }}</td>
        </tr>

        <tr>
            {{-- Antigeno prostatico --}}
            <td>{{ 1 <= count($ficha_preocupacional['examenes_realizados']) && $ficha_preocupacional['examenes_realizados'][0]['tipo'] == 'M' ? $ficha_preocupacional['examenes_realizados'][0]['examen'] : 'ANTÍGENO PROSTÁTICO' }}
            </td>
            <td>{{ 1 <= count($ficha_preocupacional['examenes_realizados']) && $ficha_preocupacional['examenes_realizados'][0]['tipo'] == 'M' ? ($ficha_preocupacional['examenes_realizados'][0]['resultado'] ? 'x' : '') : '' }}
            </td>
            <td>{{ 1 <= count($ficha_preocupacional['examenes_realizados']) && $ficha_preocupacional['examenes_realizados'][0]['tipo'] == 'M' ? (!$ficha_preocupacional['examenes_realizados'][0]['resultado'] ? 'x' : '') : '' }}
            </td>
            <td>{{ 1 <= count($ficha_preocupacional['examenes_realizados']) && $ficha_preocupacional['examenes_realizados'][0]['tipo'] == 'M' ? $ficha_preocupacional['examenes_realizados'][0]['tiempo'] : '' }}
            </td>
            <td>{{ 1 <= count($ficha_preocupacional['examenes_realizados']) && $ficha_preocupacional['examenes_realizados'][0]['tipo'] == 'M' ? $ficha_preocupacional['examenes_realizados'][0]['resultado'] : '' }}
            </td>

            <td rowspan="2">
                {{ isset($ficha_preocupacional['antecedente_personal']['tiene_metodo_planificacion_familiar']) && $ficha_preocupacional['antecedente_personal']['tiene_metodo_planificacion_familiar'] ? 'x' : '' }}
            </td>
            <td rowspan="2">
                {{ isset($ficha_preocupacional['antecedente_personal']['tiene_metodo_planificacion_familiar']) && !$ficha_preocupacional['antecedente_personal']['tiene_metodo_planificacion_familiar'] ? 'x' : '' }}
            </td>
            <td rowspan="2">
                {{ isset($ficha_preocupacional['antecedente_personal']['tipo_metodo_planificacion_familiar']) ? $ficha_preocupacional['antecedente_personal']['tipo_metodo_planificacion_familiar'] : '' }}
            </td>
            <td rowspan="2">
                {{ isset($ficha_preocupacional['antecedente_personal']['hijos_vivos']) ? $ficha_preocupacional['antecedente_personal']['hijos_vivos'] : '' }}
            </td>
            <td rowspan="2">
                {{ isset($ficha_preocupacional['antecedente_personal']['hijos_muertos']) ? $ficha_preocupacional['antecedente_personal']['hijos_muertos'] : '' }}
            </td>
        </tr>
        <tr>
            {{-- Antigeno prostatico --}}
            <td>{{ 2 <= count($ficha_preocupacional['examenes_realizados']) && $ficha_preocupacional['examenes_realizados'][1]['tipo'] == 'M' ? $ficha_preocupacional['examenes_realizados'][1]['examen'] : 'ECO PROSTÁTICO' }}
            </td>
            <td>{{ 2 <= count($ficha_preocupacional['examenes_realizados']) && $ficha_preocupacional['examenes_realizados'][1]['tipo'] == 'M' ? ($ficha_preocupacional['examenes_realizados'][1]['resultado'] ? 'x' : '') : '' }}
            </td>
            <td>{{ 2 <= count($ficha_preocupacional['examenes_realizados']) && $ficha_preocupacional['examenes_realizados'][1]['tipo'] == 'M' ? (!$ficha_preocupacional['examenes_realizados'][1]['resultado'] ? 'x' : '') : '' }}
            </td>
            <td>{{ 2 <= count($ficha_preocupacional['examenes_realizados']) && $ficha_preocupacional['examenes_realizados'][1]['tipo'] == 'M' ? $ficha_preocupacional['examenes_realizados'][1]['tiempo'] : '' }}
            </td>
            <td>{{ 2 <= count($ficha_preocupacional['examenes_realizados']) && $ficha_preocupacional['examenes_realizados'][1]['tipo'] == 'M' ? $ficha_preocupacional['examenes_realizados'][1]['resultado'] : '' }}
            </td>
        </tr>
    </table>


    <table
        style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px;"
        border="1" cellpadding="0" cellspacing="0" class="mb-8">
        <tr>
            <th style="width: 100%; text-align: left;" colspan="7">HÁBITOS TÓXICOS</th>
        </tr>
        <tr>
            {{-- tabla 1 --}}
            <td style="width: 50%; vertical-align: top;" class="border-none">
                <table
                    style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px;"
                    border="1" cellpadding="0" cellspacing="0" class="border-none">
                    <tr>
                        <td align="center" class="fs-9 bg-celeste text-bold" style="width: 20%;">
                            {{ 'CONSUMOS NOCIVOS' }}</td>
                        <td align="center" class="fs-9 bg-celeste" style="width: 4%;">{{ 'SI' }}</td>
                        <td align="center" class="fs-9 bg-celeste" style="width: 4%;">{{ 'NO' }}</td>
                        <td align="center" class="fs-9 bg-celeste" style="width: 11%;">
                            {{ 'TIEMPO DE CONSUMO' }} <br> <small class="text-mini">{{ '(meses)' }}</small>
                        </td>
                        <td align="center" class="fs-9 bg-celeste" style="width: 11%;">{{ 'CANTIDAD' }}</td>
                        <td align="center" class="fs-9 bg-celeste" style="width: 11%;">{{ 'EX CONSUMIDOR' }}</td>
                        <td align="center" class="fs-9 bg-celeste" style="width: 11%;">
                            {{ 'TIEMPO DE ABSTINENCIA' }} <br> <small class="text-mini">{{ '(meses)' }}</small>
                        </td>
                    </tr>

                    @foreach ($ficha_preocupacional['habitos_toxicos'] as $habito)
                        <tr>
                            <td>{{ $habito['tipo_habito_toxico'] }}</td>
                            <td>{{ $habito['aplica'] ? 'x' : '' }}</td>
                            <td>{{ !$habito['aplica'] ? 'x' : '' }}</td>
                            <td>{{ $habito['tiempo_consumo_meses'] }}</td>
                            <td>{{ $habito['cantidad'] }}</td>
                            <td>{{ $habito['ex_consumidor'] ? 'x' : '' }}</td>
                            <td>{{ $habito['tiempo_abstinencia_meses'] }}</td>
                        </tr>
                    @endforeach
                </table>
            </td>

            {{-- tabla 2 --}}
            <td style="width: 50%; vertical-align: top;" class="border-none">
                <table
                    style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px;"
                    border="1" cellpadding="0" cellspacing="0" class="border-none">
                    <tr>
                        <td align="center" class="fs-9 bg-celeste text-bold" style="width: 20%;">
                            {{ 'ESTILO' }}</td>
                        <td align="center" class="fs-9 bg-celeste" style="width: 4%;">{{ 'SI' }}</td>
                        <td align="center" class="fs-9 bg-celeste" style="width: 4%;">{{ 'NO' }}</td>
                        <td align="center" class="fs-9 bg-celeste" style="width: 11%;">{{ '¿CUÁL?' }}</td>
                        <td align="center" class="fs-9 bg-celeste" style="width: 11%;">{{ 'TIEMPO/CANTIDAD' }}</td>
                    </tr>

                    <tr>
                        <td>{{ 'ACTIVIDAD FÍSICA' }}</td>
                        <td>{{ $ficha_preocupacional['actividades_fisicas'] ? (count($ficha_preocupacional['actividades_fisicas']) ? 'x' : '') : '' }}
                        </td>
                        <td>{{ $ficha_preocupacional['actividades_fisicas'] ? (!count($ficha_preocupacional['actividades_fisicas']) ? 'x' : '') : '' }}
                        </td>
                        <td>{{ $ficha_preocupacional['actividades_fisicas'] && count($ficha_preocupacional['actividades_fisicas']) ? $ficha_preocupacional['actividades_fisicas'][0]['nombre_actividad'] : '' }}
                        </td>
                        <td>{{ $ficha_preocupacional['actividades_fisicas'] && count($ficha_preocupacional['actividades_fisicas']) ? $ficha_preocupacional['actividades_fisicas'][0]['tiempo'] : '' }}
                        </td>
                    </tr>
                    @foreach ($ficha_preocupacional['medicaciones'] as $medicacion)
                        <tr>
                            @if ($loop->index == 0)
                                <td rowspan="2">{{ 'MEDICACIÓN HABITUAL' }}</td> {{-- rowspan igual al total de elementos de medicaciones --}}
                                <td rowspan="2">{{ count($ficha_preocupacional['medicaciones']) ? 'x' : '' }}</td>
                                <td rowspan="2">{{ !count($ficha_preocupacional['medicaciones']) ? 'x' : '' }}
                                </td>
                            @endif

                            <td>{{ $medicacion['nombre'] }}</td>
                            <td>{{ $medicacion['cantidad'] }}</td>
                        </tr>
                    @endforeach
                </table>
            </td>
        </tr>
    </table>

    {{-- D. ANTECEDENTES DE TRABAJO --}}
    <div class="titulo-seccion">D. ANTECEDENTES DE TRABAJO</div>
    <table style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif;"
        border="1" cellpadding="0" cellspacing="0" class="celdas_amplias">
        <tr>
            <th style="width: 100%; text-align: left;" colspan="11">ANTECEDENTES DE EMPLEOS ANTERIORES</th>
        </tr>
        <tr>
            <td rowspan="2" align="center" style="width: 15%;" class="bg-green">EMPRESA</td>
            <td rowspan="2" align="center" style="width: 15%;" class="bg-green">PUESTO DE TRABAJO</td>
            <td rowspan="2" align="center" style="width: 25%;" class="bg-green">ACTIVIDADES QUE DESEMPEÑABA</td>
            <td rowspan="2" align="center" style="width: 10%;" class="bg-green">TIEMPO DE TRABAJO <br> <small
                    class="text-mini">(meses)</small></td>
            <td rowspan="1" align="center" colspan="6" style="width: 20%;" class="bg-green fs-10">RIESGO</td>
            <td rowspan="2" align="center" style="width: 15%;" class="bg-green fs-10">OBSERVACIONES</td>
        </tr>

        <tr>
            <td class="bg-green celda" style="padding-bottom: 32px; padding-top: 32px;"><span
                    class="texto-vertical fs-9">FÍSICO</span></td>
            <td class="bg-green celda" style="padding-bottom: 32px; padding-top: 32px;"><span
                    class="texto-vertical fs-9">MECÁNICO</span></td>
            <td class="bg-green celda" style="padding-bottom: 32px; padding-top: 32px;"><span
                    class="texto-vertical fs-9">QUÍMICO</span></td>
            <td class="bg-green celda" style="padding-bottom: 32px; padding-top: 32px;"><span
                    class="texto-vertical fs-9">BIOLÓGICO</span></td>
            <td class="bg-green celda" style="padding-bottom: 32px; padding-top: 32px;"><span
                    class="texto-vertical fs-9">ERGONÓMICO</span></td>
            <td class="bg-green celda" style="padding-bottom: 32px; padding-top: 32px;"><span
                    class="texto-vertical fs-9">PSICOSOCIAL</span></td>
        </tr>

        @foreach ($ficha_preocupacional['antecedentes_empleos_anteriores'] as $antecedente)
            <tr>
                <td class="pa-12">{{ $antecedente['empresa'] }}</td>
                <td class="pa-12">{{ $antecedente['puesto_trabajo'] }}</td>
                <td class="pa-12">{{ $antecedente['actividades'] }}</td>
                <td class="pa-12">{{ $antecedente['tiempo_trabajo'] }}</td>

                <td class="pa-12">{{ in_array(1, $antecedente['tipos_riesgos_ids']) ? 'x' : '' }}</td>
                <td class="pa-12">{{ in_array(2, $antecedente['tipos_riesgos_ids']) ? 'x' : '' }}</td>
                <td class="pa-12">{{ in_array(3, $antecedente['tipos_riesgos_ids']) ? 'x' : '' }}</td>
                <td class="pa-12">{{ in_array(4, $antecedente['tipos_riesgos_ids']) ? 'x' : '' }}</td>
                <td class="pa-12">{{ in_array(5, $antecedente['tipos_riesgos_ids']) ? 'x' : '' }}</td>
                <td class="pa-12">{{ in_array(6, $antecedente['tipos_riesgos_ids']) ? 'x' : '' }}</td>

                <td class="pa-12">{{ $antecedente['observaciones'] }}</td>
            </tr>
        @endforeach
    </table>

    <table
        style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px;"
        border="1" cellpadding="0" cellspacing="0" class="celdas_amplias">
        <tr>
            <th style="width: 100%; text-align: left;">ACCIDENTES DE TRABAJO (DESCRIPCIÓN)</th>
        </tr>
        <tr>
            <td class="fs-9">
                <span class="mr-8 fs-9 d-inline-block">FUE CALIFICADO POR EL INSTITUTO DE SEGURIDAD SOCIAL
                    CORRESPONDIENTE:</span>
                SI @if ($ficha_preocupacional['accidente_trabajo'] && $ficha_preocupacional['accidente_trabajo']['calificado_iss'])
                    <span class="cuadrado fs-9">{{ 'x' }}</span>
                @else
                    <span class="cuadrado">&nbsp;&nbsp;</span>
                @endif
                <span class="mr-8 d-inline-block"></span>
                <span class="fs-9">ESPECIFICAR</span>
                {{ $ficha_preocupacional['accidente_trabajo'] ? $ficha_preocupacional['accidente_trabajo']['instituto_seguridad_social'] : '' }}
                <span class="mr-8 d-inline-block"></span>
                NO @if ($ficha_preocupacional['accidente_trabajo'] ? !$ficha_preocupacional['accidente_trabajo']['calificado_iss'] : '')
                    <span class="cuadrado fs-9">{{ 'x' }}</span>
                @else
                    <span class="cuadrado">&nbsp;&nbsp;</span>
                @endif
                <span class="mr-8 d-inline-block"></span>
                <span class="fs-9">FECHA:</span>
                <span
                    class="cuadrado fs-9">{{ $ficha_preocupacional['accidente_trabajo'] ? Carbon::parse($ficha_preocupacional['accidente_trabajo']['fecha'])->format('d') : '' }}</span><span
                    class="cuadrado fs-9">{{ $ficha_preocupacional['accidente_trabajo'] ? Carbon::parse($ficha_preocupacional['accidente_trabajo']['fecha'])->format('m') : '' }}</span><span
                    class="cuadrado fs-9">{{ $ficha_preocupacional['accidente_trabajo'] ? Carbon::parse($ficha_preocupacional['accidente_trabajo']['fecha'])->year : '' }}</span>
            </td>
        </tr>

        <tr>
            <td>
                <div class="mb-4 fs-10">Observaciones:</div>
                <div class="mb-4 fs-10">
                    {{ $ficha_preocupacional['accidente_trabajo'] ? $ficha_preocupacional['accidente_trabajo']['observacion'] : '' }}
                </div>
            </td>
        </tr>

        <tr>
            <td>{{ 'Detallar aquí en caso se presuma de algún accidente de trabajo que no haya sido reportado o calificado:' }}
            </td>
        </tr>
    </table>

    <table
        style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px;"
        border="1" cellpadding="0" cellspacing="0" class="mb-8 celdas_amplias">
        <tr>
            <th style="width: 100%; text-align: left;">{{ 'ENFERMEDADES PROFESIONALES' }}</th>
        </tr>
        <tr>
            <td class="fs-9">
                <span class="mr-8 fs-9 d-inline-block">FUE CALIFICADO POR EL INSTITUTO DE SEGURIDAD SOCIAL
                    CORRESPONDIENTE:</span>
                SI @if (
                    $ficha_preocupacional['enfermedad_profesional'] &&
                        $ficha_preocupacional['enfermedad_profesional']['calificado_iss']
                )
                    <span class="cuadrado fs-9">{{ 'x' }}</span>
                @else
                    <span class="cuadrado">&nbsp;&nbsp;</span>
                @endif
                <span class="mr-8 d-inline-block"></span>
                ESPECIFICAR:
                {{ $ficha_preocupacional['enfermedad_profesional'] ? $ficha_preocupacional['enfermedad_profesional']['instituto_seguridad_social'] : '' }}
                NO @if (
                    $ficha_preocupacional['enfermedad_profesional'] &&
                        !$ficha_preocupacional['enfermedad_profesional']['calificado_iss']
                )
                    <span class="cuadrado fs-9">{{ 'x' }}</span>
                @else
                    <span class="cuadrado">&nbsp;&nbsp;</span>
                @endif
                <span class="mr-8 d-inline-block"></span>
                <span class="fs-9">FECHA:</span>
                <span
                    class="cuadrado fs-9">{{ $ficha_preocupacional['enfermedad_profesional'] ? Carbon::parse($ficha_preocupacional['enfermedad_profesional']['fecha'])->format('d') : '' }}</span><span
                    class="cuadrado fs-9">{{ $ficha_preocupacional['enfermedad_profesional'] ? Carbon::parse($ficha_preocupacional['enfermedad_profesional']['fecha'])->format('m') : '' }}</span><span
                    class="cuadrado fs-9">{{ $ficha_preocupacional['enfermedad_profesional'] ? Carbon::parse($ficha_preocupacional['enfermedad_profesional']['fecha'])->year : '' }}</span>
            </td>
        </tr>

        <tr>
            <td>
                <div class="mb-4 fs-10">Observaciones:</div>
                <div class="mb-4 fs-10">
                    {{ $ficha_preocupacional['enfermedad_profesional'] ? $ficha_preocupacional['enfermedad_profesional']['observacion'] : '' }}
                </div>
            </td>
        </tr>

        <tr>
            <td>{{ 'Detallar aquí en caso se presuma de algún accidente de trabajo que no haya sido reportado o calificado:' }}
            </td>
        </tr>
    </table>

    {{-- E. ANTECEDENTES FAMILIARES (DETALLAR EL PARENTESCO)  --}}
    <div class="titulo-seccion">E. ANTECEDENTES FAMILIARES (DETALLAR EL PARENTESCO)</div>
    <div class="border mb-8">
        <table
            style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px;"
            border="1" cellpadding="0" cellspacing="0" class="celdas_amplias">
            <tr>
                <th class="pa-4">{{ '1. ENFERMEDAD CARDIO-VASCULAR' }}</th>
                @if (
                    $ficha_preocupacional['antecedentes_familiares']->first(
                        fn($ant) => $ant->tipo_antecedente_familiar_id == TipoAntecedenteFamiliar::ENFERMEDAD_CARDIO_VASCULAR))
                    <td style="width: 3%;" class="pa-4" align="center">{{ 'x' }}</td>
                @else
                    <td style="width: 3%;" class="pa-4">&nbsp;&nbsp;</td>
                @endif

                <th class="pa-4">{{ '2. ENFERMEDAD METABÓLICA' }}</th>
                @if (
                    $ficha_preocupacional['antecedentes_familiares']->first(
                        fn($ant) => $ant->tipo_antecedente_familiar_id == TipoAntecedenteFamiliar::ENFERMEDAD_METABOLICA))
                    <td style="width: 3%;" class="pa-4" align="center">{{ 'x' }}</td>
                @else
                    <td style="width: 3%;" class="pa-4">&nbsp;&nbsp;</td>
                @endif

                <th class="pa-4">{{ '3 ENFERMEDAD NEUROLÓGICA' }}</th>
                @if (
                    $ficha_preocupacional['antecedentes_familiares']->first(
                        fn($ant) => $ant->tipo_antecedente_familiar_id == TipoAntecedenteFamiliar::ENFERMEDAD_NEUROLOGICA))
                    <td style="width: 3%;" class="pa-4" align="center">{{ 'x' }}</td>
                @else
                    <td style="width: 3%;" class="pa-4">&nbsp;&nbsp;</td>
                @endif

                <th class="pa-4">{{ '4. ENFERMEDAD ONCOLÓGICA' }}</th>
                @if (
                    $ficha_preocupacional['antecedentes_familiares']->first(
                        fn($ant) => $ant->tipo_antecedente_familiar_id == TipoAntecedenteFamiliar::ONCOLOGICA))
                    <td style="width: 3%;" class="pa-4" align="center">{{ 'x' }}</td>
                @else
                    <td style="width: 3%;" class="pa-4">&nbsp;&nbsp;</td>
                @endif

                <th class="pa-4">{{ '5. ENFERMEDAD INFECCIOSA' }}</th>
                @if (
                    $ficha_preocupacional['antecedentes_familiares']->first(
                        fn($ant) => $ant->tipo_antecedente_familiar_id == TipoAntecedenteFamiliar::ENFERMEDAD_INFECIOSA))
                    <td style="width: 3%;" class="pa-4" align="center">{{ 'x' }}</td>
                @else
                    <td style="width: 3%;" class="pa-4">&nbsp;&nbsp;</td>
                @endif

                <th class="pa-4">{{ '6.  ENFERMEDAD HEREDITARIA / CONGÉNITA' }}</th>
                @if (
                    $ficha_preocupacional['antecedentes_familiares']->first(
                        fn($ant) => $ant->tipo_antecedente_familiar_id == TipoAntecedenteFamiliar::ENFERMEDAD_HEREDITARIA_CONGENITA))
                    <td style="width: 3%;" class="pa-4" align="center">{{ 'x' }}</td>
                @else
                    <td style="width: 3%;" class="pa-4">&nbsp;&nbsp;</td>
                @endif

                <th class="pa-4">{{ '7. DISCAPACIDADES' }}</th>
                @if (
                    $ficha_preocupacional['antecedentes_familiares']->first(
                        fn($ant) => $ant->tipo_antecedente_familiar_id == TipoAntecedenteFamiliar::DISCAPACIDADES))
                    <td style="width: 3%;" class="pa-4" align="center">{{ 'x' }}</td>
                @else
                    <td style="width: 3%;" class="pa-4">&nbsp;&nbsp;</td>
                @endif
            </tr>
        </table>

        <table
            style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px;"
            border="1" cellpadding="0" cellspacing="0" class="mb-8">
            @foreach ($ficha_preocupacional['antecedentes_familiares'] as $antecedente)
                <tr>
                    <td class="px-8 fs-10" style="width: 88%;">
                        {{ $antecedente['parentesco'] . ': ' . $antecedente['descripcion'] }}</td>
                </tr>
            @endforeach
        </table>
    </div>

    {{-- G. ACTIVIDADES EXTRA LABORALES --}}
    <div class="titulo-seccion">G. ACTIVIDADES EXTRA LABORALES</div>
    <table
        style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px;"
        border="1" cellpadding="0" cellspacing="0" class="mb-8 celdas_amplias">
        <tr>
            <td>{{ $ficha_preocupacional['actividades_extralaborales'] }}</td>
        </tr>
    </table>

    {{-- H. ENFERMEDAD ACTUAL --}}
    <div class="titulo-seccion">H. ENFERMEDAD ACTUAL</div>
    <table
        style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px;"
        border="1" cellpadding="0" cellspacing="0" class="celdas_amplias mb-8">
        <tr>
            <td>{{ $ficha_preocupacional['enfermedad_actual'] }}</td>
        </tr>
    </table>

    {{-- I. REVISIÓN ACTUAL DE ÓRGANOS Y SISTEMAS  --}}
    <div class="titulo-seccion">I. REVISIÓN ACTUAL DE ÓRGANOS Y SISTEMAS</div>
    <div class="border mb-8">
        <table
            style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px;"
            border="1" cellpadding="0" cellspacing="0" class="celdas_amplias">
            <tr>
                <td class="bg-celeste pa-4">{{ '1. PIEL - ANEXOS' }}</td>
                @if (
                    $ficha_preocupacional['revisiones_actuales_organos_sistemas']->first(
                        fn($ant) => $ant['organo_id'] == SistemaOrganico::PIEL_ANEXOS))
                    <td style="width: 3%;" class="pa-4" align="center">{{ 'x' }}</td>
                @else
                    <td style="width: 3%;" class="pa-4">&nbsp;&nbsp;</td>
                @endif

                <td class="bg-celeste pa-4">{{ '3. RESPIRATORIO' }}</td>
                @if (
                    $ficha_preocupacional['revisiones_actuales_organos_sistemas']->first(
                        fn($ant) => $ant['organo_id'] == SistemaOrganico::DIGESTIVO))
                    <td style="width: 3%;" class="pa-4" align="center">{{ 'x' }}</td>
                @else
                    <td style="width: 3%;" class="pa-4">&nbsp;&nbsp;</td>
                @endif

                <td class="bg-celeste pa-4">{{ '5. DIGESTIVO' }}</td>
                @if (
                    $ficha_preocupacional['revisiones_actuales_organos_sistemas']->first(
                        fn($ant) => $ant['organo_id'] == SistemaOrganico::HEMOLINFATICO))
                    <td style="width: 3%;" class="pa-4" align="center">{{ 'x' }}</td>
                @else
                    <td style="width: 3%;" class="pa-4">&nbsp;&nbsp;</td>
                @endif

                <td class="bg-celeste pa-4">{{ '7. MÚSCULO ESQUELÉTICO' }}</td>
                @if (
                    $ficha_preocupacional['revisiones_actuales_organos_sistemas']->first(
                        fn($ant) => $ant['organo_id'] == SistemaOrganico::CARDIOVASCULAR))
                    <td style="width: 3%;" class="pa-4" align="center">{{ 'x' }}</td>
                @else
                    <td style="width: 3%;" class="pa-4">&nbsp;&nbsp;</td>
                @endif

                <td class="bg-celeste pa-4">{{ '9. HEMO LINFÁTICO' }}</td>
                @if (
                    $ficha_preocupacional['revisiones_actuales_organos_sistemas']->first(
                        fn($ant) => $ant['organo_id'] == SistemaOrganico::ENDOCRINO))
                    <td style="width: 3%;" class="pa-4" align="center">{{ 'x' }}</td>
                @else
                    <td style="width: 3%;" class="pa-4">&nbsp;&nbsp;</td>
                @endif
            </tr>

            <tr>
                <td class="bg-celeste pa-4">{{ '2. ÓRGANOS DE LOS SENTIDOS' }}</td>
                @if (
                    $ficha_preocupacional['revisiones_actuales_organos_sistemas']->first(
                        fn($ant) => $ant['organo_id'] == SistemaOrganico::RESPIRATORIO))
                    <td style="width: 3%;" class="pa-4" align="center">{{ 'x' }}</td>
                @else
                    <td style="width: 3%;" class="pa-4">&nbsp;&nbsp;</td>
                @endif

                <td class="bg-celeste pa-4">{{ '4. CARDIO-VASCULAR' }}</td>
                @if (
                    $ficha_preocupacional['revisiones_actuales_organos_sistemas']->first(
                        fn($ant) => $ant['organo_id'] == SistemaOrganico::MUSCULO_ESQUELETICO))
                    <td style="width: 3%;" class="pa-4" align="center">{{ 'x' }}</td>
                @else
                    <td style="width: 3%;" class="pa-4">&nbsp;&nbsp;</td>
                @endif

                <td class="bg-celeste pa-4">{{ '6. GENITO - URINARIO' }}</td>
                @if (
                    $ficha_preocupacional['revisiones_actuales_organos_sistemas']->first(
                        fn($ant) => $ant['organo_id'] == SistemaOrganico::ORGANOS_DE_LOS_SENTIDOS))
                    <td style="width: 3%;" class="pa-4" align="center">{{ 'x' }}</td>
                @else
                    <td style="width: 3%;" class="pa-4">&nbsp;&nbsp;</td>
                @endif

                <td class="bg-celeste pa-4">{{ '8. ENDOCRINO' }}</td>
                @if (
                    $ficha_preocupacional['revisiones_actuales_organos_sistemas']->first(
                        fn($ant) => $ant['organo_id'] == SistemaOrganico::GENITO_URINARIO))
                    <td style="width: 3%;" class="pa-4" align="center">{{ 'x' }}</td>
                @else
                    <td style="width: 3%;" class="pa-4">&nbsp;&nbsp;</td>
                @endif

                <td class="bg-celeste pa-4">{{ '10. NERVIOSO' }}</td>
                @if (
                    $ficha_preocupacional['revisiones_actuales_organos_sistemas']->first(
                        fn($ant) => $ant['organo_id'] == SistemaOrganico::NERVIOSO))
                    <td style="width: 3%;" class="pa-4" align="center">{{ 'x' }}</td>
                @else
                    <td style="width: 3%;" class="pa-4">&nbsp;&nbsp;</td>
                @endif
            </tr>
        </table>

        <table
            style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px;"
            border="1" cellpadding="0" cellspacing="0" class="celdas_amplias mb-8">
            @foreach ($ficha_preocupacional['revisiones_actuales_organos_sistemas'] as $revision)
                <tr>
                    <td class="px-8 fs-10" style="width: 88%;">
                        {{ $revision['organo_id'] . ': ' . $revision['descripcion'] }}</td>
                </tr>
            @endforeach
        </table>
    </div>

    {{-- J. CONSTANTES VITALES Y ANTROPOMETRÍA --}}
    <div class="titulo-seccion">J. CONSTANTES VITALES Y ANTROPOMETRÍA</div>
    <table
        style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px;"
        border="1" cellpadding="0" cellspacing="0" class="celdas_amplias mb-8">
        <tr>
            <th style="width: 15%;" class="fs-10">PRESIÓN ARTERIAL(mmHg)</th>
            <th style="width: 10%;" class="fs-10">TEMPERATURA(°C)</th>
            <th style="width: 15%;" class="fs-10">FRECUENCIA CARDIACA(l/min)</th>
            <th style="width: 12%;" class="fs-10">SATURACIÓN DE OXÍGENO(%)</th>
            <th style="width: 12%;" class="fs-10">FRECUENCIA RESPIRATORIA(fr/min)</th>
            <th style="width: 8%;" class="fs-10">PESO(Kg)</th>
            <th style="width: 8%;" class="fs-10">TALLA(cm)</th>
            <th style="width: 10%;" class="fs-10">ÍNDICE DE MASA CORPORAL(kg/m2)</th>
            <th style="width: 10%;" class="fs-10">PERÍMETRO ABDOMINAL(cm)</th>
        </tr>
        <tr>
            <td>{{ $ficha_preocupacional['constante_vital']['presion_arterial'] }}</td>
            <td>{{ $ficha_preocupacional['constante_vital']['temperatura'] }}</td>
            <td>{{ $ficha_preocupacional['constante_vital']['frecuencia_cardiaca'] }}</td>
            <td>{{ $ficha_preocupacional['constante_vital']['saturacion_oxigeno'] }}</td>
            <td>{{ $ficha_preocupacional['constante_vital']['frecuencia_respiratoria'] }}</td>
            <td>{{ $ficha_preocupacional['constante_vital']['peso'] }}</td>
            <td>{{ $ficha_preocupacional['constante_vital']['talla'] }}</td>
            <td>{{ $ficha_preocupacional['constante_vital']['indice_masa_corporal'] }}</td>
            <td>{{ $ficha_preocupacional['constante_vital']['perimetro_abdominal'] }}</td>
        </tr>
    </table>

    {{-- K. EXAMEN FÍSICO REGIONAL --}}
    <div class="titulo-seccion">K. EXAMEN FÍSICO REGIONAL</div>
    <div class="subtitulo-seccion">REGIONES</div>
    <table
        style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:8px;"
        border="1" cellpadding="0" cellspacing="0" class="mb-8 celdas_amplias">
        <tbody>
            <tr>
                <td rowspan="3" class="celda bg-celeste"><span class="texto-vertical fs-9">1. Piel</span></td>
                <td class="bg-celeste" style="width: 12%;">a. Cicatrices</td>
                <td>{{ in_array(CategoriaExamenFisico::CICATRICES, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td rowspan="3" class="celda bg-celeste"><span class="texto-vertical fs-9">3. Oido</span></td>
                <td class="bg-celeste" style="width: 12%;">a. C. auditivo externo</td>
                <td>{{ in_array(CategoriaExamenFisico::AUDITIVO_EXTERNO, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td rowspan="4" class="celda bg-celeste"><span class="texto-vertical fs-9">5. Nariz</span></td>
                <td class="bg-celeste" style="width: 12%;">a. Tabique</td>
                <td>{{ in_array(CategoriaExamenFisico::TABIQUE, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td rowspan="2" class="celda bg-celeste"><span class="texto-vertical fs-9">8. Tórax</span></td>
                <td class="bg-celeste" style="width: 12%;">a. Pulmones</td>
                <td>{{ in_array(CategoriaExamenFisico::PULMONES, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td rowspan="2" class="celda bg-celeste"><span class="texto-vertical fs-9">11. Pelvis</span></td>
                <td class="bg-celeste" style="width: 12%;">a. Pelvis</td>
                <td>{{ in_array(CategoriaExamenFisico::PELVIS, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
            </tr>
            <tr>
                <td class="bg-celeste">b. Tatuajes</td>
                <td>{{ in_array(CategoriaExamenFisico::TATUAJES, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td class="bg-celeste">b. Pabellón</td>
                <td>{{ in_array(CategoriaExamenFisico::PABELLON, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td class="bg-celeste">b. Cornetes</td>
                <td>{{ in_array(CategoriaExamenFisico::CORNETES, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td class="bg-celeste">b. Parrilla costal</td>
                <td>{{ in_array(CategoriaExamenFisico::PARRILLA_COSTAL, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td class="bg-celeste">b. Genitales</td>
                <td>{{ in_array(CategoriaExamenFisico::GENITALES, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
            </tr>

            <tr>
                <td class="bg-celeste">c. Piel y faneras</td>
                <td>{{ in_array(CategoriaExamenFisico::PIEL_FANERAS, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td class="bg-celeste">c. Tímpanos</td>
                <td>{{ in_array(CategoriaExamenFisico::TIMPANOS, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td class="bg-celeste">c. Mucosas</td>
                <td>{{ in_array(CategoriaExamenFisico::MUCOSAS, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td rowspan="3" class="celda bg-celeste"><span class="texto-vertical fs-9">9. Abdomen</span></td>
                <td class="bg-celeste">a. Vísceras</td>
                <td>{{ in_array(CategoriaExamenFisico::VISCERAS, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td rowspan="3" class="celda bg-celeste"><span class="texto-vertical fs-9">12. Extremidades</span></td>
                <td class="bg-celeste">a. Vascular</td>
                <td>{{ in_array(CategoriaExamenFisico::VASCULAR, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
            </tr>

            <tr>
                <td rowspan="5" class="celda bg-celeste"><span class="texto-vertical fs-9">2. Ojos</span></td>
                <td class="bg-celeste">a. Párpados</td>
                <td>{{ in_array(CategoriaExamenFisico::PARPADOS, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td rowspan="5" class="celda bg-celeste"><span class="texto-vertical fs-9">4. Oro faringe</span></td>
                <td class="bg-celeste">a. Labios</td>
                <td>{{ in_array(CategoriaExamenFisico::LABIOS, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td class="bg-celeste">d. Senos paranasales</td>
                <td>{{ in_array(CategoriaExamenFisico::SENOS_PARANASALES, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td class="bg-celeste">b. Pared abdominal</td>
                <td>{{ in_array(CategoriaExamenFisico::PARED_ABDOMINAL, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td class="bg-celeste">b. Miembros superiores</td>
                <td>{{ in_array(CategoriaExamenFisico::MIEMBROS_SUPERIORES, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
            </tr>

            <tr>
                <td class="bg-celeste">b. Conjuntivas</td>
                <td>{{ in_array(CategoriaExamenFisico::CONJUNTIVAS, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td class="bg-celeste">b. Lengua</td>
                <td>{{ in_array(CategoriaExamenFisico::LENGUA, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td rowspan="2" class="celda bg-celeste"><span class="texto-vertical fs-9">6. Cuello</span></td>
                <td class="bg-celeste">a. Tiroides / masas</td>
                <td>{{ in_array(CategoriaExamenFisico::TIROIDES_MASAS, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td class="bg-celeste">c. Flexibilidad</td>
                <td>{{ in_array(CategoriaExamenFisico::FLEXIBILIDAD, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td class="bg-celeste">c. Miembros inferiores</td>
                <td>{{ in_array(CategoriaExamenFisico::MIEMBROS_INFERIORES, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
            </tr>

            <tr>
                <td class="bg-celeste">c. Pupilas</td>
                <td>{{ in_array(CategoriaExamenFisico::PUPILAS, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td class="bg-celeste">c. Faringe</td>
                <td>{{ in_array(CategoriaExamenFisico::FARINGE, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td class="bg-celeste">b. Movilidad</td>
                <td>{{ in_array(CategoriaExamenFisico::MOVILIDAD, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td rowspan="3" class="celda bg-celeste"><span class="texto-vertical fs-9">10. Columna</span></td>
                <td class="bg-celeste" rowspan="2">a. Desviación</td>
                <td rowspan="2">
                    {{ in_array(CategoriaExamenFisico::DESVIACION, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td rowspan="4" class="celda bg-celeste"><span class="texto-vertical fs-9">13. Neurológico</span></td>
                <td class="bg-celeste">a. Fuerza</td>
                <td>{{ in_array(CategoriaExamenFisico::FUERZA, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
            </tr>

            <tr>
                <td class="bg-celeste">d. Córnea</td>
                <td>{{ in_array(CategoriaExamenFisico::CORNEA, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td class="bg-celeste">d. Amígdalas</td>
                <td>{{ in_array(CategoriaExamenFisico::AMIGDALAS, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td rowspan="2" class="celda bg-celeste"><span class="texto-vertical fs-9">7. Tórax</span></td>
                <td class="bg-celeste">a. Mamas</td>
                <td>{{ in_array(CategoriaExamenFisico::MAMAS, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td class="bg-celeste">b. Sensibilidad</td>
                <td>{{ in_array(CategoriaExamenFisico::SENSIBILIDAD, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
            </tr>

            <tr>
                <td class="bg-celeste">e. Motilidad</td>
                <td>{{ in_array(CategoriaExamenFisico::MOTILIDAD, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td class="bg-celeste">e. Dentadura</td>
                <td>{{ in_array(CategoriaExamenFisico::DENTADURA, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td class="bg-celeste">b. Corazón</td>
                <td>{{ in_array(CategoriaExamenFisico::CORAZON, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td class="bg-celeste">b. Dolor</td>
                <td>{{ in_array(CategoriaExamenFisico::DOLOR, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td class="bg-celeste">c. Marcha</td>
                <td>{{ in_array(CategoriaExamenFisico::MARCHA, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
            </tr>

            <tr>
                <td colspan="12" class="fs-9">
                    {{ 'CON EVIDENCIA DE PATOLOGÍA MARCAR CON "X" Y DESCRIBIR EN LA SIGUIENTE SECCIÓN ANOTANDO EL NUMERAL' }}
                </td>
                <td class="bg-celeste">d. Reflejos</td>
                <td>{{ in_array(CategoriaExamenFisico::REFLEJOS, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
            </tr>

            <tr>
                <td colspan="15">
                    <div class="mb-4"><b class="fs-10">Observaciones:</b></div>
                    <div class="mb-4 fs-9">
                        {{-- {{ json_encode($ficha_preocupacional['observaciones_examen_fisico_regional']) }} --}}

                        @foreach ($ficha_preocupacional['observaciones_examen_fisico_regional'] as $item)
                            <div class="fs-9">{{ $item['categoria'] . ': ' . $item['observacion'] }}</div>
                        @endforeach
                    </div>
                </td>
            </tr>
        </tbody>
    </table>

    {{-- L. RESULTADOS DE EXÁMENES GENERALES Y ESPECÍFICOS DE ACUERDO AL RIESGO Y PUESTO DE TRABAJO (IMAGEN, LABORATORIO Y OTROS) --}}
    <div class="titulo-seccion">L. RESULTADOS DE EXÁMENES GENERALES Y ESPECÍFICOS DE ACUERDO AL RIESGO Y PUESTO DE
        TRABAJO (IMAGEN, LABORATORIO Y OTROS)</div>
    <table
        style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px;"
        border="1" cellpadding="0" cellspacing="0" class="celdas_amplias mb-8">
        <tr>
            <th style="width: 20%;">EXAMEN</th>
            <th style="width: 15%;">FECHA <br> <small class="text-mini">{{ 'aaa/mm/dd' }}</small></th>
            <th style="width: 65%;">RESULTADOS</th>
        </tr>

        @foreach ($ficha_preocupacional['resultados_examenes'] as $resultado_examen)
            <tr>
                <td>{{ $resultado_examen['examen'] }}</td>
                <td>{{ $resultado_examen['fecha_asistencia'] }}</td>
                <td>
                    <table
                        style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif;"
                        border="0">
                        <tr>
                            <th style="border: none; width: 25%; background: #fff;" align="left">
                                {{ 'Examen' }}</th>
                            <th style="border: none; width: 25%; background: #fff;" align="left">
                                {{ 'Resultado' }}</th>
                            <th style="border: none; width: 50%; background: #fff;" align="left">
                                {{ 'Observación' }}</th>
                        </tr>
                        @foreach ($resultado_examen['resultados'] as $resultado)
                            <tr>
                                <td style=" border: none;">{{ $resultado['configuracion_examen_campo'] }}
                                </td>
                                <td style="border: none;">
                                    {{ $resultado['resultado'] . ' ' . $resultado['unidad_medida'] }}
                                </td>
                                <td style="border: none;">
                                    {{ $resultado['observaciones'] }}
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </td>
            </tr>
        @endforeach
    </table>

    {{-- M. DIAGNÓSTICO --}}
    <table
        style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px;"
        border="1" cellpadding="0" cellspacing="0" class="celdas_amplias mb-8">
        <tr>
            <th colspan="2" class=" bg-titulo" style="width: 60%; text-align: left;">
                <span class="fs-10">
                    M. DIAGNÓSTICO
                </span>
                <span class="fs-10">
                    PRE = PRESUNTIVO
                    DEF = DEFINITIVO
                </span>
            </th>
            <th class="fs-10 bg-titulo" style="width: 30%;">CIE</th>
            <th class="fs-10 bg-titulo" style="width: 5%;">PRE</th>
            <th class="fs-10 bg-titulo" style="width: 5%;">DEF</th>
        </tr>

        @if (count($ficha_preocupacional['consultas_medicas']) === 0)
            <tr>
                <td style="width: 4%;" class="bg-green">{{ '1' }}</td>
                <td style="width: 55%;">{{ '' }}</td>
                <td style="width: 25%;">{{ '' }}</td>
                <td style="width: 8%;">{{ '' }}</td>
                <td style="width: 8%;">{{ '' }}</td>
            </tr>
        @else
            @foreach ($ficha_preocupacional['consultas_medicas'][0]['diagnosticos'] as $diagnostico)
                <tr>
                    <td style="width: 4%;" class="bg-green">{{ $loop->index + 1 }}</td>
                    <td style="width: 55%;">{{ $diagnostico['recomendacion'] }}</td>
                    <td style="width: 25%;">{{ $diagnostico['cie'] }}</td>
                    <td style="width: 8%;">{{ $diagnostico['pre'] }}</td>
                    <td style="width: 8%;">{{ $diagnostico['def'] }}</td>
                </tr>
            @endforeach
        @endif
    </table>

    {{-- N. APTITUD MÉDICA LABORAL --}}
    <div class="titulo-seccion">N. APTITUD MÉDICA PARA EL TRABAJO</div>
    <div class="border mb-8">
        <table
            style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px;"
            border="1" cellpadding="0" cellspacing="0">
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

        <table
            style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px;"
            border="1" cellpadding="0" cellspacing="0" class="mb-8">
            <tr>
                <td class="bg-celeste px-8 fs-10" style="width: 12%;">{{ 'Observación' }}</td>
                <td class="px-8 fs-10" style="width: 88%;">
                    {{ $ficha_preocupacional['aptitud_medica']['observacion'] }}</td>
            </tr>
            <tr>
                <td class="bg-celeste px-8 fs-10">{{ 'Limitación' }}</td>
                <td class="px-8 fs-10">
                    {{ $ficha_preocupacional['aptitud_medica']['limitacion'] }}</td>
            </tr>
        </table>
    </div>

    {{-- O. RECOMENDACIONES Y/O TRATAMIENTO --}}
    <div class="titulo-seccion">O. RECOMENDACIONES Y/O TRATAMIENTO</div>
    <table
        style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px;"
        border="1" cellpadding="0" cellspacing="0" class="celdas_amplias mb-8">
        <tr>
            <td>{{ $ficha_preocupacional['recomendaciones_tratamiento'] }}</td>
        </tr>
    </table>

    <p class="fs-10 mb-16">
        {{ 'CERTIFICO QUE LO ANTERIORMENTE EXPRESADO EN RELACIÓN A MI ESTADO DE SALUD ES VERDAD. SE ME HA INFORMADO LAS MEDIDAS PREVENTIVAS A TOMAR PARA DISMINUIR O MITIGAR LOS RIESGOS RELACIONADOS CON MI ACTIVIDAD LABORAL.' }}
    </p>

    <br>

    {{-- P. DATOS DEL PROFESIONAL DE SALUD --}}
    <span style="width: 77%; display: inline-block;" class="border mr-8">
        <div class="titulo-seccion">P. DATOS DEL PROFESIONAL DE SALUD</div>
        <table style="table-layout:fixed; width: 100%;" border="1" cellpadding="0" cellspacing="0"
            class="celdas_amplias">
            <tr>
                <td class="bg-green fs-10" style="width: 8%">FECHA<br> <small
                        class="text-mini">{{ 'aaa/mm/dd' }}</small></td>
                <td style="width: 11%">{{ $fecha_creacion }}</td>
                <td class="bg-green fs-10" style="width: 7%">HORA</td>
                <td style="width: 9%">{{ $hora_creacion }}</td>
                <td class="bg-green fs-10" style="width: 12%;" align="center">NOMBRE Y APELLIDO</td>
                <td style="width: 14%" class="fs-10">
                    {{ $profesionalSalud?->empleado->nombres . ' ' . $profesionalSalud?->empleado->apellidos }}</td>
                <td class="bg-green fs-10" style="width: 9%;">CÓDIGO</td>
                <td style="width: 6%">{{ $profesionalSalud?->codigo }}</td>
                <td class="bg-green fs-10" style="width: 8%;">FIRMA Y SELLO</td>
                <td style="width: 10%">
                    @isset($firmaProfesionalMedico)
                        <img src="{{ $firmaProfesionalMedico }}" alt="" width="100%" height="40">
                    @endisset
                    @empty($firmaProfesionalMedico)
                        <div class="pa-12">
                            &nbsp;<br />
                        </div>
                    @endempty
                </td>
            </tr>
        </table>
    </span>

    {{-- Q. FIRMA DEL USUARIO --}}
    <span style="width: 20%; display: inline-block;" class="border">
        <div class="titulo-seccion">Q. FIRMA DEL USUARIO</div>
        <div style="padding: 16px;">
            &nbsp;
            <br />
        </div>
    </span>
</body>

</html>
