<!DOCTYPE html>
<html lang="es">
{{-- Aquí codigo PHP --}}
@php
    $fecha = new Datetime();
    $logo_principal = 'data:image/png;base64,' . base64_encode(file_get_contents(public_path() . $configuracion['logo_claro']));
    $logo_watermark = 'data:image/png;base64,' . base64_encode(file_get_contents(public_path() . $configuracion['logo_marca_agua']));
@endphp

<head>
    <meta charset="utf-8">
    <title>Rol de Pagos</title>
    <style>
        @page {
            margin: 0cm 15px;
        }

        header {
            top: 0cm;
            left: 0cm;
            right: 0cm;
            height: 4cm;

            /** Estilos extra personales **/
            text-align: center;
            line-height: 42%;
        }

        body {
            background-image: url({{ $logo_watermark }});
            background-size: 50% auto;
            background-repeat: no-repeat;
            background-position: center;
        }

        /** Definir las reglas del encabezado **/




        div {
            color: #000000 !important;
        }

        h1 {
            text-align: center;
            text-transform: uppercase;
        }

        /* Estilo para la tabla con clase "cuerpo" */
        table.cuerpo {
            border: #b2b2b200 1px solid;
            font-size: 10pt;
            margin-top: -1.05cm;

        }

        .cuerpo td,
        .cuerpo th {
            border: black 1px solid;
        }

        table.descripcion {
            width: 100%;
        }

        .descripcion td,
        descripcion th {
            border: none;
        }

        .subtitulo-rol {
            text-align: center;
        }

        .encabezado-rol {
            text-align: center;
        }

        .encabezado-tabla-rol {
            text-align: center;
        }

        .totales {
            text-align: right;
        }

        /** Definir las reglas del pie de página **/
        footer {
            position: fixed;
            bottom: 90px;
            font-size: 7pt;
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

        .firma {
            table-layout: fixed;
            width: 100%;
            line-height: normal;
            font-size: 7pt;
            padding-top: 7%;
        }


        .row {
            width: 100%;
        }
    </style>
</head>


<body>
    <header>
        <table
            style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10pt;">
            <tr class="row" style="width:auto">
                <td>
                    <div class="col-md-3"><img src="{{ $logo_principal }}" width="90"></div>
                </td>
                <td>
                    <p class="encabezado-rol"> <strong>JP CONSTRUCRED C.LTDA</strong></p><br>
                    <p class="encabezado-rol"><strong>RUC 0993375739001</strong></p><br>
                    <div class="col-md-7" align="center"><b> {{ $rolPago['nombre'] }}</b></div>

                </td>
                <td>

                </td>

            </tr>
        </table>
        <hr>
    </header>

    <!-- aqui va el contenido del document<br><br>o -->
    <main>
        <table align="center" class="cuerpo">
            <tr>
                <td class="encabezado-tabla-rol"><strong>INGRESOS</strong> </td>
                <td class="encabezado-tabla-rol"><strong>DESCUENTOS</strong></td>
            </tr>
            <tr>
                <td>
                    <table class="descripcion">
                        <tr>
                            <td>Décimo Tercero</td>

                            <td>
                                {{ $reporte['decimo_tercero'] }}
                            </td>
                        </tr>
                        <tr>
                            <td>Décimo Cuarto</td>

                            <td>
                                {{ $reporte['decimo_cuarto'] }}
                            </td>
                        </tr>
                        @if ($reporte['bonificacion'] != null)
                            <tr>
                                <td>Bono</td>

                                <td>
                                    {{ $reporte['bonificacion'] }}
                                </td>
                            </tr>
                        @endif
                        @if ($reporte['bono_recurente'] != null)
                            <tr>
                                <td>Bono Recurente</td>

                                <td>
                                    {{ $reporte ['bono_recurente'] }}
                                </td>
                            </tr>
                        @endif
                        @if ($reporte['fondos_reserva'] != 0)
                            <tr>
                                <td>Fondos de Reserva</td>

                                <td>
                                    {{ $reporte['fondos_reserva'] }}
                                </td>
                            </tr>
                        @endif
                        @foreach ($ingresos as $key_ingreso => $ingreso_value)
                        <tr>
                            <td>
                                {{ $key_ingreso }}
                            </td>

                            <td>
                                {{ $ingreso_value }}
                            </td>
                        </tr>
                    @endforeach
                    </table>
                </td>

                <td>
                    <table class="descripcion">
                        <tr>
                            <td>IESS (9.45%)</td>

                            <td>
                                {{ $reporte['iess'] }}
                            </td>
                        </tr>
                        <tr>
                            <td>Anticipo Quincena</td>

                            <td>
                                {{ $reporte['anticipo'] }}
                            </td>
                        </tr>
                        @if ($reporte['prestamo_quirorafario'] > 0)
                            <tr>
                                <td>Prestamo Quirorafario</td>

                                <td>
                                    {{ $reporte['prestamo_quirorafario'] }}
                                </td>
                            </tr>
                        @endif
                        @if ($reporte['prestamo_hipotecario'] > 0)
                            <tr>
                                <td>Prestamo Hipotecario</td>

                                <td>
                                    {{ $reporte['prestamo_hipotecario'] }}
                                </td>
                            </tr>
                        @endif
                        @if ($reporte['extension_conyugal'] > 0)
                            <tr>
                                <td>Extension de Salud</td>

                                <td>
                                    {{ $reporte['extension_conyugal'] }}
                                </td>
                            </tr>
                        @endif
                        @if ($reporte['prestamo_empresarial'] > 0)
                            <tr>
                                <td>
                                    Prestamo Empresarial
                                </td>

                                <td>
                                    {{ $reporte['prestamo_empresarial'] }}
                                </td>
                            </tr>
                        @endif
                        @foreach ($egresos as $key_egreso => $egreso_value)
                        <tr>
                            <td>
                                {{ $key_egreso }}
                            </td>

                            <td>
                                {{ $egreso_value }}
                            </td>
                        </tr>
                    @endforeach

                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <table class="descripcion">
                        <tr>
                            <td>
                                TOTAL INGRESOS
                            </td>
                            <td class="totales">
                                {{ $reporte['total_ingreso'] }}
                            </td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="descripcion">
                        <tr>
                            <td>TOTAL EGRESOS</td>
                            <td class="totales">
                                {{ $reporte['total_egreso'] }}
                            </td>
                        </tr>

                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <table class="descripcion">
                        <tr>
                            <td>
                                NETO A RECIBIR
                            </td>
                            <td class="totales">
                                {{ $reporte['total'] }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </main>


</body>

</html>
