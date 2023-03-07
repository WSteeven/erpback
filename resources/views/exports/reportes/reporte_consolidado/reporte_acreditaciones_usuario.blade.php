<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte Acreditaciones</title>
    <style>
        @page {
            margin: 2px 15px 5px 15px;
        }

        body {
            /* background-image: url('img/logoJPBN_10.png'); */
            background-image: url('img/logoJPBN_10.png');
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
            bottom: 90px;
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
                    <div class="col-md-3"><img src="img/logoJP.png" width="90"></div>
                </td>
                <td style="width: 100%">
                    <div class="col-md-7" align="center"><b style="font-size: 75%">REPORTE DE ACREDITACIONES
                            {{' DEL ' . $fecha_inicio . ' AL ' . $fecha_fin }}</b></div>
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
                    <div style="margin: 0%; margin-bottom: 0px; margin-top: 0px;" align="center">JP Construcred C. Ltda.
                    </div>
                    <div style="margin: 0%; margin-bottom: 0px; margin-top: 0px;" align="center">Generado por:
                        {{ auth('sanctum')->user()->empleado->nombres }}
                        {{ auth('sanctum')->user()->empleado->apellidos }} el
                        {{ $fecha->format('d/m/Y H:i') }}
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
                                <td bgcolor="#bfbfbf" style="font-size:12px">
                                    <div align="center"><strong>{{ $usuario->empleado->nombres.' '.$usuario->empleado->apellidos }} </strong></div>
                                </td>
                            </tr>
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
                                                <div align="center"><strong>Fecha</strong></div>
                                            </td>
                                            <td bgcolor="#a9d08e" style="font-size:10px" width="29%">
                                                <div align="center"><strong>Descripci&oacute;n</strong></div>
                                            </td>
                                            <td bgcolor="#a9d08e" style="font-size:10px" width="10%">
                                                <div align="center"><strong>Monto</strong></div>
                                            </td>
                                        </tr>
                                        @foreach ($acreditaciones as $acreditacion)
                                            <tr>
                                                <td style="font-size:10px" width="29%">
                                                    <div align="left">
                                                        {{ $acreditacion['empleado']->nombres . ' ' . $acreditacion['empleado']->apellidos }}
                                                    </div>
                                                </td>
                                                <td style="font-size:10px" width="15%">
                                                    <div align="left">{{ $acreditacion['usuario']->name }}
                                                    </div>
                                                </td>
                                                <td style="font-size:10px" width="17%">
                                                    <div align="center">{{ $acreditacion['fecha'] }}</div>
                                                </td>
                                                <td style="font-size:10px" width="29%">
                                                    <div align="left">{{ $acreditacion['descripcion_saldo'] }}</div>
                                                </td>
                                                <td style="font-size:10px" width="10%">
                                                    <div align="right">
                                                        {{ number_format($acreditacion['monto'], 2, ',', '.') }}</div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
        </table>
    </main>
</body>

</html>
