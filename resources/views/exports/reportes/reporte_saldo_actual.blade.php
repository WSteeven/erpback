<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte Saldo Actual</title>
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
$ciclo = [1,2,3,4,5,6,7,8,9,0,1,2,3,4,5];
@endphp
<body>
    <header>
        <table style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:18px;">
            <tr class="row" style="width:auto">
                <td style="width: 10%;">
                    <div class="col-md-3"><img src="img/logoJP.png" width="90"></div>
                </td>
                <td style="width: 68%">
                    <div class="col-md-7" align="center"><b>REPORTE SALDO ACTUAL</b></div>
                </td>
                <td style="width: 52%;">
                    <div class="col-md-2" align="right">Sistema de Fondos Rotativos</div>
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
       <main>
        <table style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px;page-break-inside: avoid;">

            <tr height="29">
                <td height="15">
                    <div align="center">
                        <table width="100%">
                            <tr>
                                <td height="55px;">
                                    <table width="100%" border="1" align="left" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td bgcolor="#a9d08e" style="font-size:10px">
                                                <div align="center"><strong>Item</strong></div>
                                            </td>
                                            <td bgcolor="#a9d08e" style="font-size:10px">
                                                <div align="center"><strong>Nombres y Apellidos</strong></div>
                                            </td>
                                            <td bgcolor="#a9d08e" style="font-size:10px">
                                                <div align="center"><strong>Cargo</strong></div>
                                            </td>
                                            <td bgcolor="#a9d08e" style="font-size:10px">
                                                <div align="center"><strong>Localidad</strong></div>
                                            </td>
                                            <td bgcolor="#a9d08e" style="font-size:10px">
                                                <div align="center"><strong>Usuario</strong></div>
                                            </td>
                                            <td bgcolor="#a9d08e" style="font-size:10px">
                                                <div align="center"><strong>Monto</strong></div>
                                            </td>
                                        </tr>
                                        @foreach ($saldos as $saldo)
                                        <tr>
                                            <td style="font-size:10px">
                                                <div align="left" style="margin-left:20px;"> {{$saldo['item']}}</div>
                                            </td>
                                            <td style="font-size:10px">
                                                <div align="left" style="margin-left:20px;">
                                                    {{ $saldo['empleado']->nombres.' '.$saldo['empleado']->apellidos }}
                                                </div>
                                            </td>
                                            <td style="font-size:10px">
                                                <div align="left" style="margin-left:20px;">
                                                {{$saldo['cargo']}}
                                                </div>
                                            </td>
                                            <td style="font-size:10px">
                                                <div align="left" style="margin-left:20px;">
                                                {{$saldo['localidad']}}
                                                </div>
                                            </td>
                                            <td style="font-size:10px">
                                                <div align="left" style="margin-left:20px;">
                                                    {{ $saldo['usuario_info']->name}}
                                                </div>
                                            </td>
                                            <td style="font-size:10px">
                                                <div align="right" style="margin-right:20px;">
                                                    {{ number_format($saldo['saldo_actual'], 2, ',', '.') }}
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach

                                    </table>
                                </td>
                            </tr>


                        </table>
                </td>
            </tr>

        </table>
    </main>
</body>

</html>
