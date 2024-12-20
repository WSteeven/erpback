<!DOCTYPE html>
<html lang="es">

@php
    use Carbon\Carbon;
    $fecha = Carbon::now();
    $fecha_creacion = Carbon::parse($ficha->created_at)->format('Y-m-d');
    $logo_watermark ='data:image/png;base64,' . base64_encode(file_get_contents(public_path() . $configuracion->logo_marca_agua));
@endphp

<head>
    <meta charset="utf-8">
    <title>Ficha Socioeconomica</title>
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
                <div style="text-align: center"><b>FICHA SOCIOECONOMICA</b>
                </div>
            </td>
            <td style="width: 22%">
                <div style="text-align: center"><b>FOR FIRSTRED 004 <br> 20 12 2024 </b></div>
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
        <p><strong>Nombres y Apellidos: </strong>{{$ficha->empleado->nombres}} {{$ficha->empleado->apellidos}}</p>
        <table style="width: 100%;">
            <tr>
                <td style="width: 50%;">
                    <p><strong>Identificación: </strong>{{$ficha->empleado->identificacion}}</p>
                </td>
                <td style="width: 50%;">
                    <p><strong>Teléfono: </strong>{{ $ficha->empleado->telefono }}</p>
                </td>
            </tr>
            <tr>
                <td style="width: 50%;">
                    <p><strong>Fecha Nacimiento: </strong>{{$ficha->empleado->fecha_nacimiento}}</p>
                </td>
                <td style="width: 50%;">
                    <p><strong>Estado Civil: </strong>{{$ficha->empleado->estadoCivil->nombre}}</p>
                </td>
            </tr>
            <tr>
                <td style="width: 50%;">
                    <p><strong>Lugar Nacimiento: </strong>{{$ficha->lugar_nacimiento}}</p>
                </td>
                <td style="width: 50%;">
                    <p><strong>Ciudad de Trabajo: </strong>{{$ficha->canton->canton}}</p>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <p><strong>Dirección Domicilio actual: </strong>{{$ficha->vivienda->direccion}}</p>
                </td>
            </tr>
            <tr>
                <td style="width: 50%;">
                    <p><strong>Telefono domicilio: </strong>{{$ficha->vivienda->telefono}}</p>
                </td>
                <td style="width: 50%;">
                    <p><strong>Coordenadas: </strong>{{$ficha->vivienda->coordenadas}}</p>
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
                    <strong>Nombre: </strong>{{ $ficha->contacto_emergencia }}
                </td>
                <td style="width: 33%; padding: 2px;">
                    <strong>Parentesco: </strong>{{ $ficha->parentesco_contacto_emergencia }}
                </td>
                <td style="width: 33%; padding: 2px;">
                    <strong>Teléfono: </strong>{{ $ficha->telefono_contacto_emergencia }}
                </td>
            </tr>
        </table>

        <p></p>
        <p><strong>2). INFORMACION DEL CÓNYUGE </strong></p>
        @if(!!$ficha->conyuge)
            <p><strong>Nombres y Apellidos: </strong>{{$ficha->conyuge->nombres}} {{$ficha->conyuge->apellidos}}</p>
            <p><strong>Nivel Académico: </strong>{{$ficha->conyuge->nivel_academico}}</p>
        @else
            <p style="background-color: #dde7f3">El empleado no tiene cónyuge.</p>
        @endif
        <p></p>
        <p><strong>3). INFORMACION DE LOS HIJOS</strong></p>
        @if($ficha->hijos->count()>0)
            <table>
                <tr style="font-weight: bold">
                    <td>Nombre</td>
                    <td>Ocupacion</td>
                    <td>Edad</td>
                </tr>
                @foreach($ficha->hijos as $hijo)
                    <tr>
                        <td>{{$hijo['nombres_apellidos']}}</td>
                        <td>{{$hijo['ocupacion']}}</td>
                        <td>{{$hijo['edad']}}</td>
                    </tr>
                @endforeach
            </table>
        @else
            <p style="background-color: #dde7f3">El empleado no tiene hijos. </p>
        @endif
        <p></p>
        <p><strong>4). EXPERIENCIA LABORAL (último empleo)</strong></p>
        @if(!!$ficha->experienciaPrevia)
            <table style="width: 100%;">
                <tr>
                    <td style="width: 50%;">
                        <p><strong>Nombre de la empresa: </strong>{{$ficha->experienciaPrevia->nombre_empresa}}</p>
                    </td>
                    <td style="width: 50%;">
                        <p><strong>Asegurado al IESS: </strong>{{$ficha->experienciaPrevia->asegurado_iess?'SI':'NO'}}
                        </p>
                    </td>
                </tr>
                <tr>
                    <td style="width: 50%;">
                        <p><strong>Cargo: </strong>{{$ficha->experienciaPrevia->cargo}}</p>
                    </td>
                    <td style="width: 50%;">
                        <p><strong>Antiguedad: </strong>{{$ficha->experienciaPrevia->antiguedad}}</p>
                    </td>
                </tr>
                <tr>
                    <td style="width: 50%;">
                        <p><strong>Teléfonos: </strong>{{$ficha->experienciaPrevia->telefono}}</p>
                    </td>
                    <td style="width: 50%;">
                        <p><strong>Fecha Retiro: </strong>{{$ficha->experienciaPrevia->fecha_retiro}}</p>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <p><strong>Motivo de Retiro: </strong>{{$ficha->experienciaPrevia->motivo_retiro}}</p>
                    </td>
                </tr>

            </table>

        @else
            <p style="background-color: #dde7f3">El empleado no tiene experiencia previa o último empleo. </p>
        @endif

        <p></p>
        <p><strong>5). INFORMACIÓN DE LA VIVIENDA</strong></p>
        <table class="custom-table" style="width: 100%;border: 1px solid #000">
            <tr style="font-weight: bold">
                <td style="width: 25%;">Tenencia</td>
                <td style="width: 25%;">Mat. Techo</td>
                <td style="width: 25%;">Mat. Piso</td>
                <td style="width: 25%;">Mat. Paredes</td>
            </tr>
            <tr>
                <td style="width: 25%;">
                    <p>{{$ficha->vivienda->tipo}}</p>
                </td>
                <td style="width: 25%;">
                    <p>{{$ficha->vivienda->material_techo}}</p>
                </td>
                <td style="width: 25%;">
                    <p>{{$ficha->vivienda->material_piso}}</p>
                </td>
                <td style="width: 25%;">
                    <p>{{$ficha->vivienda->material_paredes}}</p>
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
                <td style="width: 33%;"><p>{{$ficha->vivienda->servicios_basicos['luz']}}</p></td>
                <td style="width: 33%;"><p>{{$ficha->vivienda->servicios_basicos['agua']}}</p></td>
                <td style="width: 33%;"><p>{{$ficha->vivienda->servicios_basicos['telefono']}}</p></td>
            </tr>
            <tr style="font-weight: bold">
                <td style="width: 33%;">Internet</td>
                <td style="width: 33%;">Cable</td>
                <td style="width: 33%;">Servicios Sanitarios</td>
            </tr>
            <tr>
                <td style="width: 33%;"><p>{{$ficha->vivienda->servicios_basicos['internet']}}</p></td>
                <td style="width: 33%;"><p>{{$ficha->vivienda->servicios_basicos['cable']}}</p></td>
                <td style="width: 33%;"><p>{{$ficha->vivienda->servicios_basicos['servicios_sanitarios']}}</p></td>
            </tr>
        </table>
        <table style="width: 100%;">
            <tr>
                <td style="width: 100%">
                    <p><strong>Distribución de la vivienda: </strong>
                        {{ implode(', ', $ficha->vivienda->distribucion_vivienda)}}
                    </p>
                </td>
            </tr>
            <tr>
                <td style="width: 100%">
                    <p><strong>Considera que el espacio donde convive con su grupo familiar es: </strong>
                        {{  $ficha->vivienda->comodidad_espacio_familiar}}
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
                <td style="width: 33%">{{  $ficha->vivienda->numero_dormitorios}}</td>
                <td style="width: 33%">{{  $ficha->vivienda->existe_hacinamiento?'SI':'NO'}}</td>
                <td style="width: 33%">{{  $ficha->vivienda->existe_upc_cercano?'SI':'NO'}}</td>
            </tr>
        </table>

        <p></p>
        <p><strong>6). SITUACIÓN SOCIOECONÓMICA</strong></p>
        <table style="width: 100%;">
            <tr>
                <td colspan="2">
                    <p><strong>N° personas que aportan en el
                            hogar: </strong>{{$ficha->situacionSocioeconomica->cantidad_personas_aportan}}</p>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <p><strong>N° personas que dependen económicamente de
                            usted: </strong>{{$ficha->situacionSocioeconomica->cantidad_personas_dependientes}}</p>
                </td>
            </tr>
            <tr>
                @if($ficha->situacionSocioeconomica->recibe_apoyo_economico_otro_familiar)
                    <td style="width: 50%;">
                        <p><strong>¿Recibe apoyo económico de algún familiar o
                                amigo?: </strong>{{$ficha->situacionSocioeconomica->recibe_apoyo_economico_otro_familiar?'SI':'NO'}}
                        </p>
                    </td>
                    <td style="width: 50%;">
                        <p>
                            <strong>Especifique: </strong>{{$ficha->situacionSocioeconomica->familiar_apoya_economicamente}}
                        </p>
                    </td>
                @else
                    <td colspan="2">
                        <p><strong>¿Recibe apoyo económico de algún familiar o
                                amigo?: </strong>{{$ficha->situacionSocioeconomica->recibe_apoyo_economico_otro_familiar?'SI':'NO'}}
                        </p>
                    </td>
                @endif
            </tr>
            <tr>
                @if($ficha->situacionSocioeconomica->recibe_apoyo_economico_gobierno)
                    <td style="width: 50%;">
                        <p><strong>¿Recibe apoyo económico de alguna institución
                                gubernamental?: </strong>{{$ficha->situacionSocioeconomica->recibe_apoyo_economico_gobierno?'SI':'NO'}}
                        </p>
                    </td>
                    <td style="width: 50%;">
                        <p>
                            <strong>Especifique: </strong>{{$ficha->situacionSocioeconomica->institucion_apoya_economicamente}}
                        </p>
                    </td>
                @else
                    <td colspan="2">
                        <p><strong>¿Recibe apoyo económico de alguna institución
                                gubernamental?: </strong>{{$ficha->situacionSocioeconomica->recibe_apoyo_economico_gobierno?'SI':'NO'}}
                        </p>
                    </td>
                @endif
            </tr>
            @if($ficha->situacionSocioeconomica->tiene_prestamos)
                <tr>
                    <td style="width: 50%;">
                        <p><strong>Tiene
                                préstamos: </strong>{{$ficha->situacionSocioeconomica->tiene_prestamos?'SI':'NO'}}</p>
                    </td>
                    <td style="width: 50%;">
                        <p><strong>Cantidad préstamos: </strong>{{$ficha->situacionSocioeconomica->cantidad_prestamos}}
                        </p>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <p><strong>Entidad/es Bancaria/s: </strong>{{$ficha->situacionSocioeconomica->entidad_bancaria}}
                        </p>
                    </td>
                </tr>
            @else
                <tr>
                    <td colspan="2">
                        <p><strong>Tiene
                                préstamos: </strong>{{$ficha->situacionSocioeconomica->tiene_prestamos?'SI':'NO'}}</p>
                    </td>
                </tr>
            @endif

            @if($ficha->situacionSocioeconomica->tiene_tarjeta_credito)
                <tr>
                    <td style="width: 50%;">
                        <p><strong>Tiene tarjetas de
                                crédito: </strong>{{$ficha->situacionSocioeconomica->tiene_tarjeta_credito?'SI':'NO'}}
                        </p>
                    </td>
                    <td style="width: 50%;">
                        <p><strong>Cantidad
                                tarjetas: </strong>{{$ficha->situacionSocioeconomica->cantidad_tarjetas_credito}}
                        </p>
                    </td>
                </tr>
            @else
                <tr>
                    <td colspan="2">
                        <p><strong>Tiene tarjetas de
                                crédito: </strong>{{$ficha->situacionSocioeconomica->tiene_tarjeta_credito?'SI':'NO'}}
                        </p>
                    </td>
                </tr>
            @endif
            <tr>
                <td style="width: 50%;">
                    <p><strong>Vehículo: </strong>{{$ficha->situacionSocioeconomica->vehiculo}}</p>
                </td>
                <td style="width: 50%;">
                    <p><strong>Tiene terreno/s: </strong>{{$ficha->situacionSocioeconomica->tiene_terreno?'SI':'NO'}}
                    </p>
                </td>
            </tr>

            <tr>
                <td style="width: 50%;">
                    <p><strong>Bienes: </strong>{{$ficha->situacionSocioeconomica->tiene_bienes?'SI':'NO'}}</p>
                </td>
                <td style="width: 50%;">
                    <p><strong>Tiene ingresos
                            adicionales: </strong>{{$ficha->situacionSocioeconomica->tiene_ingresos_adicionales?'SI':'NO'}}
                    </p>
                </td>
            </tr>
            @if($ficha->situacionSocioeconomica->tiene_ingresos_adicionales)
                <tr>
                    <td colspan="2">
                        <p><strong>Ingresos
                                adicionales: </strong>{{$ficha->situacionSocioeconomica->ingresos_adicionales}}
                        </p>
                    </td>
                </tr>
            @endif

            <tr>
                <td colspan="2">
                    <p><strong>¿Apoya económicamente a algún
                            familiar? </strong>{{$ficha->situacionSocioeconomica->apoya_familiar_externo?'SI':'NO'}}
                    </p>
                </td>
            </tr>
            @if($ficha->situacionSocioeconomica->apoya_familiar_externo)
                <tr>
                    <td style="width: 50%;">
                        <p><strong>Familiar al que
                                apoya: </strong>{{$ficha->situacionSocioeconomica->familiar_externo_apoyado}}
                        </p>
                    </td>
                </tr>
            @endif

        </table>
        <p></p>
        <p><strong>7). SITUACIÓN SOCIOFAMILIAR</strong></p>
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
            @foreach($ficha->composicionFamiliar as $composicion)
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
        <p><strong>8). SALUD </strong></p>
        <table style="width: 100%;">
            <tr>
                <td style="width: 100%;">
                    <p><strong>¿Tiene discapacidad? </strong>{{!!$ficha->salud->discapacidades?'SI':'NO'}}</p>
                </td>
            </tr>
            @if(count($ficha->salud->discapacidades)>0)
                @foreach($ficha->salud->discapacidades as $discapacidad)
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
            @if(!!$ficha->salud->enfermedad_cronica)
                <tr>
                    <td style="width: 50%;">
                        <p><strong>¿Sufre alguna enfermedad
                                crónica? </strong>{{!!$ficha->salud->enfermedad_cronica?'SI':'NO'}}</p>
                    </td>
                    <td style="width: 50%;">
                        <p><strong>Indique enfermedad: </strong>{{$ficha->salud->enfermedad_cronica}}
                        </p>
                    </td>
                </tr>
            @else
                <tr>
                    <td colspan="2">
                        <p><strong>¿Sufre alguna enfermedad
                                crónica? </strong>{{!!$ficha->salud->enfermedad_cronica?'SI':'NO'}}</p>
                    </td>
                </tr>
            @endif
            <tr>
                <td style="width: 50%;">
                    <p><strong>Alergias: </strong>{{$ficha->salud->alergias}}</p>
                </td>
                <td style="width: 50%;">
                    <p><strong>Lugar de atención: </strong>{{$ficha->salud->lugar_atencion}}</p>
                </td>
            </tr>
            <tr>
                <td style="width: 100%;">
                    <p><strong>¿Tiene familiar dependiente con
                            discapacidad? </strong>{{!!$ficha->salud->discapacidades_familiar_dependiente?'SI':'NO'}}
                    </p>
                </td>
            </tr>
            @if(count($ficha->salud->discapacidades_familiar_dependiente)>0)
                <tr>
                    <td style="width: 50%;">
                        <p><strong>Nombre: </strong>{{$ficha->salud->nombre_familiar_dependiente_discapacitado}}</p>
                    </td>
                    <td style="width: 50%;">
                        <p><strong>Parentesco: </strong>{{$ficha->salud->parentesco_familiar_discapacitado}}</p>
                    </td>
                </tr>
                @foreach($ficha->salud->discapacidades_familiar_dependiente as $discapacidad)
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
        <p><strong>9). AMBIENTE SOCIAL O FAMILIAR</strong></p>
        <p>Problemas evidenciados o existentes en su entorno:
            <strong>{{ implode(', ', $ficha->problemas_ambiente_social_familiar)}}</strong></p>


        <p></p>
        <p><strong>10). CAPACITACIONES Y CONOCIMIENTOS</strong></p>
        <p>¿Ha recibido capacitaciones?
            <strong>{{ !!($ficha->capacitaciones || $ficha->conocimientos)?'SI':'NO'}}</strong></p>
        <table style="width: 100%;">
            <tr>
                <td style="width: 50%; font-weight: bold">Capacitaciones</td>
                <td style="width: 50%; font-weight: bold">Conocimientos</td>
            </tr>
            <tr>
                <td style="width: 50%; line-height: normal">
                    <ul>@foreach($ficha->capacitaciones as $capacitacion)
                            <li>{{$capacitacion}}</li>
                        @endforeach
                    </ul>
                </td>
                <td style="width: 50%; line-height: normal">
                    <ul>@foreach($ficha->conocimientos as $conocimiento)
                            <li>{{$conocimiento}}</li>
                        @endforeach
                    </ul>
                </td>
            </tr>
        </table>

        <p></p>
        <p><strong>11). CROQUIS</strong></p>
        <p>Coordenadas: <strong>{{ $ficha->vivienda->coordenadas }}</strong></p>
        <p>Dirección: <strong>{{ $ficha->vivienda->direccion }}</strong></p>
        <p>Referencia: <strong>{{ $ficha->vivienda->referencia }}</strong></p>

        @if(file_exists(public_path($ficha->vivienda->imagen_croquis)))
            <img src="{{ url($ficha->vivienda->imagen_croquis) }}" width="100%" height="200" alt="Croquis"/>
        @else
            <p>No hay imagen de croquis</p>
        @endif

        <p></p>
        <p><strong>12). RUTAGRAMA Y VIAS DE ACCESO </strong></p>
        <p>Vías de tránsito regular al trabajo: <strong>{{ $ficha->vias_transito_regular_trabajo }}</strong></p>

        @if(file_exists(public_path($ficha->imagen_rutagrama)))
            <img src="{{ url($ficha->imagen_rutagrama) }}" width="100%" height="200" alt="Rutagrama"/>
        @else
            <p>No hay imagen de rutagrama</p>
        @endif

        <p></p>
        <p><strong>13). CONCLUSIONES </strong></p>
        <p>{{ $ficha->conclusiones }}</p>


        <br><br><br><br>
        <p style="text-align: center"><strong>PARA USO EXCLUSIVO DE LA EMPRESA</strong></p>
        <p>Certifico que la información proporcionada fue verificada físicamente y corresponde a la verdad en lo que
            respecta al alcance de mi conocimiento.</p>
        <p><strong>Responsable</strong></p>
        <p><strong>Nombres y Apellidos: </strong> {{$departamento_trabajo_social?->responsable?->nombres}} {{$departamento_trabajo_social?->responsable?->apellidos}}</p>
        <p><strong>Identificación: </strong> {{$departamento_trabajo_social?->responsable?->identificacion}}</p>
        <br><br><br><br>

        <table class="firma" style="width: 100%;">
            <thead style="text-align: center">
            <th>
                @if(file_exists(public_path($ficha->empleado->firma_url)))
                    <img src="{{ url($ficha->empleado->firma_url) }}" width="100%" height="50"
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
                <td style="line-height: normal"><b>EMPLEADO</b></td>
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
