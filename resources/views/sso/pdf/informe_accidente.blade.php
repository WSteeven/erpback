<!DOCTYPE html>
<html lang="es">
@php
    use Src\Shared\Utils;
    $logo_principal =
        'data:image/png;base64,' . base64_encode(file_get_contents(public_path() . '/img/logo_ufinet.png'));
@endphp
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informe de Accidente Laboral - UFINET ECUADOR</title>
    <style>
        @page {
            margin: 0cm 15px;
        }

        body {
            font-family: Arial, sans-serif;
            max-width: 1000px;
            margin-top: 3cm;
            margin-left: 2cm;
            margin-right: 2cm;
            margin-bottom: 2cm;
            font-size: 14px;
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
            position: relative;
            right: 5px;
            background-color: darkcyan;
        }

        /*h1, h2, h3, h4 {
            color: #2c3e50;
        }*/
        h1 {
            text-align: center;
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 10px;
        }

        h2 {
            /*text-align: center;*/
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            text-transform: uppercase;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .section {
            margin-bottom: 30px;
        }

        .borde-cuadrado {
            border: 1px solid #000;
            padding: 0 16px;
        }

        .text-bold {
            font-weight: bold;
        }

        p {
            text-align: justify;
        }

        .row {
            width: 100%;
            display: flex;
        }

        .text-right {
            justify-items: right;
            float: right;
            display: inline-block;
        }

        .q-mb-sm {
            margin-bottom: 8px;
        }

        .q-pa-xl {
            padding: 48px;
        }

        .header-container {
            white-space: nowrap;
            margin-top: 48px;
        }

        .header-line-left {
            height: 4px;
            background-color: black;
            display: inline-block;
            width: 50%;
        }

        .header-line-right {
            height: 4px;
            background-color: black;
            display: inline-block;
            width: 25%;
        }

        .header-text {
            font-family: Arial, sans-serif;
            font-size: 24px;
            font-weight: bold;
            padding: 0 1rem;
        }

        img {
            display: inline-block;
        }

        .content {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            left: 20px; /* Ajusta la distancia desde la izquierda */
        }

        .gallery {
            width: 100%;
            overflow: hidden; /* Clear floats */
            display: flex;
        }

        .gallery-item {
            width: 35%; /* Three columns per row */
            margin: 1.66%; /* Space between columns */
            /*float: left;*/
            box-sizing: border-box; /* Include padding/border in width */
        }

        .gallery-item img {
            width: 100%; /* Make images responsive */
            display: block;
            border-radius: 5px; /* Rounded corners */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2); /* Subtle shadow */
        }

        .indice {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px;
        }

        .indice h1 {
            text-align: center;
            font-size: 20px;
            margin-bottom: 20px;
        }

        .indice ul {
            list-style-type: none;
            padding-left: 0;
        }

        .indice ul ul {
            margin-left: 20px;
        }

        .indice li {
            margin: 5px 0;
        }

        .indice a {
            text-decoration: none;
            color: #000;
        }

        .indice a:hover {
            text-decoration: underline;
        }

        .indice .page-number {
            float: right;
            color: #555;
        }

        .page-break {
            page-break-before: always;
        }

        @media print {
            .page-break {
                page-break-before: always;
            }
        }
    </style>
</head>
<body>
<header style="margin: 32px 36px 0 36px; color: #6b7280;">
    <div class="q-pa-xl">
        <span class="content" style="font-size: 10px; float: left; text-align: left;">
            <div><b>AREA:</b> {{ 'SSO' }}</div>
            <div><b>INFORME:</b> {{ $accidente['titulo'] }}</div>
        </span>
        <img class="content" src="{{ $logo_principal }}" width="140" style="float: right;">
    </div>
</header>

