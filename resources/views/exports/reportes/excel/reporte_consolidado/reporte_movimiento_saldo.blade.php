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
                            <div align="center">JPCONSTRUCRED C.LTDA</div>
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
                                <div align="center"><strong>REPORTE ESTADO DE CUENTA</strong>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table
                                style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px;margin-top: 20px;">
                                <tr height="29">
                                    <td height="15">
                                        <div align="left">
                                            <b>Fecha: </b>{{ date("d-m-Y", strtotime( $fecha_inicio)) . ' ' . date("d-m-Y", strtotime( $fecha_fin)) }}
                                        </div>
                                    </td>
                                </tr>
                                <tr height="29">
                                    <td height="15">
                                        <div align="left">
                                            <b>Empleado:</b> {{ $empleado->nombres.' '.$empleado->apellidos }}
                                        </div>
                                    </td>
                                </tr>
                                <tr height="29">
                                    <td height="15">
                                        <div align="left">
                                            <b>Saldo Actual:</b>  {{  number_format($nuevo_saldo, 2, ',', ' ')  }}
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <table width="100%" border="1" cellspacing="0" bordercolor="#666666"  class="gastos">
                                    <tr>
                                        <td width="15%" bgcolor="#a9d08e">
                                            <div align="center"><strong>FECHA</strong></div>
                                        </td>
                                        <td width="25%" bgcolor="#a9d08e">
                                            <div align="center"><strong>DESCRIPCIÓN</strong></div>
                                        </td>
                                        <td width="10%" bgcolor="#a9d08e">
                                            <div align="center"><strong>INGRESO</strong></div>
                                        </td>
                                        <td width="10%" bgcolor="#a9d08e">
                                            <div align="center"><strong>GASTO</strong></div>
                                        </td>
                                        <td width="10%" bgcolor="#a9d08e">
                                            <div align="center"><strong>SALDO</strong></div>
                                        </td>
                                    </tr>
                                    @if (sizeof($reportes_unidos) == 0)
                                        <tr>
                                            <td colspan="12">
                                                <div align="center">NO HAY FONDOS ROTATIVOS APROBADOS</div>
                                            </td>
                                        </tr>
                                    @else
                                        @php
                                        $saldo_act = $saldo_anterior;
                                        @endphp
                                        @foreach ($reportes_unidos as $dato)
                                            @php
                                                $saldo_act = $saldo_act + $dato['ingreso'] - $dato['gasto'];
                                            @endphp
                                            <tr>
                                                <td style="font-size:10px">
                                                    <div align="center">{{   date("d-m-Y", strtotime( $dato['fecha']))}}</div>
                                                </td>

                                                <td style="font-size:10px">
                                                    <div align="center">{{$dato['descripcion']}}
                                                    </div>
                                                </td>
                                                <td style="font-size:10px">
                                                    <div align="center">{{ number_format($dato['ingreso'], 2, ',', '.') }}
                                                    </div>
                                                </td>
                                                <td style="font-size:10px">
                                                    <div align="center">{{ number_format($dato['gasto'], 2, ',', '.') }}
                                                    </div>
                                                </td>
                                                <td style="font-size:10px">
                                                    <div align="center">{{ isset($dato['saldo'])? number_format($dato['saldo'], 2, ',', '.') :number_format($saldo_act, 2, ',', '.') }}
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </table>
                            </td>
                        </tr>

                    </table>
                </div>
            </td>
        </tr>

    </table>


</body>

</html>
