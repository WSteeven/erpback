<html>

<head>
    <style>
        body {
            font-family: sans-serif;
            background-image: url('img/logoJPBN_10.png');
            background-repeat: no-repeat;
            background-position: center;
        }

        @page {
            margin: 100px 25px;
        }

        header {
            position: fixed;
            left: 0px;
            top: -75px;
            right: 0px;
            height: 90px;
            text-align: center;
        }

        header h1 {
            margin: 5px 0;
        }

        header h2 {
            margin: 0 0 10px 0;
        }

        footer {
            position: fixed;
            left: 0px;
            bottom: -75px;
            right: 0px;
            height: 65px;
            margin-top: 0%;
            margin-bottom: 0%;
            font-size: 7pt;
        }

        .firma {
            table-layout: fixed;
            width: 75%;
            line-height: normal;
            font-size: 10pt;
            margin-top: 0%;
            margin-bottom: -20px;
            font-size: 7pt;
        }

        footer .page:after {
            content: counter(page);
        }

        footer table {
            width: 100%;
        }

        footer p {
            text-align: right;
        }

        .saldos_depositados {
            margin-top: -15px;
            table-layout: fixed;
            width: 100%;
            line-height: normal;
        }

        .gastos {
            table-layout: fixed;
            width: 100%;
            line-height: normal;
            font-size: 10pt;
        }
        .observacion
        {
            table-layout: fixed;
            width: 100%;
            line-height: normal;
            font-size: 7pt;
        }
        footer .izq {
            text-align: left;
        }
        .page-break {
        page-break-after: always;
    }
    </style>
    @php
        $fecha = new Datetime();
        $ciclo = [1, 2, 3, 4, 5, 6, 7, 8, 9, 0, 1, 2, 3, 4, 5];
    @endphp