<footer style="font-size: 10px; padding: 4px 32px 32px 32px; color: #1a202c;">
    <table style="width: 100%; border: none; border-top: 1px solid #1a202c;">
        <tr>
            <td style="border: none;">FORMA</td>
            <td style="width: 60%; line-height: normal; border: none;">
                INFORME DE ACCIDENTE LABORAL / UFI-ECU-PRL_26052023.v1
            </td>
            <td style="border: none;"></td>
        </tr>
        <tr>
            <td style="border: none;">ELA</td>
            <td style="width: 60%; line-height: normal; border: none;">
                Fecha: 27/05-2020 / Rev. V1:
            </td>
            <td style="border: none;">Versión 1</td>
        </tr>
    </table>
</footer>

<div class="header-container">
    <span class="header-line-left"></span>
    <span class="header-text">INFORME</span>
    <span class="header-line-right"></span>
</div>
<p>UFI-ECU-PRL-27052023</p>

<br>
<h2>{{ $accidente['titulo'] }}</h2>

<div class="section borde-cuadrado">
    <h4>Resumen:</h4>
    <p>Datos, descripción, análisis, conclusiones del accidente y medidas preventivas definidas para evitar otros
        accidentes similares.</p>
    <div class="text-bold">Datos:</div>
    <p>{{ $accidente['titulo'] }}</p>
    <div class="text-bold">Descripción:</div>
    <p>{{ $accidente['descripcion']  }}</p>
    <div class="text-bold">Medidas preventivas</div>
    <p>{!! $seguimiento_accidente['medidas_preventivas'] !!}</p>
</div>

<div class="row text-right"><br><br>
    <p><strong>Versión:</strong> 01</p>
    <p><strong>Fecha:</strong>{{ $accidente['created_at'] }}</p>
</div>

<div class="page-break"></div>

<div class="section indice">
    <h1>Índice del Informe</h1>
    <ul>
        <li><a href="#objeto">1. OBJETO</a> <span class="page-number">5</span></li>
        <li>
            <a href="#info-general">2. INFORMACIÓN GENERAL DEL ACCIDENTE</a> <span class="page-number">5</span>
            <ul>
                <li><a href="#datos-generales">2.1. Datos Generales</a> <span class="page-number">5</span></li>
                <li><a href="#formacion-accidentado">2.2. Formación del accidentado</a><span
                        class="page-number">8</span></li>
            </ul>
        </li>
        <li>
            <a href="#datos-generales">3. DATOS GENERALES</a> <span class="page-number">9</span>
            <ul>
                <li><a href="#datos-partida">3.1. Datos de partida</a> <span class="page-number">9</span></li>
                <li><a href="#condiciones-climaticas">3.2. Condiciones climatológicas y de entorno</a> <span
                        class="page-number">10</span></li>
                <li><a href="#condiciones-laborales">3.3. Condiciones laborales</a> <span class="page-number">10</span>
                </li>
                <li><a href="#autorizaciones">3.4. Autorizaciones y permisos de trabajos en la línea</a><span
                        class="page-number">10</span></li>
                <li><a href="#historico-accidentes">3.5. Histórico de accidentes</a> <span class="page-number">10</span>
                </li>
                <li><a href="#proteccion-personal">3.6. Formación y Elementos de Protección del personal de
                        campo</a><span class="page-number">10</span></li>
            </ul>
        </li>
        <li><a href="#sistema-electrico">4. SISTEMA ELÉCTRICO (NO APLICA)</a> <span class="page-number">10</span></li>
        <li><a href="#condicion-electrico">5. CONDICIÓN DEL SISTEMA ELÉCTRICO PRE – ACCIDENTE (NO APLICA)</a><span
                class="page-number">11</span></li>
        <li>
            <a href="#descripcion-accidente">6. DESCRIPCIÓN DEL ACCIDENTE</a> <span class="page-number">11</span>
            <ul>
                <li><a href="#cronograma">6.1. Cronograma</a> <span class="page-number">11</span></li>
                <li><a href="#descripcion-paso">6.2. Descripción del accidente paso a paso</a> <span
                        class="page-number">13</span></li>
            </ul>
        </li>
        <li><a href="#hipotesis-causas">7. HIPÓTESIS CAUSAS DEL ACCIDENTE</a> <span class="page-number">15</span></li>
        <li><a href="#analisis-causas">8. ANÁLISIS DE CAUSAS</a> <span class="page-number">15</span></li>
        <li>
            <a href="#requerimientos">9. REQUERIMIENTOS PREVENCIÓN DE RIESGOS LABORALES (NO APLICA)</a> <span
                class="page-number">16</span>
            <ul>
                <li><a href="#ufinet-contratistas">9.1. De UFINET a sus Contratistas (NO APLICA)</a> <span
                        class="page-number">16</span></li>
            </ul>
        </li>
        <li>
            <a href="#medidas-preventivas">10. MEDIDAS PREVENTIVAS A APLICAR A RAÍZ DE ESTE ACCIDENTE</a> <span
                class="page-number">17</span>
            <ul>
                <li><a href="#prevencion-riesgos">10.1. Prevención de riesgos laborales, Operativos y Técnicas</a> <span
                        class="page-number">17</span></li>
            </ul>
        </li>
        <li><a href="#anexos">11. ANEXOS</a> <span class="page-number">18</span></li>
    </ul>
