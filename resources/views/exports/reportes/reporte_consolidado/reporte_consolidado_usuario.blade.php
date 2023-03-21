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

        footer .page:after {
            content: counter(page);
        }
        footer .izq {
            text-align: left;
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
                    <div class="col-md-7" align="center"><b style="font-size: 75%">REPORTE CONSOLIDADO
                            {{ ' DEL ' . date("d/m/Y", strtotime( $fecha_inicio)) . ' AL ' . date("d/m/Y", strtotime( $fecha_fin)) }}</b></div>
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
                                                        {{ $usuario->empleado->nombres.' '.$usuario->empleado->apellidos }}
                                                    </div>
                                                </td>
                                                <td style="font-size:10px" width="15%">
                                                    <div align="left">{{ $usuario->name }}
                                                    </div>
                                                </td>
                                                <td style="font-size:10px" width="17%">
                                                    <div align="center">{{ date("d/m/Y", strtotime( $fecha_anterior)) }}</div>
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
                                                    {{ $usuario->empleado->nombres.' '.$usuario->empleado->apellidos }}
                                                </div>
                                            </td>
                                            <td style="font-size:10px" width="15%">
                                                <div align="left">{{ $usuario->name }}
                                                </div>
                                            </td>
                                            <td style="font-size:10px" width="17%">
                                                <div align="center">{{ date("d/m/Y", strtotime( $fecha_inicio)) . ' ' . date("d/m/Y", strtotime( $fecha_fin)) }}</div>
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
                                        <!--Gastos-->
                                        <tr>
                                            <td style="font-size:10px" width="29%">
                                                <div align="left">
                                                    {{ $usuario->empleado->nombres.' '.$usuario->empleado->apellidos }}
                                                </div>
                                            </td>
                                            <td style="font-size:10px" width="15%">
                                                <div align="left">{{ $usuario->name }}
                                                </div>
                                            </td>
                                            <td style="font-size:10px" width="17%">
                                                <div align="center">{{ date("d/m/Y", strtotime( $fecha_inicio))  . ' ' . date("d/m/Y", strtotime($fecha_fin))  }}</div>
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
    </main>
</body>

</html>
