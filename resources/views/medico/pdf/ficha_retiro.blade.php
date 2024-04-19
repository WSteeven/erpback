<html>
@php
    $fecha = new Datetime();
    $logo_principal =
        'data:image/png;base64,' . base64_encode(file_get_contents(public_path() . $configuracion['logo_claro']));
    $num_registro = 1;

    // $firma_profesional_salud = 'data:image/png;base64,' . base64_encode(file_get_contents(substr($profesionalSalud->firma_url, 1)));
    use App\Models\Medico\CategoriaExamenFisico;
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>FICHA DE APTITUD</title>
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
            background: #ccffff;
        }

        .bg-green {
            background: #ccffff;
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
            font-size: 9px;
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

    <table
        style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px;"
        border="1" cellpadding="0" cellspacing="0" class="mb-8">
        <tr>
            <th style="width: 40%;">ACTIVIDADES</th>
            <th style="width: 60%;">FACTORES DE RIESGO</th>
        </tr>
        @foreach ($actividadesFactorRiesgo as $actividad)
            <tr>
                <td>{{ $actividad['actividad'] }}</td>
                <td>{{ $actividad['factor_riesgo'] }}</td>
            </tr>
        @endforeach
    </table>

    {{-- B. ANTECEDENTES PERSONALES --}}
    <div class="titulo-seccion">B. ANTECEDENTES PERSONALES</div>
    <table
        style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px;"
        border="1" cellpadding="0" cellspacing="0">
        <tr>
            <th align="left" style="width: 100%;">ANTECEDENTES CLÍNICOS Y QUIRÚRGICOS</th>
        </tr>
        <tr>
            <td>{{ $fichaRetiro['antecedentes_clinicos_quirurjicos'] }}</td>
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
                SI @if ($fichaRetiro['accidentes_trabajo']['calificado_iess'])
                    <span class="cuadrado">{{ 'X' }}</span>
                @else
                    <span class="cuadrado">&nbsp;&nbsp;</span>
                @endif
                ESPECIFICAR: IESS
                NO @if (!$fichaRetiro['accidentes_trabajo']['calificado_iess'])
                    <span class="cuadrado">{{ 'X' }}</span>
                @else
                    <span class="cuadrado">&nbsp;&nbsp;</span>
                @endif
                <span class="fs-10">FECHA:</span>
                <span class="cuadrado fs-10">{{ $fichaRetiro['accidentes_trabajo']['fecha']->format('d') }}</span><span
                    class="cuadrado fs-10">{{ $fichaRetiro['accidentes_trabajo']['fecha']->format('m') }}</span><span
                    class="cuadrado fs-10">{{ $fichaRetiro['accidentes_trabajo']['fecha']->year }}</span>
            </td>
        </tr>

        <tr>
            <td>
                <div class="mb-4 fs-10">Observaciones:</div>
                <div class="mb-4 fs-10">{{ $fichaRetiro['accidentes_trabajo']['observaciones'] }}</div>
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
                SI @if ($fichaRetiro['enfermedades_profesionales']['calificado_iess'])
                    <span class="cuadrado">{{ 'X' }}</span>
                @else
                    <span class="cuadrado">&nbsp;&nbsp;</span>
                @endif
                ESPECIFICAR: IESS
                NO @if (!$fichaRetiro['enfermedades_profesionales']['calificado_iess'])
                    <span class="cuadrado">{{ 'X' }}</span>
                @else
                    <span class="cuadrado">&nbsp;&nbsp;</span>
                @endif
                <span class="fs-10">FECHA:</span>
                <span
                    class="cuadrado fs-10">{{ $fichaRetiro['enfermedades_profesionales']['fecha']->format('d') }}</span><span
                    class="cuadrado fs-10">{{ $fichaRetiro['enfermedades_profesionales']['fecha']->format('m') }}</span><span
                    class="cuadrado fs-10">{{ $fichaRetiro['enfermedades_profesionales']['fecha']->year }}</span>
            </td>
        </tr>

        <tr>
            <td>
                <div class="mb-4 fs-10">Observaciones:</div>
                <div class="mb-4 fs-10">{{ $fichaRetiro['accidentes_trabajo']['observaciones'] }}</div>
            </td>
        </tr>

        <tr>
            <td>{{ 'Detallar aquí en caso se presuma de algún accidente de trabajo que no haya sido reportado o calificado:' }}
            </td>
        </tr>
    </table>

    {{-- C. CONSTANTES VITALES Y ANTROPOMETRÍA --}}
    <div class="titulo-seccion">C. CONSTANTES VITALES Y ANTROPOMETRÍA</div>
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
            <td class="pa-12">{{ '' }}</td>
            <td class="pa-12">{{ '' }}</td>
            <td class="pa-12">{{ '' }}</td>
            <td class="pa-12">{{ '' }}</td>
            <td class="pa-12">{{ '' }}</td>
            <td class="pa-12">{{ '' }}</td>
            <td class="pa-12">{{ '' }}</td>
            <td class="pa-12">{{ '' }}</td>
            <td class="pa-12">{{ '' }}</td>
        </tr>
    </table>
    {{-- {{ $fichaRetiro['examenes_fisicos_regionales'] }} --}}
    {{-- D. EXAMEN FÍSICO REGIONAL --}}
    <div class="titulo-seccion">D. EXAMEN FÍSICO REGIONAL</div>
    <div class="subtitulo-seccion">REGIONES</div>
    <table
        style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:8px;"
        border="1" cellpadding="0" cellspacing="0">
        <tbody>
            <tr>
                <td rowspan="3" class="celda"><span class="texto-vertical">1. Piel</span></td>
                <td style="width: 12%;">a. Cicatrices</td>
                <td>{{ in_array(CategoriaExamenFisico::CICATRICES, $fichaRetiro['examenes_fisicos_regionales']) ? 'x' : '' }}</td>
                <td rowspan="3" class="celda"><span class="texto-vertical">3. Oido</span></td>
                <td style="width: 12%;">a. C. auditivo externo</td>
                <td></td>
                <td rowspan="4" class="celda"><span class="texto-vertical">5. Nariz</span></td>
                <td style="width: 12%;">a. Tabique</td>
                <td></td>
                <td rowspan="2" class="celda"><span class="texto-vertical">8. Tórax</span></td>
                <td style="width: 12%;">a. Pulmones</td>
                <td></td>
                <td rowspan="2" class="celda"><span class="texto-vertical">11. Pelvis</span></td>
                <td style="width: 12%;">a. Pelvis</td>
                <td></td>
            </tr>
            <tr>
                <td>b. Tatuajes</td>
                <td>{{ in_array(CategoriaExamenFisico::TATUAJES, $fichaRetiro['examenes_fisicos_regionales']) ? 'x' : '' }}</td>
                <td>b. Pabellón</td>
                <td>{{ in_array(CategoriaExamenFisico::PABELLON, $fichaRetiro['examenes_fisicos_regionales']) ? 'x' : '' }}</td>
                <td>b. Cornetes</td>
                <td>{{ in_array(CategoriaExamenFisico::CORNETES, $fichaRetiro['examenes_fisicos_regionales']) ? 'x' : '' }}</td>
                <td>b. Parrilla costal</td>
                <td>{{ in_array(CategoriaExamenFisico::PARRILLA_COSTAL, $fichaRetiro['examenes_fisicos_regionales']) ? 'x' : '' }}</td>
                <td>b. Genitales</td>
                <td>{{ in_array(CategoriaExamenFisico::GENITALES, $fichaRetiro['examenes_fisicos_regionales']) ? 'x' : '' }}</td>
            </tr>

            <tr>
                <td>c. Piel y faneras</td>
                <td>{{ in_array(CategoriaExamenFisico::PIEL_FANERAS, $fichaRetiro['examenes_fisicos_regionales']) ? 'x' : '' }}</td>
                <td>c. Tímpanos</td>
                <td>{{ in_array(CategoriaExamenFisico::TIMPANOS, $fichaRetiro['examenes_fisicos_regionales']) ? 'x' : '' }}</td>
                <td>c. Mucosas</td>
                <td>{{ in_array(CategoriaExamenFisico::MUCOSAS, $fichaRetiro['examenes_fisicos_regionales']) ? 'x' : '' }}</td>
                <td rowspan="3" class="celda"><span class="texto-vertical">9. Abdomen</span></td>
                <td>a. Vísceras</td>
                <td>{{ in_array(CategoriaExamenFisico::VISCERAS, $fichaRetiro['examenes_fisicos_regionales']) ? 'x' : '' }}</td>
                <td rowspan="3" class="celda"><span class="texto-vertical">12. Extremidades</span></td>
                <td>a. Vascular</td>
                <td>{{ in_array(35, $fichaRetiro['examenes_fisicos_regionales']) ? 'x' : '' }}
            </tr>

            <tr>
                <td rowspan="5" class="celda"><span class="texto-vertical">2. Ojos</span></td>
                <td>a. Párpados</td>
                <td>{{ in_array(4, $fichaRetiro['examenes_fisicos_regionales']) ? 'x' : '' }}</td>
                <td rowspan="5" class="celda"><span class="texto-vertical">4. Oro faringe</span></td>
                <td>a. Labios</td>
                <td>{{ in_array(13, $fichaRetiro['examenes_fisicos_regionales']) ? 'x' : '' }}</td>
                <td>d. Senos paranasales</td>
                <td>{{ in_array(21, $fichaRetiro['examenes_fisicos_regionales']) ? 'x' : '' }}</td>
                <td>b. Pared abdominal</td>
                <td>{{ in_array(29, $fichaRetiro['examenes_fisicos_regionales']) ? 'x' : '' }}</td>
                <td>b. Miembros superiores</td>
                <td>{{ in_array(36, $fichaRetiro['examenes_fisicos_regionales']) ? 'x' : '' }}</td>
            </tr>

            <tr>
                <td>b. Conjuntivas</td>
                <td>{{ in_array(5, $fichaRetiro['examenes_fisicos_regionales']) ? 'x' : '' }}</td>
                <td>b. Lengua</td>
                <td>{{ in_array(14, $fichaRetiro['examenes_fisicos_regionales']) ? 'x' : '' }}</td>
                <td rowspan="2" class="celda"><span class="texto-vertical">6. Cuello</span></td>
                <td>a. Tiroides / masas</td>
                <td>{{ in_array(22, $fichaRetiro['examenes_fisicos_regionales']) ? 'x' : '' }}</td>
                <td>c. Flexibilidad</td>
                <td>{{ in_array(30, $fichaRetiro['examenes_fisicos_regionales']) ? 'x' : '' }}</td>
                <td>c. Miembros inferiores</td>
                <td>{{ in_array(37, $fichaRetiro['examenes_fisicos_regionales']) ? 'x' : '' }}</td>
            </tr>

            <tr>
                <td>c. Pupilas</td>
                <td>{{ in_array(6, $fichaRetiro['examenes_fisicos_regionales']) ? 'x' : '' }}</td>
                <td>c. Faringe</td>
                <td>{{ in_array(15, $fichaRetiro['examenes_fisicos_regionales']) ? 'x' : '' }}</td>
                <td>b. Movilidad</td>
                <td>{{ in_array(14, $fichaRetiro['examenes_fisicos_regionales']) ? 'x' : '' }}</td>
                <td rowspan="3" class="celda"><span class="texto-vertical">10. Columna</span></td>
                <td rowspan="2">a. Desviación</td>
                <td rowspan="2"></td>
                <td rowspan="4" class="celda"><span class="texto-vertical">13. Neurológico</span></td>
                <td>a. Fuerza</td>
                <td>{{ in_array(14, $fichaRetiro['examenes_fisicos_regionales']) ? 'x' : '' }}</td>
            </tr>

            <tr>
                <td>d. Córnea</td>
                <td></td>
                <td>d. Amígdalas
                </td>
                <td></td>
                <td rowspan="2" class="celda"><span class="texto-vertical">7. Tórax</span></td>
                <td>a. Mamas
                </td>
                <td></td>
                {{-- <td></td> --}}
                <td>b. Sensibilidad
                </td>
                <td></td>
            </tr>

            <tr>
                <td>e. Motilidad</td>
                <td></td>
                <td>e. Dentadura
                </td>
                <td></td>
                <td>b. Corazón
                </td>
                <td></td>
                <td>b. Dolor
                </td>
                <td></td>
                <td>c. Marcha
                </td>
                <td></td>
            </tr>

            <tr>
                <td colspan="12" class="fs-9">
                    {{ 'CON EVIDENCIA DE PATOLOGÍA MARCAR CON "X" Y DESCRIBIR EN LA SIGUIENTE SECCIÓN ANOTANDO EL NUMERAL' }}
                </td>
                <td>d. Reflejos</td>
                <td></td>
            </tr>

            <tr>
                <td colspan="15">
                    <div class="mb-4 fs-10"><b>Observaciones:</b></div>
                    <div class="mb-4 fs-10">{{ $fichaRetiro['observaciones_examen_fisico_regional'] }}</div>
                </td>
            </tr>
        </tbody>
    </table>

    {{-- F. DATOS DEL PROFESIONAL DE SALUD --}}
    <span style="width: 72%; display: inline-block;" class="border mr-8">
        <div class="titulo-seccion">F. DATOS DEL PROFESIONAL DE SALUD</div>
        <table style="table-layout:fixed; width: 100%;" border="1" cellpadding="0" cellspacing="0">
            <tr>
                <td class="bg-green fs-10" style="padding: 6px;">NOMBRE Y APELLIDO</td>
                <td style="width: 20%" class="fs-10">
                    {{ $profesionalSalud->empleado->nombres . ' ' . $profesionalSalud->empleado->apellidos }}</td>
                <td class="bg-green fs-10">CÓDIGO</td>
                <td style="width: 20%">{{ $profesionalSalud->codigo }}</td>
                <td class="bg-green fs-10">FIRMA Y SELLO</td>
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

    <br><br><br><br><br><br>
    <div class="titulo-seccion">D. EXAMEN FÍSICO REGIONAL</div>
    <div class="subtitulo2-seccion">REGIONES</div>

    {{-- @foreach ($regiones as $region)
            <tr>
                <td>
                    {{ $region['region'] }}
                </td>
            </tr>
            @endforeach --}}

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

    {{-- <footer>
        <b style="float: left;">SNS-MSP / Form. CERT. 081 / 2019</b>
        <b style="float: right;">CERTIFICADO DE SALUD EN EL TRABAJO</b>
    </footer> --}}
</body>

</html>
