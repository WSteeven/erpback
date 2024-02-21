<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style type="text/css">
.encabezado {
	text-align: center;
}
</style>
</head>
<body>
 <table
        style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px;margin-top: 20px;">
        <tr>
            <div class="header">
                <table
                    style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px;page-break-inside: avoid;">
                    <tr>
                        <td width="17%">
                            <img src="{{ public_path($config['logo_claro']) }}" width="90">
                        </td>
                        <td width="83%" colspan="7" style="font-size:16px; font-weight:bold; text-align: center">
                            <div align="center">JPCONSTRUCRED C.LTDA</div>
                        </td>
                    </tr>
                    <tr>
                        <td width="17%">
                            <div align="center"></div>
                        </td>
                        <td width="83%" colspan="7" style="font-size:12px;text-align: center">
                            <div align="center"><strong>REPORTE DE VALORES A COBRAR
                                </strong>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </tr>
        <tr>
            <td>
                <div align="center">
                    <table width="100%">
                        <tr>
                            <td></td>
                        </tr>
                        <tr>
                            <td>
                                <table width="100%" style="border: 3px solid #000000;">
                                    <tr>
                                        <td style="font-size:12px; font-weight:bold; text-align: center">MES</td>
                                        <td style="font-size:12px; font-weight:bold; text-align: center">COMISION</td>
                                        <td style="font-size:12px; font-weight:bold; text-align: center">TOTAL DE VENTAS</td>
                                        <td style="font-size:12px; font-weight:bold; text-align: center">ARPU</td>
                                        <td style="font-size:12px; font-weight:bold; text-align: center">METAS ALTAS</td>
                                        <td style="font-size:12px; font-weight:bold; text-align: center">BONO TC</td>
                                        <td style="font-size:12px; font-weight:bold; text-align: center">BONO TRIMESTRAL</td>
                                        <td style="font-size:12px; font-weight:bold; text-align: center">BONO CALIDAD 180 DÍAS</td>
                                    </tr>

                                    @foreach ($reportes as $reporte)
                                        <tr>
                                            <td>{{ $reporte['mes'] }}</td>
                                            <td align="right">{{ $reporte['comision'] }}</td>
                                            <td align="right">{{ $reporte['total_ventas'] }}</td>
                                            <td align="right">{{ $reporte['arpu'] }}</td>
                                            <td align="right">{{ $reporte['metas_altas'] }}</td>
                                            <td>{{ $reporte['bonotc'] }}</td>
                                            <td>{{ $reporte['bono_trimestral'] }}</td>
                                            <td>{{ $reporte['bono_calidad180'] }}</td>
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
</body>
</html>