<body>
    <header>
        <table
            style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:18px; ">
            <tr class="row" style="width:auto">
                <td style="width: 10%;">
                    <div class="col-md-3"><img src="/img/logoJP.png" width="90"></div>
                </td>
                <td style="width: 100%">
                    <div class="col-md-7" align="center"><b>REPORTE SEMANAL DE GASTOS DEL
                            {{  date("d/m/Y", strtotime( $fecha_inicio)) . ' AL ' .date("d/m/Y", strtotime($fecha_fin))  }}</b></div>

                </td>
            </tr>
        </table>
        <hr>
    </header>
    <footer>
        <table>
            <tr>
                <td>
                    <table class="firma" style="width: 100%;">
                        <thead>
                            <th align="center">______________________________________</th>
                            <th align="center"></th>
                            <th align="center">______________________________________</th>
                        </thead>
                        <tbody>
                            <tr align="center">
                                <td><b>RESPONSABLE DE MANEJO DE
                                        VIATICOS</b></td>
                                <td><b></b></td>
                                <td><b>RESPONSABLE CONTROL DE
                                        VIATICOS</b></td>
                            </tr>
                            <tr>
                                <td style="padding-left: 60px;">Nombre: </td>
                                <td></td>
                                <td style="padding-left: 60px;">Nombre:</td>
                            </tr>
                            <tr>
                                <td style="padding-left: 60px;">C.I: </td>
                                <td></td>
                                <td style="padding-left: 60px;">C.I:</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td>
                    <p class="izq">
                        Generado por:
                        {{ auth('sanctum')->user()->empleado->nombres }}
                        {{ auth('sanctum')->user()->empleado->apellidos }} el
                        {{ $fecha->format('d/m/Y H:i') }}
                        Propiedad de  JPCONSTRUCRED CIA LTDA - Proibida su distribucion
                    </p>
                </td>
                <td>
                    <p class="page">
                        Página
                    </p>
                </td>
            </tr>
        </table>
    </footer>
    <div id="content">
        <p  style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:15px;margin-top: -6px;>
            <div class="col-md-7" align="center"><b>{{ $datos_usuario_logueado['apellidos'] . ' ' . $datos_usuario_logueado['nombres'] }}</b></div>
        </p>
        <p>
        <table width="100%" border="1" align="left" cellpadding="0" cellspacing="0" class="saldos_depositados">
            <tr>
                <td colspan="4" style="font-size:10px" bgcolor="#a9d08e"><strong>SALDOS
                        DEPOSITADOS</strong></td>
            </tr>
            <tr>
                <td style="font-size:10px" width="8%"><strong>Fecha</strong></td>
                <td style="font-size:10px"width="7%"><strong>Monto</strong></td>
                <td style="font-size:10px"width="9%"><strong>Tipo Saldo</strong></td>
                <td style="font-size:10px" width="80%"><strong>Descripción</strong></td>
            </tr>
            @if (sizeof($datos_saldo_depositados_semana) > 0)
                @foreach ($datos_saldo_depositados_semana as $dato)
                    <tr>
                        <td style="font-size:10px">{{  date("d/m/Y", strtotime(  $dato->fecha)) }}</td>
                        <td style="font-size:10px">
                            {{ number_format($dato->monto, 2, ',', '.') }}</td>
                        <td style="font-size:10px">{{ $dato->tipo_fondo->descripcion }}
                        </td>
                        <td style="font-size:10px">{{ $dato->descripcion_saldo }}</td>
                    </tr>
                    @if ($datos_saldo_depositados_semana[count($datos_saldo_depositados_semana) - 1]->id != $dato->id)
                        <div class="page-break"></div>
                    @endif
                @endforeach
            @else
                <tr>
                    <td style="font-size:10px" colspan="4">NO SE REALIZARON DEPOSITOS.</td>
                </tr>
            @endif
            <tr>
                <td style="font-size:10px" colspan="3"><div align="right"><strong>SALDO ANTERIOR:&nbsp;</strong></div><strong></strong></td>
                <td style="font-size:10px"> <div align="right"> {{ number_format($sal_anterior, 2, ',', ' ') }} </div></td>
            </tr>
            <tr>
                <td colspan="3" style="font-size:10px">
                    <div align="right"><strong>SALDO DEPOSITADO:&nbsp;</strong></div>
                </td>
                <td style="font-size:10px">
                    <div align="right"> {{ number_format($sal_dep_r, 2, ',', ' ') }} </div>
                </td>
            </tr>
            <tr>
                <td colspan="3" style="font-size:10px">
                    <div align="right"><strong>NUEVO SALDO:&nbsp;</strong></div>
                </td>
                <td style="font-size:10px">
                    <div align="right"> {{ number_format($nuevo_saldo, 2, ',', ' ') }} </div>
                </td>
            </tr>
        </table>
        <div class="page-break"></div>
        </p>
        <p>
            <table width="100%" border="1" cellspacing="0" bordercolor="#666666"  class="gastos">
                <tr>
                    <td width="5%" bgcolor="#a9d08e">
                        <div align="center"><strong>N&deg;</strong></div>
                    </td>
                    <td width="15%" bgcolor="#a9d08e">
                        <div align="center"><strong>FECHA</strong></div>
                    </td>
                    <td width="17%" bgcolor="#a9d08e">
                        <div align="center"><strong>TAREA</strong></div>
                    </td>
                    <td width="20%" bgcolor="#a9d08e">
                        <div align="center"><strong># FACTURA</strong></div>
                    </td>
                    <td width="20%" bgcolor="#a9d08e">
                        <div align="center"><strong>RUC</strong></div>
                    </td>
                    <td width="35%" bgcolor="#a9d08e">
                        <div align="center"><strong>AUTORIZACION ESPECIAL</strong></div>
                    </td>
                    <td width="25%" bgcolor="#a9d08e">
                        <div align="center"><strong>DETALLE</strong></div>
                    </td>
                    <td width="25%" bgcolor="#a9d08e">
                        <div align="center"><strong>SUB DETALLE</strong></div>
                    </td>
                    <td width="24%"  bgcolor="#a9d08e">
                        <div align="center"><strong>OBSERVACI&Oacute;N</strong></div>
                    </td>
                    <td width="10%" bgcolor="#a9d08e">
                        <div align="center"><strong>CANT.</strong></div>
                    </td>
                    <td width="10%" bgcolor="#a9d08e">
                        <div align="center"><strong>V. UNI.</strong></div>
                    </td>
                    <td width="10%" bgcolor="#a9d08e">
                        <div align="center"><strong>TOTAL</strong></div>
                    </td>
                </tr>
                @if (sizeof($datos_reporte) == 0)
                    <tr>
                        <td colspan="12">
                            <div align="center">NO HAY FONDOS ROTATIVOS APROBADOS</div>
                        </td>
                    </tr>
                @else
                    @foreach ($datos_reporte as $dato)
                        @php
                            $sub_total = $sub_total + (float) $dato->total;
                        @endphp
                        <tr>
                            <td style="font-size:10px">
                                <div align="center">{{ $dato->id }}</div>
                            </td>
                            <td style="font-size:10px">
                                <div align="center">{{   date("d/m/Y", strtotime( $dato->fecha_viat))}}</div>
                            </td>
                            <td style="font-size:10px">
                                <div align="center">
                                    {{ $dato->tarea != null ? $dato->tareacodigo_tarea : 'Sin Tarea' }}
                                </div>
                            </td>
                            <td style="font-size:10px">
                                <div align="center">{{ $dato->factura }}</div>
                            </td>
                            <td style="font-size:10px">
                                <div align="center">{{ $dato->ruc }}</div>
                            </td>
                            <td style="font-size:10px">
                                <div align="center">
                                    {{ $dato->aut_especial_user->empleado->nombres . '' . $dato->aut_especial_user->empleado->apellidos }}
                                </div>
                            </td>
                            <td style="font-size:10px">
                                <div align="center">{{ $dato->detalle_info->descripcion}}</div>
                            </td>
                            <td style="font-size:10px">
                                <div align="center">  @foreach($dato->sub_detalle_info as $sub_detalle)
                                    {{ $sub_detalle->descripcion }}
                                    @if (!$loop->last)
                                       ,
                                    @endif
                                 @endforeach</div>
                            </td>
                            <td style="font-size:10px;word-wrap: break-word;">
                                <div align="center">{{ $dato->observacion }}</div>
                            </td>
                            <td style="font-size:10px">
                                <div align="center">{{ $dato->cantidad }}</div>
                            </td>
                            <td style="font-size:10px">
                                <div align="center">
                                    {{ number_format($dato->valor_unitario, 2, ',', '.') }}</div>
                            </td>
                            <td style="font-size:10px">
                                <div align="center">{{ number_format($dato->total, 2, ',', '.') }}
                                </div>
                            </td>
                        </tr>
                        @if ($datos_reporte[count($datos_reporte) - 1]->id != $dato->id)
                            <div class="page-break"></div>
                        @endif
                    @endforeach
                @endif
                <tr>
                    <td>&nbsp;</td>
                    <td colspan="10" style="font-size:10px">
                        <div align="right"><strong>SUB TOTAL:&nbsp;</strong></div>
                    </td>
                    <td style="font-size:10px">
                        <div align="center">{{ number_format($sub_total, 2, ',', ' ') }}</div>
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td colspan="10" style="font-size:10px">
                        <div align="right"><strong>TOTAL:&nbsp;</strong></div>
                    </td>
                    <td style="font-size:10px">
                        <div align="center">
                            {{ number_format($nuevo_saldo, 2, ',', ' ') }}
                        </div>
                    </td>
                </tr>
            </table>
        </p>
        <p>
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="observacion">
            <tr>
                <td>
                    <div align="left">
                        <strong>Observacion:&nbsp;</strong>
                        <ul>
                            <li>Todo gasto deber&aacute; registrarse&nbsp; y respaldarse con su
                                debido justificativo (FACTURA O
                                NOTA DE VENTA-RISE).</li>
                            <li>Toda factura deber&aacute; pertenecer a la semana en curso, (S&aacute;bado a
                                Viernes).&nbsp;
                            </li>
                            <li> Factura que se registre o se adjunte fuera de fecha no sera
                                reembolsada.</li>
                            <li> En la parte posterior de la factura deberan firmar quienes son
                                beneficiados con el servicio
                            </li>
                            <li> Los valores de las facturas ser&aacute;n acordes con los viaticos
                                diarios utilizados.</li>
                            <li> Las facturas deberan ser legalizadas (Firma Igual a C&eacute;dula )
                                por quienes consumen el
                                servicio</li>
                            <li> El Casillero Autorizacion Especial, es cuando el Coordinador ha
                                Autorizado un consumo
                                adicional, no es un consumo frecuente&nbsp;es decir: desayunos, meriendas,
                                compras de materiales
                                o consumos no programados. As&iacute; como tambien env&iacute;os y consumos
                                de combustible
                                solicitados por nuestro cliente.</li>
                        </ul>
                    </div>
                </td>
            </tr>
        </table>
        </p>
    </div>

</body>

</html>