</div>

<div class="page-break"></div>

<div class="section">
    <h2 id="objeto">1. Objeto</h2>
    <p>El presente documento tiene como objeto relacionar todos los datos asociados al evento y al accidentado, así como
        describir cómo se desarrolló el accidente e identificar las posibles causas que pudo desencadenar el mismo.</p>
    <p>Del mismo modo, se analizan y definen las medidas preventivas a raíz del accidente que deben considerarse y
        aplicarse en adelante con el fin de evitar que vuelvan a repetirse accidentes como éste.</p>
</div>

<div class="section">
    <h2 id="info-general">2. Información General del Accidente</h2>
    <h3 id="datos-generales">2.1. Datos Generales</h3>
    <p>A continuación, se relacionan las empresas implicadas, lugar y hora del accidente y datos del accidentado:</p>
    <table>
        <tr>
            <th>Empresa Promotora:</th>
            <td>UFINET ECUADOR</td>
        </tr>
        <tr>
            <th>Empresa Contratista:</th>
            <td>JP CONSTRUCRED. C.LTDA.</td>
        </tr>
        <tr>
            <th>Fecha:</th>
            <td>{{ strtoupper(\Carbon\Carbon::parse($accidente['created_at'])->translatedFormat('j \\d\\e F \\d\\e\\l Y')) }}</td>
        </tr>
        <tr>
            <th>Línea eléctrica:</th>
            <td>{{ $accidente['titulo'] }}</td>
        </tr>
        <tr>
            <th>Lugar del accidente:</th>
            <td>{{ $accidente['lugar_accidente'] }}</td>
        </tr>
        <tr>
            <th>Hora aproximada del accidente:</th>
            <td>{{ $accidente['fecha_hora_ocurrencia'] }}</td>
        </tr>
    </table>

    <h4>Datos de los accidentados:</h4>
    @foreach($seguimiento_accidente['accidentados_informe'] as $item)
        <table>
            <tr>
                <th>Nombre del accidentado:</th>
                <td>{{ $item['nombre_accidentado']  }}</td>
            </tr>
            <tr>
                <th>Nacionalidad:</th>
                <td>{{ $item['nacionalidad'] }}</td>
            </tr>
            <tr>
                <th>Número de Identificación:</th>
                <td>{{ $item['identificacion'] }}</td>
            </tr>
            <tr>
                <th>Fecha y lugar de nacimiento:</th>
                <td>{{ $item['fecha_lugar_nacimiento'] }}</td>
            </tr>
            <tr>
                <th>Edad:</th>
                <td>{{ $item['edad'] }}</td>
            </tr>
            <tr>
                <th>Cargo:</th>
                <td>{{ $item['cargo'] }}</td>
            </tr>
            <tr>
                <th>Empresa:</th>
                <td>{{ $item['empresa'] }}</td>
            </tr>
            <tr>
                <th>Actividad general desarrollada durante el accidente:</th>
                <td>{{ $item['actividad_durante_accidente'] }}</td>
            </tr>
            <tr>
                <th>Tipo de lesión:</th>
                <td>{{ $item['tipo_lesion']  }}</td>
            </tr>
        </table>
    @endforeach

    <h3 id="formacion-accidentado">2.2. Formación del accidentado</h3>
    <h4>EXPERIENCIA</h4>
    <ul>
        @foreach($seguimiento_accidente['experiencia_accidentados_informe'] as $item)
            <li class="q-mb-sm">
                <div class="text-bold">{{ $item['nombre_accidentado'] }}</div>
                {{ $item['fecha_ingreso'] }}
            </li>
        @endforeach
    </ul>

    <h4>ACREDITACIONES</h4>
    <ul>
        @foreach($seguimiento_accidente['certificaciones'] as $certificacion)

            <li class="q-mb-sm">
                <div class="text-bold">{{ $certificacion['nombre_accidentado'] }}:</div>
                <ul>
                    @foreach($certificacion['certificaciones'] as $item)
                        @foreach($item['certificaciones_id'] as $id)
                            <li>{{ $id }}</li>
                        @endforeach
                    @endforeach
                </ul>
            </li>
        @endforeach
    </ul>

    <h4>FORMACIÓN PREVIA AL ACCIDENTE</h4>
    <ul>
        @foreach($seguimiento_accidente['formacion_accidentados_informe'] as $item)
            <li class="q-mb-sm">
                <div class="text-bold">{{ $item['nombre_accidentado'] }}</div>
                {{ $item['nivel_academico'] }}
            </li>
        @endforeach
    </ul>

    <h4>JUSTIFICANTE DE INFORMACIÓN DE RIESGOS</h4>
    <ul>
        <li>INDUCCION DE SEGURIDAD Y SALUD OCUPACIONAL SEGÚN SUS RIESGOS DE SU AREA DE TRABAJO.</li>
        <li>CAPACITACIÓN USO DE EQUIPO DE PROTECCIÓN PERSONAL.</li>
        <li>CAPACITACIÓN DE LLENADO DE ATS (Análisis de Trabajo Seguro)</li>
    </ul>
