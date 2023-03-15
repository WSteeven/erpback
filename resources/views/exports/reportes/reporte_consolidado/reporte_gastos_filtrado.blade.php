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
            bottom: 10px;
            left: 0cm;
            right: 0cm;
            height: 2cm;

            /** Estilos extra personales **/
            text-align: center;
            color: #000000;
            line-height: 1.5cm;
            font-size: 7pt;
        }
        footer table {
            width: 100%;
        }

        footer .page:after {
            content: counter(page);
        }
        footer .izq {
            text-align: left;
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

        .observacion {
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
                    <div class="col-md-7" align="center"><b>{{ $titulo }}</b></div>

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
        <p>
            <div style="font-size:12px; text-align: center; padding-bottom: 4%"">
                <strong>{{$subtitulo}} </strong>
            </div>
        <table width="100%" border="1" align="left" cellpadding="0" cellspacing="0">
            <tr>
                <td bgcolor="#a9d08e" style="font-size:10px" width="29%">
                    <div align="center"><strong>Nombres y Apellidos</strong></div>
                </td>
                <td bgcolor="#a9d08e" style="font-size:10px" width="15%">
                    <div align="center"><strong>Usuario</strong></div>
                </td>
                <td bgcolor="#a9d08e" style="font-size:10px" width="17%">
                    <div align="center"><strong>Fecha</strong></div>
                </td>
                <td bgcolor="#a9d08e" style="font-size:10px" width="29%">
                    <div align="center"><strong>Descripci&oacute;n</strong></div>
                </td>
                <td bgcolor="#a9d08e" style="font-size:10px" width="10%">
                    <div align="center"><strong>Monto</strong></div>
                </td>
            </tr>
            @foreach ($gastos as $gasto)
                <tr>
                    <td style="font-size:10px" width="29%">
                        <div align="left">
                            {{ $gasto['usuario']->nombres . ' ' . $gasto['usuario']->apellidos }}
                        </div>
                    </td>
                    <td style="font-size:10px" width="15%">
                        <div align="left">{{ $gasto['usuario_info']->name }}
                        </div>
                    </td>
                    <td style="font-size:10px" width="17%">
                        <div align="center">{{ $gasto['fecha'] }}</div>
                    </td>
                    <td style="font-size:10px" width="29%">
                        <div align="left">{{ $gasto['detalle_estado'] }}</div>
                    </td>
                    <td style="font-size:10px" width="10%">
                        <div align="right">
                            {{ number_format($gasto['total'], 2, ',', '.') }}</div>
                    </td>
                </tr>
            @endforeach
        </table>
        </p>
    </div>

</body>

</html>
