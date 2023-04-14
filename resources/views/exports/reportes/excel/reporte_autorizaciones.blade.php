<html>

<head>
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
    </style>
</head>

<body>
    <div class="header">
        <table
            style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px;page-break-inside: avoid;">
            <tr>
                <td width="17%">
                </td>
                <td width="83%" style="font-size:16px; font-weight:bold">
                    <div align="center">JEAN PATRICIO PAZMI&Ntilde;O BARROS</div>
                    <div align="center">RUC:0702875618001</div>
                </td>
            </tr>
        </table>
    </div>

    <table
        style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px;margin-top: 20px;">

        <tr height="29">
            <td height="15">
                <div align="center">
                    <table width="100%">
                        <tr>
                            <td bgcolor="#bfbfbf" style="font-size:12px">
                                <div align="center"><strong>REPORTE AUTORIZACIONES CON ESTADO
                                        {{ $tipo_reporte->descripcion . ' DEL ' . $fecha_inicio . ' AL ' . $fecha_fin }}
                                    </strong>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td bgcolor="#bfbfbf" style="font-size:14px">
                                <div align="center">
                                    <strong>{{ $usuario->nombres . ' ' . $usuario->apellidos }}</strong>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <table width="100%" border="1" cellspacing="0" bordercolor="#666666">
                                        <tr>
                                            <td bgcolor="#a9d08e">
                                                <div align="center"><strong>FECHA</strong></div>
                                            </td>
                                            <td bgcolor="#a9d08e">
                                                <div align="center"><strong>FECHA INGRESO</strong></div>
                                            </td>
                                            <td bgcolor="#a9d08e">
                                                <div align="center"><strong>FECHA PROCESO</strong></div>
                                            </td>
                                            <td bgcolor="#a9d08e">
                                                <div align="center"><strong>USUARIO</strong></div>
                                            </td>
                                            <td bgcolor="#a9d08e">
                                                <div align="center"><strong>GRUPO</strong></div>
                                            </td>
                                            <td bgcolor="#a9d08e">
                                                <div align="center"><strong>TAREA</strong></div>
                                            </td>
                                            <td bgcolor="#a9d08e">
                                                <div align="center"><strong>DETALLE</strong></div>
                                            </td>
                                            <td bgcolor="#a9d08e">
                                                <div align="center"><strong>SUB DETALLE</strong></div>
                                            </td>
                                            <td bgcolor="#a9d08e">
                                                <div align="center"><strong>OBSERVACI&Oacute;N</strong></div>
                                            </td>
                                            <td bgcolor="#a9d08e">
                                                <div align="center"><strong>DETALLE DEL ESTADO</strong></div>
                                            </td>
                                            <td bgcolor="#a9d08e">
                                                <div align="center"><strong>TOTAL</strong></div>
                                            </td>
                                        </tr>

                                    @foreach ($datos_reporte as $dato)

                                            <tr style="font-size:9px">
                                                <td width="5%">{{ $dato['fecha'] }}</td>
                                                <td width="5%">{{-- $dato->fecha_ingreso --}}</td>
                                                <td width="5%">{{-- $dato->fecha_proc --}}</td>
                                                <td width="10%">
                                                    {{ $dato['usuario']->nombres . ' ' . $dato['usuario']->apellidos }}
                                                </td>
                                                <td width="8%">{{-- $detalle_grupo=$datos_grupo[0]->descripcion --}}
                                                </td>
                                                <td width="8%">{{ $dato['tarea']->codigo_tarea }}</td>
                                                <td width="8%">{{$dato['detalle'] }}</td>
                                                <td width="8%">
                                                    @foreach($dato['sub_detalle'] as $sub_detalle)
                                                    {{ $sub_detalle['descripcion'] }}
                                                    @if (!$loop->last)
                                                       ,
                                                    @endif
                                                 @endforeach
                                                </td>
                                                <td width="22%">{{ $dato['observacion'] }}</td>
                                                <td width="23%">{{ $dato['detalle_estado'] }}</td>
                                                <td width="6%" align="center">
                                                    {{ number_format($dato['total'], 2, ',', ' ') }}</td>
                                            </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="9">
                                            <table width="100%" border="1" cellspacing="0" bordercolor="#666666"
                                                style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px;">

                                                <tr>
                                                    <td width="95%" style="font-size:10px" colspan="'.$colspan.'">
                                                        <div align="right"><strong>TOTAL</strong></div>
                                                    </td>
                                                    <td width="5%"style="font-size:10px">
                                                        <div align="center">{{ number_format($subtotal, 2, ',', ' ') }}</div>
                                                    </td>
                                                </tr>

                                            </table>
                                        </td>
                                    </tr>
                                </table>

                            </td>
                        </tr>
                    </table>
                    <br />
                </div>
            </td>
        </tr>
    </table>
</body>

</html>