</div>

<div class="section">
    <h2 id="datos-generales">3. Datos Generales</h2>
    <h3 id="datos-partida">3.1. Datos de partida</h3>
    <ul>
        <li>Contrato entre UFINET MÉXICO - INGERED</li>
        <li>Tramo de línea del proyecto: {{ $seguimiento_accidente['ruta_tarea'] }}</li>
        <li>Zona de accidente: {{ $accidente['lugar_accidente'] }}</li>
        <li>Actividad: {{ $seguimiento_accidente['titulo_tarea'] }}</li>
        <li>Actividad específica en el momento del
            accidente: {{ $seguimiento_accidente['actividades_desarrolladas'] }}</li>
    </ul>

    <h3 id="condiciones-climaticas">3.2. Condiciones climatológicas y de entorno</h3>
    <p>{{ $seguimiento_accidente['condiciones_climatologicas'] }}</p>

    <h3 id="condiciones-laborales">3.3. Condiciones laborales</h3>
    <p>{{ $seguimiento_accidente['condiciones_laborales'] }}</p>

    <h3 id="autorizaciones">3.4. Autorizaciones y permisos de trabajos en la línea</h3>
    <p>{{ $seguimiento_accidente['autorizaciones_permisos_texto'] }}</p>

    <h3 id="historico-accidentes">3.5. Histórico de accidentes</h3>
    <p>NINGUNO</p>

    <h3 id="proteccion-personal">3.6. Formación y Elementos de Protección del personal de campo</h3>
    <h4 class="sistema-electrico">FORMACION:</h4>
    <ul>
        <li>CAPACITACION EN TRABAJOS EN ALTURA.</li>
        <li>CERTIFICACIÓN EN RIESGO LABORALES (RIESGOS ELECTRICO)</li>
        <li>INDUCCIÓN CORRESPONDIENTE A LOS RIESGOS POR SU PUESTO DE TRABAJO.</li>
    </ul>

    <h4>EQUIPOS DE PROTECCION PERSONAL:</h4>
    <ul>
        <li>CASCO.</li>
        <li>BOTAS DIELECTRICAS</li>
        <li>GUANTES.</li>
        <li>UNIFORMES.</li>
    </ul>
