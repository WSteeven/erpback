<html>

<head>
    <style>
        body {
            font-family: sans-serif;
            background-image: url({{ 'data:image/png;base64,'. base64_encode(file_get_contents('img/logoJPBN_10.png')) }});
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
                    <div class="col-md-3"><img src="{{ 'data:image/png;base64,'. base64_encode(file_get_contents('img/logoJP.png')) }}" width="90"></div>
                </td>
                <td style="width: 100%">
                    <div class="col-md-7" align="center"><b>REPORTE SEMANAL DE SOLICITUD DE FONDOS DEL
                            {{  date("d-m-Y", strtotime( $fecha_inicio)) . ' AL ' .date("d-m-Y", strtotime($fecha_fin))  }}</b></div>

                </td>
            </tr>
        </table>
        <hr>
    </header>
    <footer>
        <table style="width: 100%;">
            <tr>
                <td class="page">Página </td>
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
    <div id="content">
        <p>
            <div style="text-align: center; font-weight: bold">{{ $usuario->nombres.' '.$usuario->apellidos }}</div>
        </p>
        <p>
        <table width="100%" border="1" align="left" cellpadding="0" cellspacing="0" class="saldos_depositados" >
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
                    <td style="font-size:10px" colspan="5">NO SE REALIZARON SOLICITUDES DE FONDOS.</td>
                </tr>
            @endif
        </table>
        </p>

    </div>

</body>

</html>
