@php
    $item=0
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte</title>
    <style>
        @page {
            margin: 100px 25px;
        }

        .header {
            position: fixed;
            top: -55px;
            left: 0px;
            right: 0px;
            height: 80px;
            text-align: center;
            line-height: 35px;
        }

        .footer {
            position: fixed;
            bottom: -50px;
            left: 0px;
            right: 0px;
            height: 50px;
            color: #333333;
            text-align: center;
            line-height: 35px;
            font-size: 10px;
            font-style: italic;
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
                        <td width="100%">
                            <div align="center"></div>
                        </td>
                        <td width="100%" style="font-size:16px; font-weight:bold">
                            <div align="center">JPCONSTRUCRED C.LTDA</div>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td width="100%"bgcolor="#bfbfbf" style="font-size:12px">
                            <div align="center">{{ strtoupper($titulo) }}</div>
                        </td>
                    </tr>
                </table>
            </div>
        </tr>

        <tr>
            <td>
                <div align="center">
                    <table width="100%" border="1" cellspacing="0" bordercolor="#666666" class="gastos">
                        <tr>
                            <td width="8%" bgcolor="#a9d08e">
                                <div align="center"><strong>ITEM</strong></div>
                            </td>
                            <td width="50%" bgcolor="#a9d08e">
                                <div align="center"><strong>EMPLEADO</strong></div>
                            </td>
                            <td width="17%" bgcolor="#a9d08e">
                                <div align="center"><strong>MONTO ASIGNADO</strong></div>
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
                                        <div>{{ $dato['empleado'] }}</div>
                                    </td>
                                    <td style="font-size:10px">
                                        <div align="right">{{ $dato['valor_asignado'] }}</div>
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="2" style="font-size:10px"> <div align="center"><strong>TOTAL DE MONTO ASIGNADO</strong></div></td>
                                <td style="font-size:10px">
                                    <div align="right"> {{ $suma }}</div>
                                </td>
                                <td></td>
                            </tr>
                        @endif

                    </table>
                </div>
            </td>
        </tr>

    </table>


</body>

</html>
