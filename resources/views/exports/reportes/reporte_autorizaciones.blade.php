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
                    <div class="col-md-3"><img src="img/logoJP.png" width="90"></div>
                </td>
                <td style="width: 100%">
                    <div class="col-md-7" align="center"><b>REPORTE AUTORIZACIONES CON ESTADO
                        {{ $tipo_reporte->descripcion . ' DEL ' . $fecha_inicio . ' AL ' . $fecha_fin }}</b></div>

                </td>
            </tr>
        </table>
        <hr>
    </header>
    <footer>
        <table>
            <tr>
                <td>
                    <p class="izq">
                        Generado por:
                        {{ auth('sanctum')->user()->empleado->nombres }}
                        {{ auth('sanctum')->user()->empleado->apellidos }} el
                        {{ $fecha->format('d/m/Y H:i') }}
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
            <div class="col-md-7" align="center"><b>{{ $usuario->nombres . ' ' . $usuario->apellidos }}</b></div>
        </p>
        <p>
            <table width="100%" border="1" cellspacing="0" bordercolor="#666666">
                @if ($tipo_ARCHIVO == 'pdf')
                    <tr>
                        <td width="5%" bgcolor="#a9d08e">
                            <div align="center"><strong>FECHA</strong></div>
                        </td>
                        <td width="10%" bgcolor="#a9d08e">
                            <div align="center"><strong>USUARIO</strong></div>
                        </td>
                        <td width="8%" bgcolor="#a9d08e">
                            <div align="center"><strong>GRUPO</strong></div>
                        </td>
                        <td width="8%" bgcolor="#a9d08e">
                            <div align="center"><strong>TAREA</strong></div>
                        </td>
                        <td width="8%" bgcolor="#a9d08e">
                            <div align="center"><strong>DETALLE</strong></div>
                        </td>
                        <td width="8%" bgcolor="#a9d08e">
                            <div align="center"><strong>SUB DETALLE</strong></div>
                        </td>
                        <td width="33%" bgcolor="#a9d08e">
                            <div align="center"><strong>OBSERVACI&Oacute;N</strong></div>
                        </td>
                        <td width="22%" bgcolor="#a9d08e">
                            <div align="center"><strong>DETALLE DEL ESTADO</strong></div>
                        </td>
                        <td width="6%" bgcolor="#a9d08e">
                            <div align="center"><strong>TOTAL</strong></div>
                        </td>
                    </tr>
                @else
                    <tr>
                        <td width="5%" bgcolor="#a9d08e">
                            <div align="center"><strong>FECHA</strong></div>
                        </td>
                        <td width="5%" bgcolor="#a9d08e">
                            <div align="center"><strong>FECHA INGRESO</strong></div>
                        </td>
                        <td width="5%" bgcolor="#a9d08e">
                            <div align="center"><strong>FECHA PROCESO</strong></div>
                        </td>
                        <td width="10%" bgcolor="#a9d08e">
                            <div align="center"><strong>USUARIO</strong></div>
                        </td>
                        <td width="8%" bgcolor="#a9d08e">
                            <div align="center"><strong>GRUPO</strong></div>
                        </td>
                        <td width="8%" bgcolor="#a9d08e">
                            <div align="center"><strong>TAREA</strong></div>
                        </td>
                        <td width="8%" bgcolor="#a9d08e">
                            <div align="center"><strong>DETALLE</strong></div>
                        </td>
                        <td width="8%" bgcolor="#a9d08e">
                            <div align="center"><strong>SUB DETALLE</strong></div>
                        </td>
                        <td width="28%" bgcolor="#a9d08e">
                            <div align="center"><strong>OBSERVACI&Oacute;N</strong></div>
                        </td>
                        <td width="17%" bgcolor="#a9d08e">
                            <div align="center"><strong>DETALLE DEL ESTADO</strong></div>
                        </td>
                        <td width="6%" bgcolor="#a9d08e">
                            <div align="center"><strong>TOTAL</strong></div>
                        </td>
                    </tr>
                @endif

                @foreach ($datos_reporte as $dato)
                    @if ($tipo_ARCHIVO == 'pdf')
                        <tr style="font-size:9px">
                            <td width="5%">{{ $dato['fecha'] }}</td>
                            <td width="10%">
                                {{ $dato['usuario']->nombres . ' ' . $dato['usuario']->apellidos }}
                            </td>
                            <td width="8%">{{ $dato['grupo'] }}</td>
                            <td width="8%">{{ $dato['tarea']->codigo_tarea }}</td>
                            <td width="8%">{{ $dato['detalle']->descripcion }}</td>
                            <td width="8%">{{ $dato['sub_detalle']->descripcion }}</td>
                            <td width="33%">{{ $dato['observacion'] }}</td>
                            <td width="22%">{{ $dato['detalle_estado'] }}</td>
                            <td width="6%" align="center">
                                {{ number_format($dato['total'], 2, ',', ' ') }}</td>
                        </tr>
                    @else
                        <tr style="font-size:9px">
                            <td width="5%">{{ $dato['fecha'] }}</td>
                            <td width="5%">{{-- $dato->fecha_ingreso --}}</td>
                            <td width="5%">{{-- $dato->fecha_proc --}}</td>
                            <td width="10%">
                                {{ $dato['usuario']->nombres . ' ' . $dato['usuario']->apellidos }}
                            </td>
                            <td width="8%">{{ $dato['grupo']}}</td>
                            <td width="8%">{{ $dato['tarea']->codigo_tarea }}</td>
                            <td width="8%">{{ $dato['detalle']->descripcion }}</td>
                            <td width="8%">{{ $dato['sub_detalle']->descripcion }}</td>
                            <td width="22%">{{ $dato['observacion'] }}</td>
                            <td width="23%">{{ $dato['detalle_estado'] }}</td>
                            <td width="6%" align="center">
                                {{ number_format($dato['total'], 2, ',', ' ') }}</td>
                        </tr>
                    @endif
                @endforeach
                @if (is_int($resto / $div))
            </table>
        </p>
        <p>
            <table width="100%" border="1" cellspacing="0" bordercolor="#666666"
            style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px;margin-top: 20px;">
            @endif

            <tr>
                <td width="95%" style="font-size:10px" colspan="'.$colspan.'">
                    <div align="right"><strong>TOTAL</strong></div>
                </td>
                <td width="5%"style="font-size:10px">
                    <div align="center">{{ number_format($subtotal, 2, ',', ' ') }}</div>
                </td>
            </tr>

        </table>
        </p>
    </div>

</body>

</html>
