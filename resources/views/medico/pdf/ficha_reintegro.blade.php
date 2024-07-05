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
    <title>FICHA DE REINTEGRO</title>
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
        border="1" cellpadding="0" cellspacing="0" class="celdas_amplias mb-8">
        <tr>
            <th style="width: 10%;">PRIMER APELLIDO</th>
            <th style="width: 10%;">SEGUNDO APELLIDO</th>
            <th style="width: 10%;">PRIMER NOMBRE</th>
            <th style="width: 10%;">SEGUNDO NOMBRE</th>
            <th style="width: 8%;">SEXO</th>
            <th style="width: 8%;">EDAD <br> <small class="text-mini">(AÑOS)</small></th>
            <th style="width: 15%;">PUESTO DE TRABAJO <br> <small class="text-mini">(CIUO)</small></th>
            <th style="width: 8%;">FECHA DEL ÚLTIMO DÍA LABORAL</th>
            <th style="width: 8%;">FECHA DE REINGRESO</th>
            <th style="width: 8%;">TOTAL <br> <small class="text-mini">(DÍAS)</small></th>
            <th style="width: 8%;">CAUSA DE SALIDA</th>
        </tr>
        <tr>
            <td align="center">{{ explode(' ', $empleado['apellidos'])[0] }}</td>
            <td align="center">{{ explode(' ', $empleado['apellidos'])[1] }}</td>
            <td align="center">{{ explode(' ', $empleado['nombres'])[0] }}</td>
            <td align="center">{{ explode(' ', $empleado['nombres'])[1] }}</td>
            <td align="center">{{ $empleado['genero'] }}</td>
            <td align="center">{{ $empleado['edad'] }}</td>
            <td style="width: 5%;" align="center">{{ $empleado['cargo']->nombre }}</td>
            <td align="center">{{ $ficha_reintegro['fecha_ultimo_dia_laboral'] }}</td>
            <td align="center">{{ $ficha_reintegro['fecha_reingreso'] }}</td>
            <td align="center">{{ $ficha_reintegro['total_dias'] }}</td>
            <td align="center">{{ $ficha_reintegro['causa_salida'] }}</td>

        </tr>
    </table>

    {{-- B. MOTIVO DE LA CONSULTA --}}
    <div class="titulo-seccion">B. MOTIVO DE LA CONSULTA</div>
    <table
        style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px;"
        border="1" cellpadding="0" cellspacing="0" class="celdas_amplias mb-8">
        <tr>
            <td>
                <div class="mb-4"><b class="text-mini">Descripción</b></div>
                {{ $ficha_reintegro['motivo_consulta'] }}
            </td>
        </tr>
    </table>

    {{-- C. ENFERMEDAD ACTUAL --}}
    <div class="titulo-seccion">C. ENFERMEDAD ACTUAL</div>
    <table
        style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px;"
        border="1" cellpadding="0" cellspacing="0" class="celdas_amplias mb-8">
        <tr>
            <td>
                <div class="mb-4"><b class="text-mini">Descripción</b></div>
                {{ $ficha_reintegro['enfermedad_actual'] }}
            </td>
        </tr>
    </table>

    {{-- D. CONSTANTES VITALES Y ANTROPOMETRÍA --}}
    <div class="titulo-seccion">D. CONSTANTES VITALES Y ANTROPOMETRÍA</div>
    <table
        style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px;"
        border="1" cellpadding="0" cellspacing="0" class="celdas_amplias mb-8">
        <tr>
            <th style="width: 10%;" class="fs-10">PRESIÓN ARTERIAL <br> <small class="text-mini">(mmHg)</small></th>
            <th style="width: 12%;" class="fs-10">TEMPERATURA <br> <small class="text-mini">(°C)</small></th>
            <th style="width: 15%;" class="fs-10">FRECUENCIA CARDIACA <br> <small class="text-mini">(l/min)</small>
            </th>
            <th style="width: 12%;" class="fs-10">SATURACIÓN DE OXÍGENO <br> <small class="text-mini">(%)</small></th>
            <th style="width: 12%;" class="fs-10">FRECUENCIA RESPIRATORIA <br> <small
                    class="text-mini">(fr/min)</small></th>
            <th style="width: 8%;" class="fs-10">PESO <br> <small class="text-mini">(Kg)</small></th>
            <th style="width: 8%;" class="fs-10">TALLA <br> <small class="text-mini">(cm)</small></th>
            <th style="width: 10%;" class="fs-10">ÍNDICE DE MASA CORPORAL <br> <small class="text-mini">(kg/m2)</small>
            </th>
            <th style="width: 10%;" class="fs-10">PERÍMETRO ABDOMINAL <br> <small class="text-mini">(cm)</small>
            </th>
        </tr>
        <tr>
            <td>{{ $ficha_reintegro['constante_vital']['presion_arterial'] }}</td>
            <td>{{ $ficha_reintegro['constante_vital']['temperatura'] }}</td>
            <td>{{ $ficha_reintegro['constante_vital']['frecuencia_cardiaca'] }}</td>
            <td>{{ $ficha_reintegro['constante_vital']['saturacion_oxigeno'] }}</td>
            <td>{{ $ficha_reintegro['constante_vital']['frecuencia_respiratoria'] }}</td>
            <td>{{ $ficha_reintegro['constante_vital']['peso'] }}</td>
            <td>{{ $ficha_reintegro['constante_vital']['talla'] }}</td>
            <td>{{ $ficha_reintegro['constante_vital']['indice_masa_corporal'] }}</td>
            <td>{{ $ficha_reintegro['constante_vital']['perimetro_abdominal'] }}</td>
        </tr>
    </table>

    {{-- E. EXAMEN FÍSICO REGIONAL --}}
    <div class="titulo-seccion">E. EXAMEN FÍSICO REGIONAL</div>
    <div class="subtitulo-seccion">REGIONES</div>
    <table
        style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:8px;"
        border="1" cellpadding="0" cellspacing="0" class="mb-8 celdas_amplias">
        <tbody>
            <tr>
                <td rowspan="3" class="celda bg-celeste"><span class="texto-vertical fs-9">1. Piel</span></td>
                <td class="bg-celeste" style="width: 12%;">a. Cicatrices</td>
                <td>{{ in_array(CategoriaExamenFisico::CICATRICES, $ficha_reintegro['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td rowspan="3" class="celda bg-celeste"><span class="texto-vertical fs-9">3. Oido</span></td>
                <td class="bg-celeste" style="width: 12%;">a. C. auditivo externo</td>
                <td>{{ in_array(CategoriaExamenFisico::AUDITIVO_EXTERNO, $ficha_reintegro['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td rowspan="4" class="celda bg-celeste"><span class="texto-vertical fs-9">5. Nariz</span></td>
                <td class="bg-celeste" style="width: 12%;">a. Tabique</td>
                <td>{{ in_array(CategoriaExamenFisico::TABIQUE, $ficha_reintegro['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td rowspan="2" class="celda bg-celeste"><span class="texto-vertical fs-9">8. Tórax</span></td>
                <td class="bg-celeste" style="width: 12%;">a. Pulmones</td>
                <td>{{ in_array(CategoriaExamenFisico::PULMONES, $ficha_reintegro['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td rowspan="2" class="celda bg-celeste"><span class="texto-vertical fs-9">11. Pelvis</span></td>
                <td class="bg-celeste" style="width: 12%;">a. Pelvis</td>
                <td>{{ in_array(CategoriaExamenFisico::PELVIS, $ficha_reintegro['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
            </tr>
            <tr>
                <td class="bg-celeste">b. Tatuajes</td>
                <td>{{ in_array(CategoriaExamenFisico::TATUAJES, $ficha_reintegro['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td class="bg-celeste">b. Pabellón</td>
                <td>{{ in_array(CategoriaExamenFisico::PABELLON, $ficha_reintegro['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td class="bg-celeste">b. Cornetes</td>
                <td>{{ in_array(CategoriaExamenFisico::CORNETES, $ficha_reintegro['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td class="bg-celeste">b. Parrilla costal</td>
                <td>{{ in_array(CategoriaExamenFisico::PARRILLA_COSTAL, $ficha_reintegro['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td class="bg-celeste">b. Genitales</td>
                <td>{{ in_array(CategoriaExamenFisico::GENITALES, $ficha_reintegro['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
            </tr>

            <tr>
                <td class="bg-celeste">c. Piel y faneras</td>
                <td>{{ in_array(CategoriaExamenFisico::PIEL_FANERAS, $ficha_reintegro['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td class="bg-celeste">c. Tímpanos</td>
                <td>{{ in_array(CategoriaExamenFisico::TIMPANOS, $ficha_reintegro['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td class="bg-celeste">c. Mucosas</td>
                <td>{{ in_array(CategoriaExamenFisico::MUCOSAS, $ficha_reintegro['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td rowspan="3" class="celda bg-celeste"><span class="texto-vertical fs-9">9. Abdomen</span></td>
                <td class="bg-celeste">a. Vísceras</td>
                <td>{{ in_array(CategoriaExamenFisico::VISCERAS, $ficha_reintegro['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td rowspan="3" class="celda bg-celeste"><span class="texto-vertical fs-9">12. Extremidades</span>
                </td>
                <td class="bg-celeste">a. Vascular</td>
                <td>{{ in_array(CategoriaExamenFisico::VASCULAR, $ficha_reintegro['examenes_fisicos_regionales']) ? 'x' : '' }}
            </tr>

            <tr>
                <td rowspan="5" class="celda bg-celeste"><span class="texto-vertical fs-9">2. Ojos</span></td>
                <td class="bg-celeste">a. Párpados</td>
                <td>{{ in_array(CategoriaExamenFisico::PARPADOS, $ficha_reintegro['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td rowspan="5" class="celda bg-celeste"><span class="texto-vertical fs-9">4. Oro faringe</span>
                </td>
                <td class="bg-celeste">a. Labios</td>
                <td>{{ in_array(CategoriaExamenFisico::LABIOS, $ficha_reintegro['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td class="bg-celeste">d. Senos paranasales</td>
                <td>{{ in_array(CategoriaExamenFisico::SENOS_PARANASALES, $ficha_reintegro['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td class="bg-celeste">b. Pared abdominal</td>
                <td>{{ in_array(CategoriaExamenFisico::PARED_ABDOMINAL, $ficha_reintegro['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td class="bg-celeste">b. Miembros superiores</td>
                <td>{{ in_array(CategoriaExamenFisico::MIEMBROS_SUPERIORES, $ficha_reintegro['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
            </tr>

            <tr>
                <td class="bg-celeste">b. Conjuntivas</td>
                <td>{{ in_array(CategoriaExamenFisico::CONJUNTIVAS, $ficha_reintegro['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td class="bg-celeste">b. Lengua</td>
                <td>{{ in_array(CategoriaExamenFisico::LENGUA, $ficha_reintegro['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td rowspan="2" class="celda bg-celeste"><span class="texto-vertical fs-9">6. Cuello</span></td>
                <td class="bg-celeste">a. Tiroides / masas</td>
                <td>{{ in_array(CategoriaExamenFisico::TIROIDES_MASAS, $ficha_reintegro['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td class="bg-celeste">c. Flexibilidad</td>
                <td>{{ in_array(CategoriaExamenFisico::FLEXIBILIDAD, $ficha_reintegro['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td class="bg-celeste">c. Miembros inferiores</td>
                <td>{{ in_array(CategoriaExamenFisico::MIEMBROS_INFERIORES, $ficha_reintegro['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
            </tr>

            <tr>
                <td class="bg-celeste">c. Pupilas</td>
                <td>{{ in_array(CategoriaExamenFisico::PUPILAS, $ficha_reintegro['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td class="bg-celeste">c. Faringe</td>
                <td>{{ in_array(CategoriaExamenFisico::FARINGE, $ficha_reintegro['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td class="bg-celeste">b. Movilidad</td>
                <td>{{ in_array(CategoriaExamenFisico::MOVILIDAD, $ficha_reintegro['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td rowspan="3" class="celda bg-celeste"><span class="texto-vertical fs-9">10. Columna</span></td>
                <td class="bg-celeste" rowspan="2">a. Desviación</td>
                <td rowspan="2">
                    {{ in_array(CategoriaExamenFisico::DESVIACION, $ficha_reintegro['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td rowspan="4" class="celda bg-celeste"><span class="texto-vertical fs-9">13. Neurológico</span>
                </td>
                <td class="bg-celeste">a. Fuerza</td>
                <td>{{ in_array(CategoriaExamenFisico::FUERZA, $ficha_reintegro['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
            </tr>

            <tr>
                <td class="bg-celeste">d. Córnea</td>
                <td>{{ in_array(CategoriaExamenFisico::CORNEA, $ficha_reintegro['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td class="bg-celeste">d. Amígdalas</td>
                <td>{{ in_array(CategoriaExamenFisico::AMIGDALAS, $ficha_reintegro['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td rowspan="2" class="celda bg-celeste"><span class="texto-vertical fs-9">7. Tórax</span></td>
                <td class="bg-celeste">a. Mamas</td>
                <td>{{ in_array(CategoriaExamenFisico::MAMAS, $ficha_reintegro['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td class="bg-celeste">b. Sensibilidad</td>
                <td>{{ in_array(CategoriaExamenFisico::SENSIBILIDAD, $ficha_reintegro['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
            </tr>

            <tr>
                <td class="bg-celeste">e. Motilidad</td>
                <td>{{ in_array(CategoriaExamenFisico::MOTILIDAD, $ficha_reintegro['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td class="bg-celeste">e. Dentadura</td>
                <td>{{ in_array(CategoriaExamenFisico::DENTADURA, $ficha_reintegro['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td class="bg-celeste">b. Corazón</td>
                <td>{{ in_array(CategoriaExamenFisico::CORAZON, $ficha_reintegro['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td class="bg-celeste">b. Dolor</td>
                <td>{{ in_array(CategoriaExamenFisico::DOLOR, $ficha_reintegro['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
                <td class="bg-celeste">c. Marcha</td>
                <td>{{ in_array(CategoriaExamenFisico::MARCHA, $ficha_reintegro['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
            </tr>

            <tr>
                <td colspan="12" class="fs-9">
                    {{ 'CON EVIDENCIA DE PATOLOGÍA MARCAR CON "X" Y DESCRIBIR EN LA SIGUIENTE SECCIÓN ANOTANDO EL NUMERAL' }}
                </td>
                <td class="bg-celeste">d. Reflejos</td>
                <td>{{ in_array(CategoriaExamenFisico::REFLEJOS, $ficha_reintegro['examenes_fisicos_regionales']) ? 'x' : '' }}
                </td>
            </tr>

            <tr>
                <td colspan="15">
                    <div class="mb-4"><b class="text-mini">Observaciones:</b></div>
                    <div class="mb-4 fs-9">
                        {{-- {{ json_encode($ficha_reintegro['observaciones_examen_fisico_regional']) }} --}}

                        @foreach ($ficha_reintegro['observaciones_examen_fisico_regional'] as $item)
                            <div class="fs-9">{{ $item['categoria'] . ': ' . $item['observacion'] }}</div>
                        @endforeach
                    </div>
                </td>
            </tr>
        </tbody>
    </table>

    {{-- F. RESULTADOS DE EXÁMENES GENERALES (IMAGEN, LABORATORIO Y OTROS) --}}
    <div class="titulo-seccion">F. RESULTADOS DE EXÁMENES (IMAGEN, LABORATORIO Y OTROS)</div>
    <table
        style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px;"
        border="1" cellpadding="0" cellspacing="0" class="celdas_amplias mb-8">
        <tr>
            <th style="width: 20%;">EXAMEN</th>
            <th style="width: 15%;">FECHA <br> <small class="text-mini">{{ 'aaaa/mm/dd' }}</small></th>
            <th style="width: 65%;">RESULTADO</th>
        </tr>

        @foreach ($ficha_reintegro['resultados_examenes'] as $resultado_examen)
            <tr>
                <td>{{ $resultado_examen['examen'] }}</td>
                <td>{{ $resultado_examen['fecha_asistencia'] }}</td>
                <td>
                    <table
                        style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif;"
                        border="0">
                        <tr>
                            <th style="border: none; width: 25%; background: #fff; text-align: left;">
                                {{ 'Parámetro' }}</th>
                            <th style="border: none; width: 25%; background: #fff; text-align: left;">
                                {{ 'Resultado' }}</th>
                            <th style="border: none; width: 50%; background: #fff; text-align: left;">
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
                                    {{ $resultado['observaciones'] ?? '(Sin observacióon)' }}
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </td>
            </tr>
        @endforeach
    </table>

    {{-- G. DIAGNÓSTICO --}}
    <table
        style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px;"
        border="1" cellpadding="0" cellspacing="0" class="celdas_amplias mb-8">
        <tr>
            <th colspan="2" class=" bg-titulo" style="width: 60%; text-align: left;">
                <span class="fs-10">
                    G. DIAGNÓSTICO
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

        @if (count($ficha_reintegro['consultas_medicas']) === 0)
            <tr>
                <td style="width: 4%;" class="bg-green">{{ '1' }}</td>
                <td style="width: 55%;">{{ '' }}</td>
                <td style="width: 25%;">{{ '' }}</td>
                <td style="width: 8%;">{{ '' }}</td>
                <td style="width: 8%;">{{ '' }}</td>
            </tr>
        @else
            @foreach ($ficha_reintegro['consultas_medicas'][0]['diagnosticos'] as $diagnostico)
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

    {{-- H. APTITUD MÉDICA LABORAL --}}
    <div class="titulo-seccion">H. APTITUD MÉDICA PARA EL TRABAJO</div>
    <div class="mb-8">
        <table
            style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif;"
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
            style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif;"
            border="1" cellpadding="0" cellspacing="0">
            <tr>
                <td class="bg-celeste px-8 fs-10" style="width: 12%;">{{ 'Observación' }}</td>
                <td class="px-8 fs-10" style="width: 88%;">
                    {{ $ficha_reintegro['aptitud_medica']['observacion'] }}</td>
            </tr>
            <tr>
                <td class="bg-celeste px-8 fs-10">{{ 'Limitación' }}</td>
                <td class="px-8 fs-10">
                    {{ $ficha_reintegro['aptitud_medica']['limitacion'] }}</td>
            </tr>
        </table>
    </div>

    {{-- I. RECOMENDACIONES Y/O TRATAMIENTO --}}
    <div class="titulo-seccion">I. RECOMENDACIONES Y/O TRATAMIENTO</div>
    <table
        style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px;"
        border="1" cellpadding="0" cellspacing="0" class="celdas_amplias mb-8">
        <tr>
            <td>
                <div class="mb-4"><b class="text-mini">Descripción</b></div>
                {{ $ficha_reintegro['recomendaciones_tratamiento'] }}
            </td>
        </tr>
    </table>

    <p class="fs-10 mb-16">
        {{ 'CERTIFICO QUE LO ANTERIORMENTE EXPRESADO EN RELACIÓN A MI ESTADO DE SALUD ES VERDAD. SE ME HA INFORMADO LAS MEDIDAS PREVENTIVAS A TOMAR PARA DISMINUIR O MITIGAR LOS RIESGOS RELACIONADOS CON MI ACTIVIDAD LABORAL.' }}
    </p>

    <br>

    {{-- J. DATOS DEL PROFESIONAL DE SALUD --}}
    <span style="width: 77%; display: inline-block;" class="border mr-8">
        <div class="titulo-seccion">J. DATOS DEL PROFESIONAL DE SALUD</div>
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

    {{-- K. FIRMA DEL USUARIO --}}
    <span style="width: 20%; display: inline-block;" class="border">
        <div class="titulo-seccion">K. FIRMA DEL USUARIO</div>
        <div style="padding: 16px;">
            &nbsp;
            <br />
        </div>
    </span>
</body>

</html>
