<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Rol de Pagos</title>
    <style>
        .principal {
            border-collapse: separate;
            border-spacing: 30px 10px;
        }

        /* Estilo para la tabla con clase "cuerpo" */
        table.cuerpo {
            border: #b2b2b200 1px solid;
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

        footer {
            text-align: center;
        }

        .firma {
            width: 100%;
            line-height: normal;
            font-size: 16px;
            padding-top: 15%;
        }
    </style>
</head>

<body>
    @php
        $rol_pago = $roles_pago[0];
    @endphp
    <table align="center" class="principal" width="90%">
        <tr>
            <td width="15%"><img
                    src="{{ 'data:image/png;base64,' . base64_encode(file_get_contents('img/logoJP.png')) }}"
                    width="90"></td>
            <td width="85%" colspan="2">
                <p class="encabezado-rol"> <strong>JP CONSTRUCRED C.LTDA</strong></p>
                <p class="encabezado-rol"><strong>RUC 0993375739001</strong></p>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <h3 class="subtitulo-rol">Rol de Pagos de {{ $rol_pago['mes'] }}</h3>
            </td>

        </tr>

    </table>
    <table align="center" class="cuerpo">
        <tr>
            <td><strong>NOMBRES:</strong>{{ $rol_pago['empleado_info'] }} </td>
            <td><strong>CARGO: </strong>{{ $rol_pago['cargo'] }}</td>

        </tr>
        <tr>
            <td class="encabezado-tabla-rol"><strong>INGRESOS</strong> </td>
            <td class="encabezado-tabla-rol"><strong>DESCUENTOS</strong></td>
        </tr>
        <tr>
            <td>
                <table class="descripcion">
                    <tr>
                        <td>Días Laborado</td>

                        <td>
                            {{ $rol_pago['dias_laborados'] }}
                        </td>
                    </tr>
                    <tr>
                        <td>Sueldo</td>

                        <td>
                            {{ $rol_pago['sueldo'] }}
                        </td>
                    </tr>
                    <tr>
                        <td>Décimo Tercero</td>

                        <td>
                            {{ $rol_pago['decimo_tercero'] }}
                        </td>
                    </tr>
                    <tr>
                        <td>Décimo Cuarto</td>

                        <td>
                            {{ $rol_pago['decimo_cuarto'] }}
                        </td>
                    </tr>
                    @if($rol_pago['bonificacion'] != null)
                    <tr>
                        <td>Bono</td>

                        <td>
                            {{ $rol_pago['bonificacion'] }}
                        </td>
                    </tr>
                    @endif
                    @if( $rol_pago['bono_recurente'] != null)
                    <tr>
                        <td>Bono Recurente</td>

                        <td>
                            {{ $rol_pago['bono_recurente'] }}
                        </td>
                    </tr>
                    @endif
                    @if($rol_pago['fondos_reserva']  != 0)
                    <tr>
                        <td>Fondos de Reserva</td>

                        <td>
                            {{ $rol_pago['fondos_reserva'] }}
                        </td>
                    </tr>
                    @endif

                    @foreach ($rol_pago['ingresos'] as $ingreso)
                        <tr>
                            <td>
                                {{ $ingreso->concepto_ingreso_info->nombre }}
                            </td>

                            <td>
                                {{ $ingreso->monto }}
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
                            {{ $rol_pago['iess'] }}
                        </td>
                    </tr>
                    <tr>
                        <td>Anticipo Quincena</td>

                        <td>
                            {{ $rol_pago['anticipo'] }}
                        </td>
                    </tr>
                    <tr>
                        <td>Prestamo Quirorafario</td>

                        <td>
                            {{ $rol_pago['prestamo_quirorafario'] }}
                        </td>
                    </tr>
                    <tr>
                        <td>Prestamo Hipotecario</td>

                        <td>
                            {{ $rol_pago['prestamo_hipotecario'] }}
                        </td>
                    </tr>
                    <tr>
                        <td>Extension de Salud</td>

                        <td>
                            {{ $rol_pago['extension_conyugal'] }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Prestamo Empresarial
                        </td>

                        <td>
                            {{ $rol_pago['prestamo_empresarial'] }}
                        </td>
                    </tr>
                    @foreach ($rol_pago['egresos'] as $descuento)
                        <tr>
                            <td>
                                {{ $descuento->descuento->nombre }}
                            </td>

                            <td>
                                {{ $descuento->monto }}
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td>SUPA</td>

                        <td>
                            {{ $rol_pago['supa'] }}
                        </td>
                    </tr>
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
                            {{ $rol_pago['total_ingreso'] }}
                        </td>
                    </tr>
                </table>
            </td>
            <td>
                <table class="descripcion">
                    <tr>
                        <td>TOTAL EGRESOS</td>
                        <td class="totales">
                            {{ $rol_pago['total_egreso'] }}
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
                            {{ $rol_pago['total'] }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <footer>
        <table class="firma" style="width: 100%;">
            <thead>
                <th align="center">
                    __________________________________________<br />
                    <b>{{ $rol_pago['empleado_info'] }}</b>
                    <br>
                    <b>{{ $rol_pago['identificacion_empleado'] }}</b>
                </th>
                <th align="center"></th>
                <th align="center">
                    __________________________________________<br />
                    <b>ING. LUIS MANUEL PEZANTEZ MORA</b>
                    <br>
                    <b>APROBADO POR</b>
                </th>
            </thead>

        </table>
        <p>Este Rol de pago es fiel copia del original que reposa en Contabilidad</p>
    </footer>

</body>

</html>
