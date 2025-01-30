<!DOCTYPE html>
<html lang="es">

@php
    use Carbon\Carbon;
    use Src\Shared\Utils;

    $fecha = Carbon::now();
    $fecha_creacion = Carbon::parse($ficha->created_at)->format('Y-m-d');
@endphp

<head>
    <meta charset="utf-8">
    <title>FICHA DE EVALUACION DE RIESGO ANTE EVENTOS ADVERSOS (TERREMOTOS - INUNDACIONES - DESLAVES - DERRUMBES -
        ERUPCIONES VOLCANICAS - LAHARES) PARA EL PERSONAL DE JP CONSTRUCRED. C.LTDA.</title>
    <style>
        @page {
            margin: 0 15px;
        }

        body {
            background-image: url({{ Utils::urlToBase64(url($configuracion->logo_marca_agua)) }});
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

        .custom-table {
            border: 1px solid black;
            border-collapse: collapse;
        }

        .custom-table th { /*Colocar en negrita los encabezados*/
            font-weight: bold;
            border: 1px solid #000;
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
                    <img src="{{ Utils::urlToBase64(url($configuracion->logo_claro)) }}" width="90" alt="Logo">
                </div>
            </td>
            <td style="width: 68%">
                <div style="text-align: center"><b>EVALUACION DE RIESGOS</b>
                </div>
            </td>
            <td style="width: 22%">
                <div style="text-align: center"><b>FOR FIRSTRED 006 <br> 15 01 2025 </b></div>
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
        <p><strong>I). UBICACIÓN GEOGRÁFICA</strong></p>
        <table class="custom-table">
            <tr>
                <td>Provincia</td>
                <td>{{$ficha->empleado->canton->provincia->provincia}}</td>
                <td>Cantón</td>
                <td>{{$ficha->empleado->canton->canton}}</td>
            </tr>
            <tr>
                <td>Dirección exacta de su domicilio</td>
                <td colspan="3">{{$ficha->vivienda->direccion}}</td>
            </tr>
            <tr>
                <td>Coordenadas</td>
                <td>{{$ficha->vivienda->coordenadas}}</td>
                <td>Referencia</td>
                <td>{{$ficha->vivienda->referencia}}</td>
            </tr>
            {{--            <tr>--}}
            {{--                <td>Observaciones</td>--}}
            {{--                <td colspan="3">{{$ficha->conclusiones}}</td>--}}
            {{--            </tr>--}}
        </table>
        <br><br>
        <p><strong>II). POSIBLES AMENAZAS EN EL SECTOR</strong></p>
        <table class="custom-table">
            <tr>
                <th>Inundaciones</th>
                <th>Deslaves</th>
                <th>Otras amenazas previstas</th>
            </tr>
            <tr>
                @if($ficha->vivienda->amenaza_inundacion)
                    <td style="width: 33%; line-height: normal">
                        <ul>@foreach($ficha->vivienda->amenaza_inundacion as $amenaza)
                                <li>{{$amenaza}}</li>
                            @endforeach
                        </ul>
                    </td>
                @endif

                @if($ficha->vivienda->amenaza_deslaves)
                    <td style="width: 33%; line-height: normal">
                        <ul>@foreach($ficha->vivienda->amenaza_deslaves as $amenaza)
                                <li>{{$amenaza}}</li>
                            @endforeach
                        </ul>
                    </td>
                @endif

                @if($ficha->vivienda->otras_amenazas_previstas)
                    <td style="width: 33%; line-height: normal">
                        <ul>@foreach($ficha->vivienda->otras_amenazas_previstas as $amenaza)
                                <li>{{$amenaza}}</li>
                            @endforeach
                        </ul>
                    </td>
                @endif
            </tr>
            <tr>
                <td>Otras amenazas:</td>
                <td colspan="2">{{$ficha->vivienda->otras_amenazas}}</td>
            </tr>
            <tr>
                <td colspan="3">¿En caso de terremoto podría ocurrir
                    TSUNAMI? <strong>{{$ficha->vivienda->existe_peligro_tsunami?'SI':'NO'}}</strong></td>
            </tr>
            <tr>
                <td colspan="3">¿En caso de erupción volcanica existe peligro de
                    LAHARES? <strong> {{$ficha->vivienda->existe_peligro_lahares?'SI':'NO'}}</strong></td>
            </tr>

        </table>
        <br><br>
        <p><strong>III). DATOS PERSONALES</strong></p>
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
                <td style="width: 33%; padding: 2px; line-height: normal">
                    <strong>Nombre: </strong>{{ $ficha->contacto_emergencia }}
                </td>
                <td style="width: 33%; padding: 2px;line-height: normal">
                    <strong>Parentesco: </strong>{{ $ficha->parentesco_contacto_emergencia }}
                </td>
                <td style="width: 33%; padding: 2px;line-height: normal">
                    <strong>Teléfono: </strong>{{ $ficha->telefono_contacto_emergencia }}
                </td>
            </tr>
        </table>
        <br>
        <table style="width: 100%; border: 1px solid #000; border-radius: 5px;">
            <tr>
                <td colspan="3">
                    <p>Contacto que no vive con usted:</p>
                </td>
            </tr>
            <tr>
                <td style="width: 33%; padding: 2px; line-height: normal">
                    <strong>Nombre: </strong>{{ $ficha->contacto_emergencia_externo }}
                </td>
                <td style="width: 33%; padding: 2px;line-height: normal">
                    <strong>Parentesco: </strong>{{ $ficha->parentesco_contacto_emergencia_externo }}
                </td>
            </tr>
            <tr>
                <td style="width: 33%; padding: 2px;line-height: normal">
                    <strong>Teléfono: </strong>{{ $ficha->telefono_contacto_emergencia_externo }}
                </td>
                <td style="width: 33%; padding: 2px;line-height: normal">
                    <strong>Ciudad: </strong>{{ $ficha->ciudadContactoExterno?->canton }}
                </td>
            </tr>
        </table>
        <p><strong>Número de personas que viven en su hogar: </strong>{{$ficha->vivienda->numero_personas?:1}} </p>
        <table class="custom-table" style="width: 100%;">

            <tr style="font-weight: bold">
                <td>Nombres y Apellidos</td>
                <td>Parentesco</td>
                <td>Edad</td>
                <td>Estado Civil</td>
                <td>Instrucción</td>
                <td>Ocupación/Profesión</td>
                <td>Discapacidad</td>
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
                </tr>
            @endforeach
        </table>
        <br><br>
        <p><strong>IV). DATOS DE LA VIVIENDA</strong></p>
        <table class="custom-table" style="width: 100%;border: 1px solid #000">
            <tr style="font-weight: bold">
                <td style="width: 20%;">Tenencia</td>
                <td style="width: 20%;">N° Plantas</td>
                <td style="width: 20%;">Mat. Techo</td>
                <td style="width: 20%;">Mat. Piso</td>
                <td style="width: 20%;">Mat. Paredes</td>
            </tr>
            <tr>
                <td style="width: 20%;"><p>{{$ficha->vivienda->tipo}}</p></td>
                <td style="width: 20%;"><p>{{$ficha->vivienda->numero_plantas}}</p></td>
                <td style="width: 20%;"><p>{{$ficha->vivienda->material_techo}}</p></td>
                <td style="width: 20%;"><p>{{$ficha->vivienda->material_piso}}</p></td>
                <td style="width: 20%;"><p>{{$ficha->vivienda->material_paredes}}</p></td>
            </tr>
            <tr>
                <td colspan="5" style="width: 50%;">
                    <p><strong>¿En caso de evacuación tiene a donde
                            acudir?: </strong>{{$ficha->vivienda->tiene_donde_evacuar?'SI':'NO'}}
                    </p>
                </td>
            </tr>
        </table>
        <br><br>
        @if($ficha->vivienda->tiene_donde_evacuar)
            <p><strong>V). DATOS DE LA FAMILIA ACOGIENTE</strong></p>
            <table class="custom-table">
                <tr>
                    <td>Provincia</td>
                    <td>{{$ficha->vivienda->familiaAcogiente->canton->provincia->provincia}}</td>
                    <td>Cantón</td>
                    <td>{{$ficha->vivienda->familiaAcogiente->canton->canton}}</td>
                </tr>
                <tr>
                    <td>Dirección exacta de su domicilio</td>
                    <td colspan="3">{{$ficha->vivienda->familiaAcogiente->direccion}}</td>
                </tr>
                <tr>
                    <td>Coordenadas</td>
                    <td>{{$ficha->vivienda->familiaAcogiente->coordenadas}}</td>
                    <td>Referencia</td>
                    <td>{{$ficha->vivienda->familiaAcogiente->referencia}}</td>
                </tr>
                {{--            <tr>--}}
                {{--                <td>Observaciones</td>--}}
                {{--                <td colspan="3">{{$ficha->conclusiones}}</td>--}}
                {{--            </tr>--}}
            </table>

            <br><br><br><br>
            <p><strong>VI). REGISTRO FOTOGRAFICO DEL DOMICILIO</strong></p>
        @else
            <br><br>
            <p><strong>V). REGISTRO FOTOGRAFICO DEL DOMICILIO</strong></p>
        @endif
        @if($ficha->vivienda->imagen_croquis)
            <img src="{{ Utils::urlToBase64(url($ficha->vivienda->imagen_croquis)) }}" width="100%" height="200" alt="Croquis"/>
        @else
            <p>No hay imagen de croquis</p>
        @endif

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
