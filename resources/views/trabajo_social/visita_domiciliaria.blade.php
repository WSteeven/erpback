<!DOCTYPE html>
<html lang="es">

@php
    use Carbon\Carbon;
    $fecha = Carbon::now();
    $fecha_creacion = Carbon::parse($visita->created_at)->format('Y-m-d');
    $logo_watermark ='data:image/png;base64,' . base64_encode(file_get_contents(public_path() . $configuracion->logo_marca_agua));
@endphp

<head>
    <meta charset="utf-8">
    <title>Visita Domiciliaria</title>
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

        .custom-table table {
            border-collapse: collapse;
            border: 1px solid black;
        }

        .custom-table td {
            line-height: normal;
            border: 1px solid black;
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
                <div style="text-align: center"><b>VISITA DOMICILIARIA</b>
                </div>
            </td>
            <td style="width: 22%">
                <div style="text-align: center"><b>FOR FIRSTRED 005 <br> 20 12 2024 </b></div>
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
        <p><strong>Fecha: </strong> {{$fecha_creacion}}</p>
        <p><strong>1). DATOS PERSONALES</strong></p>
        <p><strong>Nombres y Apellidos: </strong>{{$visita->empleado->nombres}} {{$visita->empleado->apellidos}}</p>
        <table style="width: 100%;">
            <tr>
                <td style="width: 50%;">
                    <p><strong>Identificación: </strong>{{$visita->empleado->identificacion}}</p>
                </td>
                <td style="width: 50%;">
                    <p><strong>Teléfono: </strong>{{ $visita->empleado->telefono }}</p>
                </td>
            </tr>
            <tr>
                <td style="width: 50%;">
                    <p><strong>Fecha Nacimiento: </strong>{{$visita->empleado->fecha_nacimiento}}</p>
                </td>
                <td style="width: 50%;">
                    <p><strong>Estado Civil: </strong>{{$visita->empleado->estadoCivil->nombre}}</p>
                </td>
            </tr>
            <tr>
                <td style="width: 50%;">
                    <p><strong>Lugar Nacimiento: </strong>{{$visita->lugar_nacimiento}}</p>
                </td>
                <td style="width: 50%;">
                    <p><strong>Ciudad de Trabajo: </strong>{{$visita->canton->canton}}</p>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <p><strong>Dirección Domicilio actual: </strong>{{$visita->vivienda->direccion}}</p>
                </td>
            </tr>
            <tr>
                <td style="width: 50%;">
                    <p><strong>Telefono domicilio: </strong>{{$visita->vivienda->telefono}}</p>
                </td>
                <td style="width: 50%;">
                    <p><strong>Coordenadas: </strong>{{$visita->vivienda->coordenadas}}</p>
                </td>
            </tr>
        </table>
        <table style="width: 100%; border: 1px solid #000; border-radius: 5px;">
            <tr>
                <td colspan="3">
                    <p>En caso de emergencia llamar a:</p>
                </td>
            </tr>
            <tr>
                <td style="width: 33%; padding: 2px;">
                    <strong>Nombre: </strong>{{ $visita->contacto_emergencia }}
                </td>
                <td style="width: 33%; padding: 2px;">
                    <strong>Parentesco: </strong>{{ $visita->parentesco_contacto_emergencia }}
                </td>
                <td style="width: 33%; padding: 2px;">
                    <strong>Teléfono: </strong>{{ $visita->telefono_contacto_emergencia }}
                </td>
            </tr>
        </table>

        <p></p>
        <p><strong>2). SITUACIÓN SOCIOFAMILIAR</strong></p>
        {{--        <p style="text-align: center; font-weight: bold">COMPOSICIÓN FAMILIAR</p>--}}
        <table class="custom-table" style="width: 100%;">

            <tr style="font-weight: bold">
                <td>Nombres y Apellidos</td>
                <td>Parentesco</td>
                <td>Edad</td>
                <td>Estado Civil</td>
                <td>Instrucción</td>
                <td>Ocupación/Profesión</td>
                <td>Discapacidad</td>
                <td>Ingreso Mensual</td>
            </tr>
            @foreach($visita->composicionFamiliar as $composicion)
                <tr>
                    <td>{{$composicion['nombres_apellidos']}}</td>
                    <td>{{$composicion['parentesco']}}</td>
                    <td>{{$composicion['edad']}}</td>
                    <td>{{$composicion['estado_civil']}}</td>
                    <td>{{$composicion['instruccion']}}</td>
                    <td>{{$composicion['ocupacion']}}</td>
                    <td>{{$composicion['discapacidad']}}</td>
                    <td>{{$composicion['ingreso_mensual']}}</td>
                </tr>
            @endforeach
        </table>

        <p></p>
        <p><strong>3). GENOGRAMA </strong></p>
        @if(file_exists(public_path($visita->imagen_genograma)))
            <img src="{{ url($visita->imagen_genograma) }}" width="100%" height="200" alt="Genograma"/>
        @else
            <p>No hay imagen de genograma</p>
        @endif

        <p></p>
        <p><strong>4). SALUD </strong></p>
        <table style="width: 100%;">
            <tr>
                <td style="width: 100%;">
                    <p><strong>¿Tiene discapacidad? </strong>{{!!$visita->salud->discapacidades?'SI':'NO'}}</p>
                </td>
            </tr>
            @if(count($visita->salud->discapacidades)>0)
                @foreach($visita->salud->discapacidades as $discapacidad)
                    <tr>
                        <td style="width: 50%;">
                            <p>Tipo: {{$discapacidad['tipo_discapacidad']}}</p>
                        </td>
                        <td style="width: 50%;">
                            <p>Porcentaje: {{$discapacidad['porcentaje']}}</p>
                        </td>
                    </tr>
                @endforeach
            @endif
            @if(!!$visita->salud->enfermedad_cronica)
                <tr>
                    <td style="width: 50%;">
                        <p><strong>¿Sufre alguna enfermedad
                                crónica? </strong>{{!!$visita->salud->enfermedad_cronica?'SI':'NO'}}</p>
                    </td>
                    <td style="width: 50%;">
                        <p><strong>Indique enfermedad: </strong>{{$visita->salud->enfermedad_cronica}}
                        </p>
                    </td>
                </tr>
            @else
                <tr>
                    <td colspan="2">
                        <p><strong>¿Sufre alguna enfermedad
                                crónica? </strong>{{!!$visita->salud->enfermedad_cronica?'SI':'NO'}}</p>
                    </td>
                </tr>
            @endif
            <tr>
                <td style="width: 50%;">
                    <p><strong>Alergias: </strong>{{$visita->salud->alergias}}</p>
                </td>
                <td style="width: 50%;">
                    <p><strong>Lugar de atención: </strong>{{$visita->salud->lugar_atencion}}</p>
                </td>
            </tr>
            <tr>
                <td style="width: 100%;">
                    <p><strong>¿Tiene familiar dependiente con
                            discapacidad? </strong>{{!!$visita->salud->discapacidades_familiar_dependiente?'SI':'NO'}}
                    </p>
                </td>
            </tr>
            @if(count($visita->salud->discapacidades_familiar_dependiente)>0)
                <tr>
                    <td style="width: 50%;">
                        <p><strong>Nombre: </strong>{{$visita->salud->nombre_familiar_dependiente_discapacitado}}</p>
                    </td>
                    <td style="width: 50%;">
                        <p><strong>Parentesco: </strong>{{$visita->salud->parentesco_familiar_discapacitado}}</p>
                    </td>
                </tr>
                @foreach($visita->salud->discapacidades_familiar_dependiente as $discapacidad)
                    <tr>
                        <td style="width: 50%;">
                            <p>Tipo: {{$discapacidad['tipo_discapacidad']}}</p>
                        </td>
                        <td style="width: 50%;">
                            <p>Porcentaje: {{$discapacidad['porcentaje']}}</p>
                        </td>
                    </tr>
                @endforeach
            @endif
        </table>

        <p></p>
        <p><strong>5). ECONOMÍA FAMILIAR</strong></p>
        <p style="text-align: center"><strong>INGRESOS</strong></p>
        <table class="custom-table" style="width: 100%;border: 1px solid #000">
            <thead>
            <th style="width: 40%; border: 1px solid black">Nombres y Apellidos</th>
            <th style="width: 40%; border: 1px solid black">Ocupación</th>
            <th style="width: 20%; border: 1px solid black">Ingreso Mensual</th>
            </thead>
            <tbody>
            @foreach($visita->economiaFamiliar->ingresos as $ingreso)
                <tr>
                    <td>{{$ingreso['nombres_apellidos']}}</td>
                    <td>{{$ingreso['ocupacion']}}</td>
                    <td>{{$ingreso['ingreso_mensual']}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <p style="text-align: center"><strong>EGRESOS</strong></p>
        <table style="width: 100%; border-collapse: collapse">
            <tr>
                <td style="width: 33%; border: 1px solid black">
                    <p>Vivienda: <strong>{{$visita->economiaFamiliar->eg_vivienda}} </strong></p>
                </td>
                <td style="width: 33%; border: 1px solid black">
                    <p>Alimentación: <strong>{{$visita->economiaFamiliar->eg_alimentacion}} </strong></p>
                </td>
                <td style="width: 33%; border: 1px solid black">
                    <p>Educación: <strong>{{$visita->economiaFamiliar->eg_educacion}} </strong></p>
                </td>
            </tr>
            <tr>
                <td style="width: 33%; border: 1px solid black">
                    <p>Servicios Básicos: <strong>{{$visita->economiaFamiliar->eg_servicios_basicos}} </strong></p>
                </td>
                <td style="width: 33%; border: 1px solid black">
                    <p>Transporte: <strong>{{$visita->economiaFamiliar->eg_transporte}} </strong></p>
                </td>
                <td style="width: 33%; border: 1px solid black">
                    <p>Préstamos: <strong>{{$visita->economiaFamiliar->eg_prestamos}} </strong></p>
                </td>
            </tr>
            <tr>
                <td style="width: 33%; border: 1px solid black">
                    <p>Vestimenta: <strong>{{$visita->economiaFamiliar->eg_vestimenta}} </strong></p>
                </td>
                <td style="width: 33%; border: 1px solid black">
                    <p>Salud: <strong>{{$visita->economiaFamiliar->eg_salud}} </strong></p>
                </td>
                <td style="width: 33%; border: 1px solid black">
                    <p>Otros gastos: <strong>{{$visita->economiaFamiliar->eg_otros_gastos}} </strong></p>
                </td>
            </tr>
        </table>
        <br>
        @php
            $total_ingresos = array_sum(array_column($visita->economiaFamiliar->ingresos, 'ingreso_mensual'));
            $total_egresos = $visita->economiaFamiliar->eg_vivienda+ $visita->economiaFamiliar->eg_servicios_basicos+
            $visita->economiaFamiliar->eg_educacion+$visita->economiaFamiliar->eg_salud+$visita->economiaFamiliar->eg_vestimenta+
            $visita->economiaFamiliar->eg_alimentacion+$visita->economiaFamiliar->eg_transporte+$visita->economiaFamiliar->eg_prestamos+
            $visita->economiaFamiliar->eg_otros_gastos;
            $diferencia = $total_ingresos-$total_egresos;
        @endphp
        <table style="width: 100%; border-collapse: collapse">
            <tr>
                <td style="width: 33%; border: 1px solid black">
                    <p>Total Ingresos: <strong>{{$total_ingresos}}</strong></p>
                </td>
                <td style="width: 33%;border: 1px solid black">
                    <p>Total Egresos: <strong>{{$total_egresos}}</strong></p>
                </td>
                <td style="width: 33%;border: 1px solid black">
                    <p>{{$diferencia>0?'Superávit':'Déficit'}}: <strong>{{$diferencia}}</strong></p>
                </td>
            </tr>
        </table>

        <p></p>
        <p><strong>6). INFORMACIÓN DE LA VIVIENDA</strong></p>
        <table class="custom-table" style="width: 100%">
            <tr style="font-weight: bold">
                <td style="width: 25%;">Tenencia</td>
                <td style="width: 25%;">Mat. Techo</td>
                <td style="width: 25%;">Mat. Piso</td>
                <td style="width: 25%;">Mat. Paredes</td>
            </tr>
            <tr>
                <td style="width: 25%;">
                    <p>{{$visita->vivienda->tipo}}</p>
                </td>
                <td style="width: 25%;">
                    <p>{{$visita->vivienda->material_techo}}</p>
                </td>
                <td style="width: 25%;">
                    <p>{{$visita->vivienda->material_piso}}</p>
                </td>
                <td style="width: 25%;">
                    <p>{{$visita->vivienda->material_paredes}}</p>
                </td>
            </tr>
        </table>
        <p></p>
        <table class="custom-table" style="width: 100%;border: 1px solid #000">
            <tr style="font-weight: bold; border: 1px solid #000">
                <td style="width: 33%;">Luz</td>
                <td style="width: 33%;">Agua</td>
                <td style="width: 33%;">Teléfono</td>
            </tr>
            <tr>
                <td style="width: 33%;"><p>{{$visita->vivienda->servicios_basicos['luz']}}</p></td>
                <td style="width: 33%;"><p>{{$visita->vivienda->servicios_basicos['agua']}}</p></td>
                <td style="width: 33%;"><p>{{$visita->vivienda->servicios_basicos['telefono']}}</p></td>
            </tr>
            <tr style="font-weight: bold">
                <td style="width: 33%;">Internet</td>
                <td style="width: 33%;">Cable</td>
                <td style="width: 33%;">Servicios Sanitarios</td>
            </tr>
            <tr>
                <td style="width: 33%;"><p>{{$visita->vivienda->servicios_basicos['internet']}}</p></td>
                <td style="width: 33%;"><p>{{$visita->vivienda->servicios_basicos['cable']}}</p></td>
                <td style="width: 33%;"><p>{{$visita->vivienda->servicios_basicos['servicios_sanitarios']}}</p></td>
            </tr>
        </table>
        <table style="width: 100%;">
            <tr>
                <td style="width: 100%">
                    <p><strong>Distribución de la vivienda: </strong>
                        {{ implode(', ', $visita->vivienda->distribucion_vivienda)}}
                    </p>
                </td>
            </tr>
            <tr>
                <td style="width: 100%">
                    <p><strong>Considera que el espacio donde convive con su grupo familiar es: </strong>
                        {{  $visita->vivienda->comodidad_espacio_familiar}}
                    </p>
                </td>
            </tr>
        </table>
        <table class="custom-table" style="width: 100%;">
            <tr style="font-weight: bold; text-align: center">
                <td style="width: 33%">N° Dormitorios</td>
                <td style="width: 33%">Existe hacinamiento</td>
                <td style="width: 33%">Existe UPC cercano</td>
            </tr>
            <tr>
                <td style="width: 33%">{{  $visita->vivienda->numero_dormitorios}}</td>
                <td style="width: 33%">{{  $visita->vivienda->existe_hacinamiento?'SI':'NO'}}</td>
                <td style="width: 33%">{{  $visita->vivienda->existe_upc_cercano?'SI':'NO'}}</td>
            </tr>
        </table>

        <p></p>
        <p><strong>7).DIAGNÓSTICO SOCIAL</strong></p>
        <p>{{ $visita->diagnostico_social }}</p>

        <p></p>
        <p><strong>8).OBSERVACIONES DEL VISITADOR</strong></p>
        <p>{{ $visita->observaciones }}</p>

        <p></p>
        <p><strong>9). CROQUIS</strong></p>
        <p>Coordenadas: <strong>{{ $visita->vivienda->coordenadas }}</strong></p>
        <p>Dirección: <strong>{{ $visita->vivienda->direccion }}</strong></p>
        <p>Referencia: <strong>{{ $visita->vivienda->referencia }}</strong></p>

        @if(file_exists(public_path($visita->vivienda->imagen_croquis)))
            <img src="{{ url($visita->vivienda->imagen_croquis) }}" width="100%" height="200" alt="Croquis"/>
        @else
            <p>No hay imagen de croquis</p>
        @endif

        <p></p>
        <p><strong>10). FOTOGRAFÍA DE LA VISITA DOMICILIARIA </strong></p>
        @if(file_exists(public_path($visita->imagen_visita_domiciliaria)))
            <img src="{{ url($visita->imagen_visita_domiciliaria) }}" width="100%" height="200"
                 alt="Evidencia de visita domiciliaria"/>
        @else
            <p>No hay imagen de visita domiciliaria</p>
        @endif


        <br><br><br><br>
        <p style="text-align: center"><strong>PARA USO EXCLUSIVO DE LA EMPRESA</strong></p>
        <p>Certifico que la información proporcionada fue verificada físicamente y corresponde a la verdad en lo que
            respecta al alcance de mi conocimiento.</p>
        <p><strong>Responsable</strong></p>
        <p><strong>Nombres y
                Apellidos: </strong> {{$departamento_trabajo_social?->responsable?->nombres}} {{$departamento_trabajo_social?->responsable?->apellidos}}
        </p>
        <p><strong>Identificación: </strong> {{$departamento_trabajo_social?->responsable?->identificacion}}</p>
        <br><br><br><br>

        <table class="firma" style="width: 100%;">
            <thead style="text-align: center">
            <th>
                @if(file_exists(public_path($visita->empleado->firma_url)))
                    <img src="{{ url($visita->empleado->firma_url) }}" width="100%" height="50"
                         alt="firma empleado visitado"/>
                @else
                    ___________________
                @endif
            </th>
            <th></th>
            <th>
                @if(file_exists(public_path($departamento_trabajo_social?->responsable->firma_url)))
                    <img src="{{ url($departamento_trabajo_social?->responsable->firma_url) }}" width="100%" height="50"
                         alt="firma Trabajador Social"/>
                @else
                    ___________________
                @endif
            </th>
            </thead>
            <tbody>
            <tr style="text-align: center">
                <td style="line-height: normal"><b>EMPLEADO VISITADO</b></td>
                <td><b></b></td>
                <td style="line-height: normal"><b>TRABAJADOR SOCIAL</b></td>
            </tr>
            </tbody>
        </table>
        <br><br><br><br><br>
        <table class="firma" style="width: 100%;">
            <tr>
                <td style="text-align: center">
                    @if(file_exists(public_path($departamento_rrhh->responsable->firma_url)))
                        <img src="{{ url($departamento_rrhh->responsable->firma_url) }}" width="100%" height="50"
                             alt="firma RRHH"/>
                    @else
                        ___________________
                    @endif
                    <b>RRHH</b> <br> <br>   <br><br>
                    {{$departamento_rrhh->responsable->nombres}} {{$departamento_rrhh->responsable->apellidos}} <br><br><br><br>
                    {{$departamento_rrhh->responsable->identificacion}}
                </td>
            </tr>
        </table>
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