</div>

<div class="section">
    <h2 id="sistema-electrico">4. Sistema Eléctrico (NO APLICA)</h2>
    <p>Esta sección no aplica para este informe.</p>
</div>

<div class="section">
    <h2 id="condicion-electrico">5. Condición del Sistema Eléctrico Pre – Accidente (NO APLICA)</h2>
    <p>Esta sección no aplica para este informe.</p>
</div>

<div class="section">
    <h2 id="descripcion-accidente">6. Descripción del Accidente</h2>
    <h3 id="cronograma">6.1. Cronograma</h3>
    <p>Para centrar la idea de los trabajos que se habían realizado hasta el momento del accidente, se muestra la Tabla
        2 con un cronograma donde se contemplan y describen las principales actividades desarrolladas por el personal de
        campo.</p>
    <table>
        <tr>
            <th>ACTIVIDAD</th>
            <th>FECHA</th>
            <th>DESCRIPCIÓN GENÉRICA</th>
        </tr>
        @if($seguimiento_accidente['actividades_subtarea'])
            @foreach($seguimiento_accidente['actividades_subtarea'] as $actividad)
                <tr>
                    <td>{{ $actividad['actividad'] }}</td>
                    <td>{{ $actividad['fecha_hora'] }}</td>
                    <td>{{ $actividad['trabajo_realizado'] }}</td>
                </tr>
            @endforeach
        @endif
    </table>

    <h3 id="descripcion-paso">6.2. Descripción del accidente paso a paso</h3>
    {!! $seguimiento_accidente['descripcion_amplia_accidente'] !!}

    <ol type="1">
        <li>
            <div class="text-bold">Antes del accidente.</div>
            <p>{!! $seguimiento_accidente['antes_accidente'] !!}</p>
        </li>
        <li>
            <span class="text-bold">Instantes previos al accidente (ROBO)</span>
            <p>{!! $seguimiento_accidente['instantes_previos'] !!}</p>
        </li>
        <li>
            <span class="text-bold">Durante el accidente (ROBO)</span>
            <p>{!! $seguimiento_accidente['durante_accidente'] !!}</p>
        </li>
        <li>
            <span class="text-bold">Después del accidente (ROBO)</span>
            <p>{!! $seguimiento_accidente['despues_accidente'] !!}</p>
        </li>
    </ol>
</div>

<div class="section">
    <h2 id="hipotesis-causas">7. Hipótesis Causas del Accidente</h2>
    <p>{!! $seguimiento_accidente['hipotesis_causa_accidente'] !!}</p>
</div>

<div class="section">
    <h2 id="analisis-causas">8. Análisis de Causas</h2>
    <p><b>Describir metodología utilizada:</b> {{ $seguimiento_accidente['metodologia_utilizada'] }}</p>
    <p><b>Causas inmediatas:</b> {{ $seguimiento_accidente['causas_inmediatas'] }}</p>
    <p><b>Causas básicas del accidente:</b> {{ $seguimiento_accidente['causas_basicas'] }}</p>
</div>

