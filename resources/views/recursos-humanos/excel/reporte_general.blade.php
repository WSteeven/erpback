<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Rol de Pagos</title>
    <style>
        /* Estilo para la tabla con clase "cuerpo" */
        table.cuerpo {
            border: #b2b2b2 1px solid;
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
            text-align: left;
        }

        .encabezado-tabla-rol {
            text-align: center;
        }

        .totales {
            text-align: right;
        }
    </style>
</head>

<body>
    <table align="center" class="principal" width="90%">
        <tr>
            <td width="15%"></td>
            <td width="85%" colspan="2">
                <p class="encabezado-rol"> <strong>JP CONSTRUCRED C.LTDA</strong></p>
                <p class="encabezado-rol"><strong>RUC 0993375739001</strong></p>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <div class="col-md-7" align="center"><b> {{ $rolPago['nombre'] }}</b></div>
            </td>

        </tr>

    </table>
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

</body>

</html>
