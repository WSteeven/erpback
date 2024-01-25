<html>
@php
    $fecha = new Datetime();
    $logo_principal = 'data:image/png;base64,' . base64_encode(file_get_contents(public_path() . $configuracion['logo_claro']));
    $logo_watermark = 'data:image/png;base64,' . base64_encode(file_get_contents(public_path() . $configuracion['logo_marca_agua']));
    $num_registro = 1;
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
        }

        footer .page:after {
            content: counter(page);
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
                    <div class="col-md-7" align="center"><b>REPORTE AUTORIZACIONES CON ESTADO
                            {{ $tipo_reporte->descripcion . ' DEL ' . date('d-m-Y', strtotime($fecha_inicio)) . ' AL ' . date('d-m-Y', strtotime($fecha_fin)) }}</b>
                    </div>

                </td>
            </tr>
        </table>
        <hr>
    </header>
    <footer>
        <table style="width: 100%;">
            <tr>
                <td style="line-height: normal;">
                    <div style="margin: 0%; margin-bottom: 0px; margin-top: 0px;" align="center">{{ $copyright }}</div>
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
        <p style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:15px;margin-top: -6px;>
            <div class="
            col-md-7" align="center"><b>{{ $usuario->nombres . ' ' . $usuario->apellidos }}</b>
    </div>
    </p>
    <p>
    <table width="100%" border="1" cellspacing="0" bordercolor="#666666">
        <tr style="font-size:11px">
            <td width="5%" bgcolor="#a9d08e">#</td>
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
            <td width="22%" bgcolor="#a9d08e">
                <div align="center"><strong>CENTRO DE COSTO</strong></div>
            </td>
            <td width="22%" bgcolor="#a9d08e">
                <div align="center"><strong>SUB CENTRO DE COSTO</strong></div>
            </td>
            <td width="6%" bgcolor="#a9d08e">
                <div align="center"><strong>TOTAL</strong></div>
            </td>
        </tr>

        @foreach ($datos_reporte as $dato)
            <tr style="font-size:9px">
                <td>{{ $num_registro }}</td>
                <td width="5%">{{ date('d-m-Y', strtotime($dato['fecha'])) }}</td>
                <td width="10%">
                    {{ $dato['usuario']->nombres . ' ' . $dato['usuario']->apellidos }}
                </td>
                <td width="8%">{{ $dato['grupo'] }}</td>
                <td width="8%">{{ $dato['tarea'] == null ? 'SIN TAREA' : $dato['tarea']->codigo_tarea }}</td>
                <td width="8%">{{ $dato['detalle'] }}</td>
                <td width="8%">
                    @foreach ($dato['sub_detalle'] as $sub_detalle)
                        {{ $sub_detalle->descripcion }}
                        @if (!$loop->last)
                            ,
                        @endif
                    @endforeach
                </td>
                <td width="33%">{{ $dato['observacion'] }}</td>
                <td width="22%">{{ $dato['detalle_estado'] }}</td>
                <td width="22%">{{ $dato['centro_costo'] }}</td>
                <td width="22%">{{ $dato['sub_centro_costo'] }}</td>

                <td width="6%" align="center">{{ number_format($dato['total'], 2, ',', ' ') }}</td>
            </tr>
            @php
                $num_registro++;
            @endphp
        @endforeach
        <tr>
            <td colspan="12">
                <table width="100%" border="1" cellspacing="0" bordercolor="#666666"
                    style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px;">

                    <tr>
                        <td width="95%" style="font-size:10px" colspan="'.$colspan.'">
                            <div align="right"><strong>TOTAL</strong></div>
                        </td>
                        <td width="5%" style="font-size:10px">
                            <div align="center">{{ number_format($subtotal, 2, ',', ' ') }}</div>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
    </p>
    <p>
    </p>
    </div>
    <script type="text/php">
        if (isset($pdf)) {
                $text = "Pág {PAGE_NUM} de {PAGE_COUNT}";
                $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
                $pdf->page_text(10, 550, $text, $font, 12);
        }
    </script>
</body>

</html>
