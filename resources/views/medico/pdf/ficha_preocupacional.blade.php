<html>
@php
    $fecha = new Datetime();

    // $firma_profesional_salud = 'data:image/png;base64,' . base64_encode(file_get_contents(substr($profesionalSalud->firma_url, 1)));
    use App\Models\Medico\CategoriaExamenFisico;
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
            padding: 4px;
            word-wrap: break-word;
            border: 1px solid #000;
            font-size: 10px;
        }

        .celdas_amplias td,
        th {
            padding: 8px;
            word-wrap: break-word;
            border: 1px solid #000;
            font-size: 10px;
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
            font-size: 10px;
        }

        .celda {
            width: 5px !important;
            background: #ccffff;
            position: relative;
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
            <td align="center">{{ '' }}</td>
        </tr>
    </table>

    <table
        style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px;"
        border="1" cellpadding="0" cellspacing="0">
        <tr>
            <th style="width: 10%;">PRIMER APELLIDO</th>
            <th style="width: 10%;">SEGUNDO APELLIDO</th>
            <th style="width: 10%;">PRIMER NOMBRE</th>
            <th style="width: 10%;">SEGUNDO NOMBRE</th>
            <th style="width: 8%;">SEXO</th>
            <th style="width: 10%;">FECHA DE INICIO DE LABORES</th>
            <th style="width: 10%;">FECHA DE SALIDA</th>
            <th style="width: 10%;">Tiempo <small>(meses)</small></th>
            <th style="width: 22%;">PUESTO DE TRABAJO (CIUO)</th>
        </tr>
        <tr>
            <td align="center">{{ explode(' ', $empleado['apellidos'])[0] }}</td>
            <td align="center">{{ explode(' ', $empleado['apellidos'])[1] }}</td>
            <td align="center">{{ explode(' ', $empleado['nombres'])[0] }}</td>
            <td align="center">{{ explode(' ', $empleado['nombres'])[1] }}</td>
            <td align="center">{{ $empleado['genero'] }}</td>
            <td align="center">{{ $empleado['fecha_ingreso'] }}</td>
            <td align="center">{{ $empleado['fecha_salida'] }}</td>
            <td align="center">{{ $empleado['antiguedad'] }}</td>
            <td align="center">{{ $empleado['cargo']->nombre }}</td>
        </tr>
    </table>

    {{-- B. MOTIVO DE LA CONSULTA --}}
    <div class="titulo-seccion">B. MOTIVO DE LA CONSULTA</div>
    <table
        style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px;"
        border="1" cellpadding="0" cellspacing="0">
        <tr>
            <td>{{ $ficha_preocupacional['motivo_consulta'] }}</td>
        </tr>
    </table>

    {{-- C. ANTECEDENTES PERSONALES --}}
    <div class="titulo-seccion">C. ANTECEDENTES PERSONALES</div>
    <table
        style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px;"
        border="1" cellpadding="0" cellspacing="0">
        <tr>
            <th align="left" style="width: 100%;">ANTECEDENTES CLÍNICOS Y QUIRÚRGICOS</th>
        </tr>
        <tr>
            <td>{{ $ficha_preocupacional['antecedente_personal']['antecedentes_quirurgicos'] }}</td>
        </tr>
    </table>

    <table
        style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif;"
        border="1" cellpadding="0" cellspacing="0" >
        <tr>
            <th align="left" style="width: 10%;" colspan="14">ANTECEDENTES GINECO OBSTÉTRICOS</th>
        </tr>

        <tr>
            <td style="width: 10%;" class="bg-celeste fs-8" rowspan="2" colspan="1">{{ 'MENARQUÍA' }}</td>
            <td style="width: 7%;" class="bg-celeste fs-8" rowspan="2" colspan="1">{{ 'CICLOS' }}</td>
            <td style="width: 12%;" class="bg-celeste fs-8" rowspan="2" colspan="1" align="center">{{ 'FECHA DE ÚLTIMA MENSTRUACIÓN' }}</td>
            <td style="width: 8%;" class="bg-celeste fs-8" rowspan="2" colspan="1">{{ 'GESTAS' }}</td>
            <td style="width: 8%;" class="bg-celeste fs-8" rowspan="2" colspan="1">{{ 'PARTOS' }}</td>
            <td style="width: 10%;" class="bg-celeste fs-8" rowspan="2" colspan="1">{{ 'CESÁREAS' }}</td>
            <td style="width: 9%;" class="bg-celeste fs-8" rowspan="2" colspan="1">{{ 'ABORTOS' }}</td>
            <td class="bg-celeste fs-8" rowspan="1" colspan="2" align="center">{{ 'HIJOS' }}</td>
            <td class="bg-celeste fs-8" rowspan="1" colspan="2" align="center">{{ 'VIDA SEXUAL ACTIVA' }}</td>
            <td style="width: 10%;" class="bg-celeste fs-8" rowspan="1" colspan="3">{{ 'MÉTODO DE PLANIFICACIÓN FAMILIAR' }}</td>
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
            <td>{{ isset($ficha_preocupacional['antecedentes_gineco_obstetricos']['menarquia']) ? $ficha_preocupacional['antecedentes_gineco_obstetricos']['menarquia'] : ''}}</td>
            <td>{{ isset($ficha_preocupacional['antecedentes_gineco_obstetricos']['menarquia']) ? $ficha_preocupacional['antecedentes_gineco_obstetricos']['ciclos'] : ''}}</td>
            <td>{{ isset($ficha_preocupacional['antecedentes_gineco_obstetricos']['menarquia']) ? $ficha_preocupacional['antecedentes_gineco_obstetricos']['fecha_ultima_menstruacion'] : ''}}</td>
            <td>{{ isset($ficha_preocupacional['antecedentes_gineco_obstetricos']['menarquia']) ? $ficha_preocupacional['antecedentes_gineco_obstetricos']['gestas'] : ''}}</td>
            <td>{{ isset($ficha_preocupacional['antecedentes_gineco_obstetricos']['menarquia']) ? $ficha_preocupacional['antecedentes_gineco_obstetricos']['partos'] : ''}}</td>
            <td>{{ isset($ficha_preocupacional['antecedentes_gineco_obstetricos']['menarquia']) ? $ficha_preocupacional['antecedentes_gineco_obstetricos']['cesareas'] : ''}}</td>
            <td>{{ isset($ficha_preocupacional['antecedentes_gineco_obstetricos']['menarquia']) ? $ficha_preocupacional['antecedentes_gineco_obstetricos']['abortos'] : ''}}</td>
            <td>{{ isset($ficha_preocupacional['antecedente_personal']['hijos_vivos']) ? $ficha_preocupacional['antecedente_personal']['hijos_vivos'] : ''}}</td>
            <td>{{ isset($ficha_preocupacional['antecedente_personal']['hijos_muertos']) ? $ficha_preocupacional['antecedente_personal']['hijos_muertos'] : ''}}</td>
            <td>{{ isset($ficha_preocupacional['antecedente_personal']['vida_sexual_activa']) && $ficha_preocupacional['antecedente_personal']['vida_sexual_activa'] ? 'x' : ''}}</td>
            <td>{{ isset($ficha_preocupacional['antecedente_personal']['vida_sexual_activa']) && !$ficha_preocupacional['antecedente_personal']['vida_sexual_activa'] ? 'x' : ''}}</td>
            <td>{{ isset($ficha_preocupacional['antecedente_personal']['tiene_metodo_planificacion_familiar']) && $ficha_preocupacional['antecedente_personal']['tiene_metodo_planificacion_familiar'] ? 'x' : ''}}</td>
            <td>{{ isset($ficha_preocupacional['antecedente_personal']['tiene_metodo_planificacion_familiar']) && !$ficha_preocupacional['antecedente_personal']['tiene_metodo_planificacion_familiar'] ? 'x' : ''}}</td>
            <td>{{ isset($ficha_preocupacional['antecedente_personal']['tipo_metodo_planificacion_familiar']) ? $ficha_preocupacional['antecedente_personal']['tipo_metodo_planificacion_familiar'] : ''}}</td>
        </tr>
    </table>

    <table
        style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px;"
        border="1" cellpadding="0" cellspacing="0">
        <tr>
            <th align="left" style="width: 100%;" colspan="10">ANTECEDENTES GINECO OBSTÉTRICOS</th>
        </tr>
        <tr>
            <td class="bg-celeste" style="width: 20%;">{{ 'EXÁMENES REALIZADOS' }}</td>
            <td class="bg-celeste" style="width: 4%;">{{ 'SI' }}</td>
            <td class="bg-celeste" style="width: 4%;">{{ 'NO' }}</td>
            <td class="bg-celeste" style="width: 11%;">{{ 'TIEMPO(años)' }}</td>
            <td class="bg-celeste" style="width: 11%;">{{ 'RESULTADO' }}</td>
            <td class="bg-celeste" style="width: 20%;">{{ 'EXÁMENES REALIZADOS' }}</td>
            <td class="bg-celeste" style="width: 4%;">{{ 'SI' }}</td>
            <td class="bg-celeste" style="width: 4%;">{{ 'NO' }}</td>
            <td class="bg-celeste" style="width: 11%;">{{ 'TIEMPO(años)' }}</td>
            <td class="bg-celeste" style="width: 11%;">{{ 'RESULTADO' }}</td>
        </tr>
        <tr>
            {{-- Papanicolaou --}}
            <td>{{ 1 <= count($ficha_preocupacional['examenes_realizados']) && $ficha_preocupacional['examenes_realizados'][0]['tipo'] == 'F' ? $ficha_preocupacional['examenes_realizados'][0]['examen'] : '' }}
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
            <td>{{ 3 <= count($ficha_preocupacional['examenes_realizados']) && $ficha_preocupacional['examenes_realizados'][0]['tipo'] == 'F' ? $ficha_preocupacional['examenes_realizados'][2]['examen'] : '' }}
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
            <td>{{ 2 <= count($ficha_preocupacional['examenes_realizados']) && $ficha_preocupacional['examenes_realizados'][0]['tipo'] == 'F' ? $ficha_preocupacional['examenes_realizados'][1]['examen'] : '' }}
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
            <td>{{ 4 <= count($ficha_preocupacional['examenes_realizados']) && $ficha_preocupacional['examenes_realizados'][0]['tipo'] == 'F' ? $ficha_preocupacional['examenes_realizados'][3]['examen'] : '' }}
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

    {{-- AQUI --}}
    <table
        style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px;"
        border="1" cellpadding="0" cellspacing="0">
        <tr>
            <th align="left" style="width: 100%;" colspan="10">ANTECEDENTES REPRODUCTIVOS MASCULINOS</th>
        </tr>
        <tr>
            <td class="bg-celeste" style="width: 20%;">{{ 'EXÁMENES REALIZADOS' }}</td>
            <td class="bg-celeste" style="width: 4%;">{{ 'SI' }}</td>
            <td class="bg-celeste" style="width: 4%;">{{ 'NO' }}</td>
            <td class="bg-celeste" style="width: 11%;">{{ 'TIEMPO(años)' }}</td>
            <td class="bg-celeste" style="width: 11%;">{{ 'RESULTADO' }}</td>
            <td class="bg-celeste fs-8" rowspan="1" colspan="2" align="center">{{ 'HIJOS' }}</td>
            <td class="bg-celeste fs-8" rowspan="1" colspan="2" align="center">{{ 'VIDA SEXUAL ACTIVA' }}</td>
            <td style="width: 10%;" class="bg-celeste fs-8" rowspan="1" colspan="3">{{ 'MÉTODO DE PLANIFICACIÓN FAMILIAR' }}</td>
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
            {{-- Antigeno prostatico --}}
            <td>{{ 1 <= count($ficha_preocupacional['examenes_realizados']) && $ficha_preocupacional['examenes_realizados'][0]['tipo'] == 'M' ? $ficha_preocupacional['examenes_realizados'][0]['examen'] : '' }}
            </td>
            <td>{{ 1 <= count($ficha_preocupacional['examenes_realizados']) && $ficha_preocupacional['examenes_realizados'][0]['tipo'] == 'M' ? ($ficha_preocupacional['examenes_realizados'][0]['resultado'] ? 'x' : '') : '' }}
            </td>
            <td>{{ 1 <= count($ficha_preocupacional['examenes_realizados']) && $ficha_preocupacional['examenes_realizados'][0]['tipo'] == 'M' ? (!$ficha_preocupacional['examenes_realizados'][0]['resultado'] ? 'x' : '') : '' }}
            </td>
            <td>{{ 1 <= count($ficha_preocupacional['examenes_realizados']) && $ficha_preocupacional['examenes_realizados'][0]['tipo'] == 'M' ? $ficha_preocupacional['examenes_realizados'][0]['tiempo'] : '' }}
            </td>
            <td>{{ 1 <= count($ficha_preocupacional['examenes_realizados']) && $ficha_preocupacional['examenes_realizados'][0]['tipo'] == 'M' ? $ficha_preocupacional['examenes_realizados'][0]['resultado'] : '' }}
            </td>
        </tr>
        <tr>
            {{-- Antigeno prostatico --}}
            <td>{{ 2 <= count($ficha_preocupacional['examenes_realizados']) && $ficha_preocupacional['examenes_realizados'][1]['tipo'] == 'M' ? $ficha_preocupacional['examenes_realizados'][1]['examen'] : '' }}
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
        border="1" cellpadding="0" cellspacing="0">
        <tr>
            <th align="left" style="width: 100%;">ACCIDENTES DE TRABAJO (DESCRIPCIÓN)</th>
        </tr>
        <tr>
            <td class="fs-10">FUE CALIFICADO POR EL INSTITUTO DE SEGURIDAD SOCIAL CORRESPONDIENTE:
                SI @if ($ficha_preocupacional['accidentes_trabajo']['calificado_iess'])
                    <span class="cuadrado">{{ 'X' }}</span>
                @else
                    <span class="cuadrado">&nbsp;&nbsp;</span>
                @endif
                ESPECIFICAR: IESS
                NO @if (!$ficha_preocupacional['accidentes_trabajo']['calificado_iess'])
                    <span class="cuadrado">{{ 'X' }}</span>
                @else
                    <span class="cuadrado">&nbsp;&nbsp;</span>
                @endif
                <span class="fs-10">FECHA:</span>
                <span
                    class="cuadrado fs-10">{{ $ficha_preocupacional['accidentes_trabajo']['fecha']->format('d') }}</span><span
                    class="cuadrado fs-10">{{ $ficha_preocupacional['accidentes_trabajo']['fecha']->format('m') }}</span><span
                    class="cuadrado fs-10">{{ $ficha_preocupacional['accidentes_trabajo']['fecha']->year }}</span>
            </td>
        </tr>

        <tr>
            <td>
                <div class="mb-4 fs-10">Observaciones:</div>
                <div class="mb-4 fs-10">{{ $ficha_preocupacional['accidentes_trabajo']['observaciones'] }}</div>
            </td>
        </tr>

        <tr>
            <td>{{ 'Detallar aquí en caso se presuma de algún accidente de trabajo que no haya sido reportado o calificado:' }}
            </td>
        </tr>
    </table>

    <table
        style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px;"
        border="1" cellpadding="0" cellspacing="0" class="mb-8">
        <tr>
            <th align="left" style="width: 100%;">{{ 'ENFERMEDADES PROFESIONALES' }}</th>
        </tr>
        <tr>
            <td class="fs-10">FUE CALIFICADO POR EL INSTITUTO DE SEGURIDAD SOCIAL CORRESPONDIENTE:
                SI @if ($ficha_preocupacional['enfermedades_profesionales']['calificado_iess'])
                    <span class="cuadrado">{{ 'X' }}</span>
                @else
                    <span class="cuadrado">&nbsp;&nbsp;</span>
                @endif
                ESPECIFICAR: IESS
                NO @if (!$ficha_preocupacional['enfermedades_profesionales']['calificado_iess'])
                    <span class="cuadrado">{{ 'X' }}</span>
                @else
                    <span class="cuadrado">&nbsp;&nbsp;</span>
                @endif
                <span class="fs-10">FECHA:</span>
                <span
                    class="cuadrado fs-10">{{ $ficha_preocupacional['enfermedades_profesionales']['fecha']->format('d') }}</span><span
                    class="cuadrado fs-10">{{ $ficha_preocupacional['enfermedades_profesionales']['fecha']->format('m') }}</span><span
                    class="cuadrado fs-10">{{ $ficha_preocupacional['enfermedades_profesionales']['fecha']->year }}</span>
            </td>
        </tr>

        <tr>
            <td>
                <div class="mb-4 fs-10">Observaciones:</div>
                <div class="mb-4 fs-10">{{ $ficha_preocupacional['accidentes_trabajo']['observaciones'] }}</div>
            </td>
        </tr>

        <tr>
            <td>{{ 'Detallar aquí en caso se presuma de algún accidente de trabajo que no haya sido reportado o calificado:' }}
            </td>
        </tr>
    </table>

    {{-- G. ACTIVIDADES EXTRA LABORALES --}}
    <div class="titulo-seccion">G. ACTIVIDADES EXTRA LABORALES</div>
    <table
        style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px;"
        border="1" cellpadding="0" cellspacing="0">
        <tr>
            <td>{{ $ficha_preocupacional['actividades_extralaborales'] }}</td>
        </tr>
    </table>

    {{-- H. ENFERMEDAD ACTUAL --}}
    <div class="titulo-seccion">H. ENFERMEDAD ACTUAL</div>
    <table
        style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px;"
        border="1" cellpadding="0" cellspacing="0">
        <tr>
            <td>{{ $ficha_preocupacional['enfermedad_actual'] }}</td>
        </tr>
    </table>

    {{-- J. CONSTANTES VITALES Y ANTROPOMETRÍA --}}
    <div class="titulo-seccion">J. CONSTANTES VITALES Y ANTROPOMETRÍA</div>
    <table
        style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px;"
        border="1" cellpadding="0" cellspacing="0" class="mb-8">
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
            <td class="pa-12">{{ $ficha_preocupacional['constante_vital']['presion_arterial'] }}</td>
            <td class="pa-12">{{ $ficha_preocupacional['constante_vital']['temperatura'] }}</td>
            <td class="pa-12">{{ $ficha_preocupacional['constante_vital']['frecuencia_cardiaca'] }}</td>
            <td class="pa-12">{{ $ficha_preocupacional['constante_vital']['saturacion_oxigeno'] }}</td>
            <td class="pa-12">{{ $ficha_preocupacional['constante_vital']['frecuencia_respiratoria'] }}</td>
            <td class="pa-12">{{ $ficha_preocupacional['constante_vital']['peso'] }}</td>
            <td class="pa-12">{{ $ficha_preocupacional['constante_vital']['talla'] }}</td>
            <td class="pa-12">{{ $ficha_preocupacional['constante_vital']['indice_masa_corporal'] }}</td>
            <td class="pa-12">{{ $ficha_preocupacional['constante_vital']['perimetro_abdominal'] }}</td>
        </tr>
    </table>

    {{-- K. EXAMEN FÍSICO REGIONAL --}}
    <div class="titulo-seccion">K. EXAMEN FÍSICO REGIONAL</div>
    <div class="subtitulo-seccion">REGIONES</div>
    <table
        style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:8px;"
        border="1" cellpadding="0" cellspacing="0" class="mb-8">
        <tbody>
            <tr>
                <td rowspan="3" class="celda"><span class="texto-vertical">1. Piel</span></td>
                <td style="width: 12%;">a. Cicatrices</td>
                <td>{{ in_array(CategoriaExamenFisico::CICATRICES, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td rowspan="3" class="celda"><span class="texto-vertical">3. Oido</span></td>
                <td style="width: 12%;">a. C. auditivo externo</td>
                <td>{{ in_array(CategoriaExamenFisico::AUDITIVO_EXTERNO, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td rowspan="4" class="celda"><span class="texto-vertical">5. Nariz</span></td>
                <td style="width: 12%;">a. Tabique</td>
                <td>{{ in_array(CategoriaExamenFisico::TABIQUE, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td rowspan="2" class="celda"><span class="texto-vertical">8. Tórax</span></td>
                <td style="width: 12%;">a. Pulmones</td>
                <td>{{ in_array(CategoriaExamenFisico::PULMONES, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td rowspan="2" class="celda"><span class="texto-vertical">11. Pelvis</span></td>
                <td style="width: 12%;">a. Pelvis</td>
                <td>{{ in_array(CategoriaExamenFisico::PELVIS, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
            </tr>
            <tr>
                <td>b. Tatuajes</td>
                <td>{{ in_array(CategoriaExamenFisico::TATUAJES, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td>b. Pabellón</td>
                <td>{{ in_array(CategoriaExamenFisico::PABELLON, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td>b. Cornetes</td>
                <td>{{ in_array(CategoriaExamenFisico::CORNETES, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td>b. Parrilla costal</td>
                <td>{{ in_array(CategoriaExamenFisico::PARRILLA_COSTAL, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td>b. Genitales</td>
                <td>{{ in_array(CategoriaExamenFisico::GENITALES, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
            </tr>

            <tr>
                <td>c. Piel y faneras</td>
                <td>{{ in_array(CategoriaExamenFisico::PIEL_FANERAS, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td>c. Tímpanos</td>
                <td>{{ in_array(CategoriaExamenFisico::TIMPANOS, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td>c. Mucosas</td>
                <td>{{ in_array(CategoriaExamenFisico::MUCOSAS, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td rowspan="3" class="celda"><span class="texto-vertical">9. Abdomen</span></td>
                <td>a. Vísceras</td>
                <td>{{ in_array(CategoriaExamenFisico::VISCERAS, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td rowspan="3" class="celda"><span class="texto-vertical">12. Extremidades</span></td>
                <td>a. Vascular</td>
                <td>{{ in_array(CategoriaExamenFisico::VASCULAR, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
            </tr>

            <tr>
                <td rowspan="5" class="celda"><span class="texto-vertical">2. Ojos</span></td>
                <td>a. Párpados</td>
                <td>{{ in_array(CategoriaExamenFisico::PARPADOS, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td rowspan="5" class="celda"><span class="texto-vertical">4. Oro faringe</span></td>
                <td>a. Labios</td>
                <td>{{ in_array(CategoriaExamenFisico::LABIOS, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td>d. Senos paranasales</td>
                <td>{{ in_array(CategoriaExamenFisico::SENOS_PARANASALES, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td>b. Pared abdominal</td>
                <td>{{ in_array(CategoriaExamenFisico::PARED_ABDOMINAL, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td>b. Miembros superiores</td>
                <td>{{ in_array(CategoriaExamenFisico::MIEMBROS_SUPERIORES, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
            </tr>

            <tr>
                <td>b. Conjuntivas</td>
                <td>{{ in_array(CategoriaExamenFisico::CONJUNTIVAS, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td>b. Lengua</td>
                <td>{{ in_array(CategoriaExamenFisico::LENGUA, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td rowspan="2" class="celda"><span class="texto-vertical">6. Cuello</span></td>
                <td>a. Tiroides / masas</td>
                <td>{{ in_array(CategoriaExamenFisico::TIROIDES_MASAS, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td>c. Flexibilidad</td>
                <td>{{ in_array(CategoriaExamenFisico::FLEXIBILIDAD, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td>c. Miembros inferiores</td>
                <td>{{ in_array(CategoriaExamenFisico::MIEMBROS_INFERIORES, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
            </tr>

            <tr>
                <td>c. Pupilas</td>
                <td>{{ in_array(CategoriaExamenFisico::PUPILAS, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td>c. Faringe</td>
                <td>{{ in_array(CategoriaExamenFisico::FARINGE, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td>b. Movilidad</td>
                <td>{{ in_array(CategoriaExamenFisico::MOVILIDAD, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td rowspan="3" class="celda"><span class="texto-vertical">10. Columna</span></td>
                <td rowspan="2">a. Desviación</td>
                <td rowspan="2">
                    {{ in_array(CategoriaExamenFisico::DESVIACION, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td rowspan="4" class="celda"><span class="texto-vertical">13. Neurológico</span></td>
                <td>a. Fuerza</td>
                <td>{{ in_array(CategoriaExamenFisico::FUERZA, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
            </tr>

            <tr>
                <td>d. Córnea</td>
                <td>{{ in_array(CategoriaExamenFisico::CORNEA, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td>d. Amígdalas</td>
                <td>{{ in_array(CategoriaExamenFisico::AMIGDALAS, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td rowspan="2" class="celda"><span class="texto-vertical">7. Tórax</span></td>
                <td>a. Mamas</td>
                <td>{{ in_array(CategoriaExamenFisico::MAMAS, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td>b. Sensibilidad</td>
                <td>{{ in_array(CategoriaExamenFisico::SENSIBILIDAD, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
            </tr>

            <tr>
                <td>e. Motilidad</td>
                <td>{{ in_array(CategoriaExamenFisico::MOTILIDAD, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td>e. Dentadura</td>
                <td>{{ in_array(CategoriaExamenFisico::DENTADURA, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td>b. Corazón</td>
                <td>{{ in_array(CategoriaExamenFisico::CORAZON, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td>b. Dolor</td>
                <td>{{ in_array(CategoriaExamenFisico::DOLOR, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td>c. Marcha</td>
                <td>{{ in_array(CategoriaExamenFisico::MARCHA, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
            </tr>

            <tr>
                <td colspan="12" class="fs-9">
                    {{ 'CON EVIDENCIA DE PATOLOGÍA MARCAR CON "X" Y DESCRIBIR EN LA SIGUIENTE SECCIÓN ANOTANDO EL NUMERAL' }}
                </td>
                <td>d. Reflejos</td>
                <td>{{ in_array(CategoriaExamenFisico::REFLEJOS, $ficha_preocupacional['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
            </tr>

            <tr>
                <td colspan="15">
                    <div class="mb-4"><b class="fs-10">Observaciones:</b></div>
                    <div class="mb-4 fs-10">{{ $ficha_preocupacional['observaciones_examen_fisico_regional'] }}</div>
                </td>
            </tr>
        </tbody>
    </table>

    {{-- M. DIAGNÓSTICO --}}
    <table
        style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px;"
        border="1" cellpadding="0" cellspacing="0" class="mb-8">
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
        border="1" cellpadding="0" cellspacing="0">
        <tr>
            <td>{{ $ficha_preocupacional['recomendaciones_tratamiento'] }}</td>
        </tr>
    </table>

    <p class="fs-10 mb-16">
        {{ 'CERTIFICO QUE LO ANTERIORMENTE EXPRESADO EN RELACIÓN A MI ESTADO DE SALUD ES VERDAD. SE ME HA INFORMADO LAS MEDIDAS PREVENTIVAS A TOMAR PARA DISMINUIR O MITIGAR LOS RIESGOS RELACIONADOS CON MI ACTIVIDAD LABORAL.' }}
    </p>

    <br>

    {{-- F. DATOS DEL PROFESIONAL DE SALUD --}}
    <span style="width: 77%; display: inline-block;" class="border mr-8">
        <div class="titulo-seccion">F. DATOS DEL PROFESIONAL DE SALUD</div>
        <table style="table-layout:fixed; width: 100%;" border="1" cellpadding="0" cellspacing="0">
            <tr>
                <td class="bg-green fs-10" style="width: 8%">FECHA</td>
                <td style="width: 11%">{{ $fecha_creacion }}</td>
                <td class="bg-green fs-10" style="width: 7%">HORA</td>
                <td style="width: 9%">{{ $hora_creacion }}</td>
                <td class="bg-green fs-10" style="width: 12%;">NOMBRE Y APELLIDO</td>
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
                        &nbsp;<br />
                    @endempty
                </td>
            </tr>
        </table>
    </span>

    {{-- G. FIRMA DEL USUARIO --}}
    <span style="width: 20%; display: inline-block;" class="border">
        <div class="titulo-seccion">G. FIRMA DEL USUARIO</div>
        <div class="pa-12">
            &nbsp;
            <br />
            <br />
        </div>
    </span>
</body>

</html>
