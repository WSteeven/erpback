<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>REPORTE ESTADO DE CUENTA</title>
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
                    <div class="col-md-7" align="center"><b style="font-size: 75%">REPORTE ESTADO DE CUENTA</b></div>
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
    </main>
</body>

</html>
