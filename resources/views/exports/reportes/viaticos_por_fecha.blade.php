<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte</title>
    <style>
        @page {
            margin: 100px 25px;
        }

        .header {
            position: fixed;
            top: -55px;
            left: 0px;
            right: 0px;
            height: 80px;
            text-align: center;
            line-height: 35px;
        }

        .footer {
            position: fixed;
            bottom: -50px;
            left: 0px;
            right: 0px;
            height: 50px;
            color: #333333;
            text-align: center;
            line-height: 35px;
            font-size: 10px;
            font-style: italic;
        }
    </style>
</head>

<body>

    <table
        style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px;margin-top: 20px;">
        <tr>
            <div class="header">
                <table
                    style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px;page-break-inside: avoid;">
                    <tr>
                        <td width="17%">
                            <div align="center"></div>
                        </td>
                        <td width="83%" style="font-size:16px; font-weight:bold">
                            <div align="center">JEAN PATRICIO PAZMI&Ntilde;O BARROS</div>
                        </td>
                    </tr>
                    <tr>
                        <td width="17%">
                            <div align="center"></div>
                        </td>
                        <td width="83%" style="font-size:16px; font-weight:bold">
                            <div align="center">RUC:0702875618001</div>
                        </td>
                    </tr>
                </table>
            </div>
        </tr>
        <tr>
            <td>
                <div align="center">
                    <table width="100%">
                        <tr>
                            <td bgcolor="#bfbfbf" style="font-size:12px">
                                <div align="center"><strong>REPORTE SEMANAL DE GASTOS DEL
                                        {{ $fecha_inicio . ' AL ' . $fecha_fin }}</strong></div>
                            </td>
                        </tr>
                        <tr>
                            <td bgcolor="#bfbfbf" style="font-size:14px">
                                <div align="center">
                                    <strong>{{ $datos_usuario_logueado['apellidos'] . ' ' . $datos_usuario_logueado['nombres'] }}</strong>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table width="100%" border="1" align="left" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td colspan="4" style="font-size:10px" bgcolor="#a9d08e"><strong>SALDOS
                                                DEPOSITADOS</strong></td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:10px"><strong>Fecha</strong></td>
                                        <td style="font-size:10px"><strong>Monto</strong></td>
                                        <td style="font-size:10px"><strong>Tipo Saldo</strong></td>
                                        <td style="font-size:10px"><strong>Descripción</strong></td>
                                    </tr>
                                    @if (sizeof($datos_saldo_depositados_semana) > 0)
                                        @foreach ($datos_saldo_depositados_semana as $dato)
                                            @php
                                                $tipo_fondo_descripcion = DB::table('tipo_fondo')
                                                    ->select('*')
                                                    ->where('id', '=', $dato->id_tipo_fondo)
                                                    ->get();
                                            @endphp
                                            <tr>
                                                <td style="font-size:10px">{{ $dato->fecha }}</td>
                                                <td style="font-size:10px">
                                                    {{ number_format($dato->saldo_depositado, 2, ',', '.') }}</td>
                                                <td style="font-size:10px">{{ $tipo_fondo_descripcion[0]->descripcion }}
                                                </td>
                                                <td style="font-size:10px">{{ $dato->descripcion_saldo }}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        @php
                                            $height = 8;
                                        @endphp
                                        <tr>
                                            <td style="font-size:10px" colspan="4">NO SE REALIZARON DEPOSITOS.</td>
                                        </tr>
                                    @endif

                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table width="100%" border="1" cellspacing="0" bordercolor="#666666"
                                    style="margin-top:8 ">
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td colspan="10" style="font-size:10px">
                                            <div align="right"><strong>SALDO ANTERIOR:</strong></div>
                                        </td>
                                        <td style="font-size:10px">
                                            <div align="center"> {{ number_format($sal_anterior, 2, ',', ' ') }} </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td colspan="10" style="font-size:10px">
                                            <div align="right"><strong>SALDO DEPOSITADO:&nbsp;</strong></div>
                                        </td>
                                        <td style="font-size:10px">
                                            <div align="center"> {{ number_format($sal_dep_r, 2, ',', ' ') }} </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td colspan="10" style="font-size:10px">
                                            <div align="right"><strong>NUEVO SALDO:&nbsp;</strong></div>
                                        </td>
                                        <td style="font-size:10px">
                                            <div align="center"> {{ number_format($nuevo_saldo, 2, ',', ' ') }} </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="2%" bgcolor="#a9d08e">
                                            <div align="center"><strong>N&deg;</strong></div>
                                        </td>
                                        <td width="5%" bgcolor="#a9d08e">
                                            <div align="center"><strong>FECHA</strong></div>
                                        </td>
                                        <td width="8%" bgcolor="#a9d08e">
                                            <div align="center"><strong>TAREA</strong></div>
                                        </td>
                                        <td width="10%" bgcolor="#a9d08e">
                                            <div align="center"><strong># FACTURA</strong></div>
                                        </td>
                                        <td width="10%" bgcolor="#a9d08e">
                                            <div align="center"><strong>RUC</strong></div>
                                        </td>
                                        <td width="10%" bgcolor="#a9d08e">
                                            <div align="center"><strong>AUTORIZACION ESPECIAL</strong></div>
                                        </td>
                                        <td width="8%" bgcolor="#a9d08e">
                                            <div align="center"><strong>DETALLE</strong></div>
                                        </td>
                                        <td width="8%" bgcolor="#a9d08e">
                                            <div align="center"><strong>SUB DETALLE</strong></div>
                                        </td>
                                        <td width="24%" bgcolor="#a9d08e">
                                            <div align="center"><strong>OBSERVACI&Oacute;N</strong></div>
                                        </td>
                                        <td width="3%" bgcolor="#a9d08e">
                                            <div align="center"><strong>CANT.</strong></div>
                                        </td>
                                        <td width="7%" bgcolor="#a9d08e">
                                            <div align="center"><strong>V. UNI.</strong></div>
                                        </td>
                                        <td width="5%" bgcolor="#a9d08e">
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
                                                    <div align="center">{{ $dato->fecha }}</div>
                                                </td>
                                                <td style="font-size:10px">
                                                    <div align="center">{{ $dato->tarea }}</div>
                                                </td>
                                                <td style="font-size:10px">
                                                    <div align="center">{{ $dato->numero_factura }}</div>
                                                </td>
                                                <td style="font-size:10px">
                                                    <div align="center">{{ $dato->ruc }}</div>
                                                </td>
                                                <td style="font-size:10px">
                                                    <div align="center">{{ $dato->autorizacion_especial }}</div>
                                                </td>
                                                <td style="font-size:10px">
                                                    <div align="center">{{ $dato->detalle }}</div>
                                                </td>
                                                <td style="font-size:10px">
                                                    <div align="center">{{ $dato->sub_detalle }}</div>
                                                </td>
                                                <td style="font-size:10px">
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
                                        @endforeach
                                    @endif
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table width="100%" border="1" cellspacing="0"
                                    bordercolor="#666666"style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px;margin-top: 40px;">
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
                                                {{ number_format($nuevo_saldo - $sub_total + $restas_diferencias, 2, ',', ' ') }}
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                    </table>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div align="left">
                    <strong>Observacion:&nbsp;</strong>
                    <ul>
                        <li>&bull; Todo gasto deber&aacute; registrarse&nbsp; y respaldarse con su
                            debido justificativo (FACTURA O
                            NOTA DE VENTA-RISE).</li>
                        <li>Toda factura deber&aacute; pertenecer a la semana en curso, (S&aacute;bado a
                            Viernes).&nbsp;
                        </li>
                        <li>&bull; Factura que se registre o se adjunte fuera de fecha no sera
                            reembolsada.</li>
                        <li>&bull; En la parte posterior de la factura deberan firmar quienes son
                            beneficiados con el servicio
                        </li>
                        <li>&bull; Los valores de las facturas ser&aacute;n acordes con los viaticos
                            diarios utilizados.</li>
                        <li>&bull; Las facturas deberan ser legalizadas (Firma Igual a C&eacute;dula )
                            por quienes consumen el
                            servicio</li>
                        <li>&bull; El Casillero Autorizacion Especial, es cuando el Coordinador ha
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
        <tr>
            <td>
                <div align="center">
                    <p style="font-size:11px">__________________________________</p>
                    <p style="font-size:11px"><strong>RESPONSABLE DE MANEJO DE
                            VIATICOS</strong></p>
                </div>
            </td>
            <td>
                <div align="center">
                    <p style="font-size:11px">__________________________________</p>
                    <p style="font-size:11px"><strong>RESPONSABLE CONTROL DE
                            VIATICOS</strong></p>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div align="left" style="font-size:11px; margin-left:40px;">Nombre:
                </div>
            </td>
            <td>
                <div align="left" style="font-size:11px; margin-left:40px;">Nombre:
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div align="left" style="font-size:11px; margin-left:40px;">C.I.</div>
            </td>
            <td>
                <div align="left" style="font-size:11px; margin-left:40px;">C.I.</div>
            </td>
        </tr>

    </table>


</body>

</html>
