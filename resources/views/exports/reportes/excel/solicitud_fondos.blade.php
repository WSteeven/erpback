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
    <table
        style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px;margin-top: 20px;">

        <tr height="29">
            <td height="15">
                <div align="center">
                    <table width="100%">
                        <tr>
                            <td>
                                <table width="100%" border="1" align="left" cellpadding="0" cellspacing="0" class="saldos_depositados" >
                                    <tr>
                                        <td width="17%">
                                        </td>
                                        <td width="83%" style="font-size:16px; font-weight:bold">
                                            <div align="center">JPCONSTRUCRED C.LTDA</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="17%">
                                        </td>
                                        <td width="83%" bgcolor="#bfbfbf" style="font-size:12px;" >
                                            <div align="center"><strong>REPORTE SEMANAL DE SOLICITUD DE FONDOS DEL
                                                {{  date("d-m-Y", strtotime( $fecha_inicio)) . ' AL ' .date("d-m-Y", strtotime($fecha_fin))  }}
                                                </strong>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="17%">
                                        </td>
                                        <td width="83%" bgcolor="#bfbfbf" style="font-size:12px;" >
                                            <div align="center"><strong> {{ $usuario->nombres.' '.$usuario->apellidos }}</strong>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:10px" width="8%" bgcolor="#a9d08e"><strong>Fecha</strong></td>
                                        <td style="font-size:10px" width="8%" bgcolor="#a9d08e"><strong>Lugar</strong></td>
                                        <td style="font-size:10px" width="8%" bgcolor="#a9d08e"><strong>Grupo</strong></td>
                                        <td style="font-size:10px"width="7%" bgcolor="#a9d08e"><strong>Motivo</strong></td>
                                        <td style="font-size:10px"width="9%" bgcolor="#a9d08e"><strong>Monto</strong></td>
                                        <td style="font-size:10px" width="80%" bgcolor="#a9d08e"><strong>Descripción de la solicitud</strong></td>
                                    </tr>
                                    @if (sizeof($solicitudes) > 0)
                                        @foreach ($solicitudes as $dato)
                                            <tr>
                                                <td style="font-size:10px">{{  date("d-m-Y", strtotime(  $dato['fecha_gasto'])) }}</td>
                                                <td style="font-size:10px">{{ $dato['lugar_info'] }}</td>
                                                <td style="font-size:10px">{{ $dato['grupo_info'] }}</td>
                                                <td style="font-size:10px">
                                                    <div align="center">  @foreach($dato['motivo_info'] as $motivo)
                                                        {{ $motivo->nombre }}
                                                        @if (!$loop->last)
                                                           ,
                                                        @endif
                                                     @endforeach</div>
                                                </td>
                                                 <td style="font-size:10px">{{ number_format($dato['monto'], 2, ',', '.') }}</td>
                                                <td style="font-size:10px">{{ $dato['observacion'] }}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td style="font-size:10px" colspan="6">NO SE REALIZARON SOLICITUDES DE FONDOS.</td>
                                        </tr>
                                    @endif
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