<div class="section">
    <h2 id="requerimientos">9. Requerimientos Prevención de Riesgos Laborales (NO APLICA)</h2>
    <h3 id="ufinet-contratistas">9.1. De UFINET a sus Contratistas (NO APLICA)</h3>
    <table>
        <thead>
        <tr>
            <th>Actividad</th>
            <th>Descripción de actividades realizadas</th>
            <th>¿Actividad realizadas en estos trabajos?</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td class="bold">Actividad 1</td>
            <td>Solicitud de documentación previo al inicio de la prestación de servicios:</td>
            <td></td>
        </tr>
        <tr>
            <td class="bold">Actividad 2</td>
            <td>-Solicitud de realización de inspecciones documentadas y seguimiento de las labores operativas (in-situ)
                a realizar por el contratista
            </td>
            <td></td>
        </tr>
        <tr>
            <td class="bold">Actividad 3</td>
            <td>Solicitud de entrega de los Procedimientos de trabajo seguro, según las actividades a realizar.</td>
            <td></td>
        </tr>
        <tr>
            <td class="bold">Actividad 4</td>
            <td>Solicitud a los contratistas de impartición de charlas diarias sobre Seguridad Industrial y Salud
                Ocupacional a su empleados y subcontratistas.
            </td>
            <td></td>
        </tr>
        <tr>
            <td class="bold">Actividad 5</td>
            <td>Seguimiento al cumplimiento de cobertura de Seguridad Social del personal de los contratistas y
                subcontratistas que realiza actividades para Ufinet
            </td>
            <td></td>
        </tr>
        <tr>
            <td class="bold">Actividad 6</td>
            <td>Verificación de competencias del personal en campo, Programa de Inducciones, capacitación y
                entrenamiento en seguridad y salud (Trabajo en alturas – Riesgo eléctrico)
            </td>
            <td></td>
        </tr>
        <tr>
            <td class="bold">Actividad 7</td>
            <td>Reporte de Accidentes Laborales inmediato a UFINET, registro y Estadísticas de Accidentalidad.</td>
            <td></td>
        </tr>
        <tr>
            <td class="bold">Actividad 8</td>
            <td>Seguimiento y auditorías en campo, acciones preventivas y correctivas para mitigar riesgos
                identificados.
            </td>
            <td></td>
        </tr>
        <tr>
            <td class="bold">Actividad 10</td>
            <td>Requerimiento de personal de seguridad y salud por parte del contratista en sitio.</td>
            <td></td>
        </tr>
        </tbody>
    </table>
    <caption>Tabla 3.- Actividades de Prevención desarrolladas por UFINET con sus contratistas.</caption>
</div>

<div class="section">
    <h2 id="medidas-preventivas">10. Medidas Preventivas a Aplicar a Raíz de este Accidente</h2>
    <h3 id="prevencion-riesgos">10.1. Prevención de riesgos laborales, Operativos y Técnicas</h3>
    <p>{!! $seguimiento_accidente['medidas_preventivas'] !!}</p>

</div>

<div class="section">
    <h2 id="anexos">11. Anexos</h2>
    <p>Se adjuntan los siguientes documentos:</p>
    <div class="gallery">
        @foreach($seguimiento_accidente['archivos'] as $ruta)
            <div class="gallery-item">
                {{-- <img src="{{ 'data:image/png;base64,' . base64_encode(file_get_contents(public_path() . $ruta)) }}"> --}}
                <img src="{{ Utils::urlToBase64(url($ruta)) }}">
            </div>
        @endforeach
    </div>
</div>

<script type="text/php">
    $pdf->page_script('
       $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "bold"); // Fuente en negrita
       $x = $pdf->get_width() - 134; // 100px desde el borde izquierdo
       $y = $pdf->get_height() - 78; // 30px desde el borde inferior
       $pdf->text($x, $y, "Página $PAGE_NUM de $PAGE_COUNT", $font, 8);
   ');
</script>
</body>
</html>
