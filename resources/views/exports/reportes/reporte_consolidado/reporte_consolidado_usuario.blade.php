<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte Consolidado</title>
    <style>
        @page {
            margin: 2px 15px 5px 15px;
        }

        body {
            /* background-image: url({{ 'data:image/png;base64,'. base64_encode(file_get_contents('img/logoJPBN_10.png')) }}); */
            background-image: url({{ 'data:image/png;base64,'. base64_encode(file_get_contents('img/logoJPBN_10.png')) }});
            background-repeat: no-repeat;
            background-position: center;
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
            bottom: 10px;
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
    </style>
</head>
@php
    $fecha = new Datetime();
    $ciclo = [1, 2, 3, 4, 5, 6, 7, 8, 9, 0, 1, 2, 3, 4, 5];
@endphp

<body>
    <header>
        <table
            style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:18px;">
            <tr class="row" style="width:auto">
                <td style="width: 10%;">
                    <div class="col-md-3"><img src="{{ 'data:image/png;base64,'. base64_encode(file_get_contents('img/logoJP.png')) }}" width="90"></div>
                </td>
                <td style="width: 100%">
                    <div class="col-md-7" align="center"><b style="font-size: 75%">REPORTE CONSOLIDADO
                            {{ ' DEL ' . date("d-m-Y", strtotime( $fecha_inicio)) . ' AL ' . date("d-m-Y", strtotime( $fecha_fin)) }}</b></div>
                </td>
            </tr>
        </table>
        <hr>
    </header>
    <footer>
        <table style="width: 100%;">
            <tr>
                <td style="line-height: normal;">
                    <div style="margin: 0%; margin-bottom: 0px; margin-top: 0px;" align="center">Esta informacion es propiedad de  JPCONSTRUCRED C.LTDA. - Prohibida su divulgacion
                    </div>
                    <div style="margin: 0%; margin-bottom: 0px; margin-top: 0px;" align="center">Generado por el
                        Usuario:
                        {{ auth('sanctum')->user()->empleado->nombres }}
                        {{ auth('sanctum')->user()->empleado->apellidos }} el
                        {{ $fecha->format('d-m-Y H:i') }}
                    </div>
                </td>
            </tr>
        </table>
    </footer>
    <main>
        <table
            style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px;margin-top: 20px;">
            <tr height="29">
                <td height="15">
                    <div align="center">
                        <table width="100%">
                            <tr>
                                <td height="55px;">
                                    <table width="100%" border="1" align="left" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td bgcolor="#a9d08e" style="font-size:10px" width="29%">
                                                <div align="center"><strong>Nombres y Apellidos</strong></div>
                                            </td>
                                            <td bgcolor="#a9d08e" style="font-size:10px" width="15%">
                                                <div align="center"><strong>Usuario</strong></div>
                                            </td>
                                            <td bgcolor="#a9d08e" style="font-size:10px" width="17%">
                                                <div align="center"><strong>Fecha Consolidado</strong></div>
                                            </td>
                                            <td bgcolor="#a9d08e" style="font-size:10px" width="29%">
                                                <div align="center"><strong>Descripci&oacute;n</strong></div>
                                            </td>
                                            <td bgcolor="#a9d08e" style="font-size:10px" width="10%">
                                                <div align="center"><strong>Monto</strong></div>
                                            </td>
                                        </tr>
                                        <!--Saldo Inicial-->
                                            <tr>
                                                <td style="font-size:10px" width="29%">
                                                    <div align="left">
                                                        {{ $empleado->nombres.' '.$empleado->apellidos }}
                                                    </div>
                                                </td>
                                                <td style="font-size:10px" width="15%">
                                                    <div align="left">{{ $usuario->name }}
                                                    </div>
                                                </td>
                                                <td style="font-size:10px" width="17%">
                                                    <div align="center">{{ date("d-m-Y", strtotime( $fecha_anterior)) }}</div>
                                                </td>
                                                <td style="font-size:10px" width="29%">
                                                    <div align="left">Saldo Inicial</div>
                                                </td>
                                                <td style="font-size:10px" width="10%">
                                                    <div align="right">
                                                        {{ number_format($saldo_anterior, 2, ',', '.') }}</div>
                                                </td>
                                            </tr>
                                        <!--Fin Saldo Inicial-->
                                        <!--Acreditaciones-->
                                        <tr>
                                            <td style="font-size:10px" width="29%">
                                                <div align="left">
                                                    {{ $empleado->nombres.' '.$empleado->apellidos }}
                                                </div>
                                            </td>
                                            <td style="font-size:10px" width="15%">
                                                <div align="left">{{ $usuario->name }}
                                                </div>
                                            </td>
                                            <td style="font-size:10px" width="17%">
                                                <div align="center">{{ date("d-m-Y", strtotime( $fecha_inicio)) . ' ' . date("d-m-Y", strtotime( $fecha_fin)) }}</div>
                                            </td>
                                            <td style="font-size:10px" width="29%">
                                                <div align="left">Acreditaciones</div>
                                            </td>
                                            <td style="font-size:10px" width="10%">
                                                <div align="right">
                                                    {{ number_format($acreditaciones, 2, ',', '.') }}</div>
                                            </td>
                                        </tr>
                                        <!--Fin Acreditaciones-->
                                        <!--Transferencias-->
                                        <tr>
                                            <td style="font-size:10px" width="29%">
                                                <div align="left">
                                                    {{ $empleado->nombres.' '.$empleado->apellidos }}
                                                </div>
                                            </td>
                                            <td style="font-size:10px" width="15%">
                                                <div align="left">{{ $usuario->name }}
                                                </div>
                                            </td>
                                            <td style="font-size:10px" width="17%">
                                                <div align="center">{{ date("d-m-Y", strtotime( $fecha_inicio))  . ' ' . date("d-m-Y", strtotime($fecha_fin))  }}</div>
                                            </td>
                                            <td style="font-size:10px" width="29%">
                                                <div align="left">Transferencias</div>
                                            </td>
                                            <td style="font-size:10px" width="10%">
                                                <div align="right">
                                                    {{ number_format($transferencia, 2, ',', '.') }}</div>
                                            </td>
                                        </tr>



                                        <!--Fin Transferencias-->
                                        <!--Gastos-->
                                        <tr>
                                            <td style="font-size:10px" width="29%">
                                                <div align="left">
                                                    {{ $empleado->nombres.' '.$empleado->apellidos }}
                                                </div>
                                            </td>
                                            <td style="font-size:10px" width="15%">
                                                <div align="left">{{ $usuario->name }}
                                                </div>
                                            </td>
                                            <td style="font-size:10px" width="17%">
                                                <div align="center">{{ date("d-m-Y", strtotime( $fecha_inicio))  . ' ' . date("d-m-Y", strtotime($fecha_fin))  }}</div>
                                            </td>
                                            <td style="font-size:10px" width="29%">
                                                <div align="left">Gastos</div>
                                            </td>
                                            <td style="font-size:10px" width="10%">
                                                <div align="right">
                                                    {{ number_format($gastos, 2, ',', '.') }}</div>
                                            </td>
                                        </tr>
                                        <!--Fin Gastos-->
                                        <!--Saldo Final-->
                                        <tr>
                                            <td colspan="4" style="font-size:10px"><div align="right"><strong>TOTAL:</strong></div></td>
                                            <td style="font-size:10px" align="center"><div align="right"  style="margin-right:20px;">{{  number_format($total_suma, 2, ',', ' ')  }}</div></td>
                                          </tr>
                                        <!--Fin Saldo Final-->
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
        </table>
        <p  style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:15px;margin-top: -6px;>
            <div class="col-md-7" align="center"><b>Detalle de Gastos</b></div>
        </p>
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
            @if (sizeof($gastos_reporte) == 0)
                <tr>
                    <td colspan="12">
                        <div align="center">NO HAY FONDOS ROTATIVOS APROBADOS</div>
                    </td>
                </tr>
            @else
                @foreach ($gastos_reporte as $dato)
                    @php
                        $sub_total = $sub_total + (float) $dato->total;
                    @endphp
                    <tr>
                        <td style="font-size:10px">
                            <div align="center">{{ $dato->id }}</div>
                        </td>
                        <td style="font-size:10px">
                            <div align="center">{{   date("d-m-Y", strtotime( $dato->fecha_viat))}}</div>
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
                                {{ $dato->aut_especial_user->nombres . ' ' . $dato->aut_especial_user->apellidos }}
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
                @endforeach
            @endif
            <tr>
                <td>&nbsp;</td>
                <td colspan="10" style="font-size:10px">
                    <div align="right"><strong>TOTAL DE GASTOS:&nbsp;</strong></div>
                </td>
                <td style="font-size:10px">
                    <div align="center">{{ number_format($sub_total, 2, ',', ' ') }}</div>
                </td>
            </tr>
        </table>
    </main>
</body>

</html>
