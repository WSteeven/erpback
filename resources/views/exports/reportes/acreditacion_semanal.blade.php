<html>
@php
    $fecha = new Datetime();
    $logo_principal = 'data:image/png;base64,' . base64_encode(file_get_contents(public_path() . $configuracion['logo_claro']));
    $logo_watermark = 'data:image/png;base64,' . base64_encode(file_get_contents(public_path() . $configuracion['logo_marca_agua']));
    $item=0;
@endphp

<head>
    <style>
        body {
            font-family: sans-serif;
            background-image: url({{ $logo_watermark }});
            background-size: 50% auto;
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

<body>
    <header>
        <table
            style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:18px; ">
            <tr class="row" style="width:auto">
                <td style="width: 10%;">
                    <div class="col-md-3"><img src="{{ $logo_principal }}" width="90"></div>
                </td>
                <td style="width: 100%">
                    <div class="col-md-7" align="center"><b>REPORTE {{ $titulo }}</b>
                    </div>

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
                    <div style="margin: 0%; margin-bottom: 0px; margin-top: 0px;" align="center">{{ $copyright }}
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

        <table width="100%" border="1" cellspacing="0" bordercolor="#666666" class="gastos">
            <tr>
                <td width="8%" bgcolor="#a9d08e">
                    <div align="center"><strong>ITEM</strong></div>
                </td>
                <td width="50%" bgcolor="#a9d08e">
                    <div align="center"><strong>EMPLEADO</strong></div>
                </td>
                <td width="15%" bgcolor="#a9d08e">
                    <div align="center"><strong>SALDO ACTUAL</strong></div>
                </td>
                <td width="17%" bgcolor="#a9d08e">
                    <div align="center"><strong>MONTO ASIGNADO</strong></div>
                </td>
                <td width="25%" bgcolor="#a9d08e">
                    <div align="center"><strong>MOTIVO</strong></div>
                </td>

            </tr>
            @if (sizeof($reportes) == 0)
                <tr>
                    <td colspan="12">
                        <div align="center">NO HAY ACREDITACIONES EN ESTA SEMANA</div>
                    </td>
                </tr>
            @else
                @foreach ($reportes as $dato)
                @php
                    $item ++;
                @endphp
                    <tr>
                        <td style="font-size:10px">
                            <div align="center">{{ $item }}</div>
                        </td>
                        <td style="font-size:10px">
                            <div>{{ $dato['empleado_info'] }}</div>
                        </td>
                        <td style="font-size:10px">
                            <div align="right">{{ $dato['saldo_actual'] }}</div>
                        </td>
                        <td style="font-size:10px">
                            <div align="right">{{ $dato['monto_modificado'] }}</div>
                        </td>
                        <td style="font-size:10px">
                            <div>{{ $dato['motivo'] }}</div>
                        </td>

                    </tr>
                @endforeach
                <tr>
                    <td colspan="3" style="font-size:10px"> <div align="center"><strong>TOTAL DE MONTO ASIGNADO</strong></div></td>
                    <td style="font-size:10px">
                        <div align="right"> {{ $suma }}</div>
                    </td>
                    <td></td>
                </tr>
            @endif

        </table>
        </p>
    </div>

</body>

</html>
