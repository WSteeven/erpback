<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de Gastos</title>
    <style>
        @page {
            margin: 2px 15px 5px 15px;
        }

        body {
            /* background-image: url({{ 'data:image/png;base64,' . base64_encode(file_get_contents('img/logoJPBN_10.png')) }}); */
            background-image: url({{ 'data:image/png;base64,' . base64_encode(file_get_contents('img/logoJPBN_10.png')) }});
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
            bottom: 5px;
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
            margin-bottom: 4.3cm;
            font-size: 12px;
        }

        div {
            color: #000000 !important;
        }

        h1 {
            text-align: center;
            text-transform: uppercase;
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
                    <div class="col-md-3"><img
                            src="{{ 'data:image/png;base64,' . base64_encode(file_get_contents('img/logoJP.png')) }}"
                            width="90"></div>
                </td>
                <td style="width: 100%">
                    <div class="col-md-7" align="center"><b style="font-size: 75%">{{ $titulo }}</b></div>
                </td>
            </tr>
        </table>
        <hr>
    </header>
    <footer>
        <table style="width: 100%;">
            <tr>
                <td style="line-height: normal;">
                    <div style="margin: 0%; margin-bottom: 0px; margin-top: 0px;" align="center">Esta informacion es
                        propiedad de JPCONSTRUCRED C.LTDA. - Prohibida su divulgacion
                    </div>
                    <div style="margin: 0%; margin-bottom: 0px; margin-top: 0px;" align="center">Generado por el
                        Usuario:
                        {{ auth('sanctum')->user()->empleado->nombres }}
                        {{ auth('sanctum')->user()->empleado->apellidos }} el
                        {{ $fecha->format('d-m-Y H:i') }}
                    </div>
                </td>
                <td class="page">Página </td>

            </tr>
        </table>
    </footer>
    <main>
        @php
            $total = 0;
        @endphp
        @if ($subtitulo != '')
            <p
                style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12; font-weight:bold; margin-top: -6px;">
            <div align="center" style=" background-color:#bfbfbf;"><strong>{{ $subtitulo }} </strong></div>
            </p>
            <br>
        @endif
        <table width="100%" border="1" align="left" cellpadding="0" cellspacing="0">
            <tr>
                <td bgcolor="#a9d08e" style="font-size:10px" width="3%">
                    <div align="center"><strong>#</strong></div>
                </td>
                <td bgcolor="#a9d08e" style="font-size:10px" width="13%">
                    <div align="center"><strong>NOMBRES Y APELLIDOS</strong></div>
                </td>
                <td bgcolor="#a9d08e" style="font-size:10px" width="4%">
                    <div align="center"><strong>LUGAR</strong></div>
                </td>
                <td bgcolor="#a9d08e" style="font-size:10px" width="5%">
                    <div align="center"><strong>FECHA</strong></div>
                </td>
                @if ($subtitulo == '' && $tipo_filtro != 3)
                    <td bgcolor="#a9d08e" style="font-size:10px" width="13%">
                        <div align="center"><strong>DESCRIPCI&Oacute;N  DEL GASTO</strong></div>
                    </td>
                @endif
                <td bgcolor="#a9d08e" style="font-size:10px" width="8%">
                    <div align="center"><strong>#COMPROBANTE</strong></div>
                </td>
                <td bgcolor="#a9d08e" style="font-size:10px" width="13%">
                    <div align="center"><strong>OBSERVACI&Oacute;N</strong></div>
                </td>
                <td bgcolor="#a9d08e" style="font-size:10px"  width="13%">
                    <div align="center"><strong>COMENTARIO</strong></div>
                </td>
                <td bgcolor="#a9d08e" style="font-size:10px" width="5%">
                    <div align="center"><strong>AUTORIZADOR</strong></div>
                </td>
                @if ($subdetalle == 96)
                    <td bgcolor="#a9d08e" style="font-size:10px" width="5%">
                        <div align="center"><strong>PLACA</strong></div>
                    </td>
                    <td bgcolor="#a9d08e" style="font-size:10px" width="5%">
                        <div align="center"><strong>KILOMETRAJE</strong></div>
                    </td>
                @endif
                <td bgcolor="#a9d08e" style="font-size:10px" width="5%">
                    <div align="center"><strong>MONTO</strong></div>
                </td>
            </tr>

            @foreach ($gastos as $gasto)
                @php
                    $total = number_format($gasto['total'], 2) + $total;
                @endphp
                <tr>
                    <td style="font-size:10px">
                        <div align="left">{{ $gasto['num_registro'] }}
                        </div>
                    </td>
                    <td style="font-size:10px">
                        <div align="left">
                            {{ $gasto['usuario']->nombres . ' ' . $gasto['usuario']->apellidos }}
                        </div>
                    </td>
                    <td style="font-size:10px">
                        <div align="left">{{ $gasto['lugar'] }}
                        </div>
                    </td>
                    <td style="font-size:10px">
                        <div align="center">{{ date('d-m-Y', strtotime($gasto['fecha'])) }}</div>
                    </td>
                    @if ($subtitulo == '' && $tipo_filtro != 3)
                        <td style="font-size:10px">
                            <div align="left">
                                {{ $gasto['sub_detalle_desc'] }}
                            </div>
                        </td>
                    @endif
                    <td style="font-size:10px">
                        <div align="left">{{ $gasto['factura'] }}</div>
                    </td>
                    <td style="font-size:10px">
                        <div align="left">{{ $gasto['observacion'] }}</div>
                    </td>
                    <td style="font-size:10px">
                        <div align="left">{{ $gasto['detalle_estado'] }}</div>
                    </td>
                    <td style="font-size:10px" width="29%">
                        <div align="left">
                            {{ $gasto['autorizador'] }}
                        </div>
                    </td>
                    @if ($subdetalle == 96)
                        <td style="font-size:10px">
                            <div align="right">{{ $gasto['placa'] }}</div>
                        </td>
                        <td style="font-size:10px">
                            <div align="right">{{ $gasto['kilometraje'] }}</div>
                        </td>
                    @endif
                    <td style="font-size:10px">
                        <div align="right">
                            {{ number_format($gasto['total'], 2, ',', '.') }}</div>
                    </td>
                </tr>
            @endforeach
            <tr>
                @if ($subtitulo == '')
                    @if ($subdetalle == 96)
                        <td colspan="11" style="font-size:10px" width="29%">
                            <div align="right"><strong>Total</strong></div>
                        </td>
                    @else
                        <td colspan="9" style="font-size:10px" width="29%">
                            <div align="right"><strong>Total</strong></div>
                        </td>
                    @endif
                @else
                    @if ($subdetalle == 96)
                        <td colspan="10" style="font-size:10px" width="29%">
                            <div align="right"><strong>Total</strong></div>
                        </td>
                    @else
                    <td colspan="9" style="font-size:10px" width="29%">
                        <div align="right"><strong>Total</strong></div>
                    </td>
                    @endif
                @endif


                <td style="font-size:10px" width="10%">
                    <div align="right">
                        <strong>{{ number_format($total, 2, ',', '.') }}</strong>
                    </div>
                </td>
            </tr>
        </table>


    </main>
</body>

</html>
