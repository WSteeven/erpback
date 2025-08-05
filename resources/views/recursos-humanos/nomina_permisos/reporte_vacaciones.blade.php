@php
    use Src\Shared\Utils;
@endphp
    <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de Vacaciones</title>

    <style>
        @page {
            margin: 100px 25px;
        }

        .header {
            position: fixed;
            top: -55px;
            left: 0;
            right: 0;
            height: 80px;
            text-align: center;
            line-height: 35px;
        }
    </style>
</head>
<body>
<table style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px;margin-top: 20px;">
    <tr>
        <div class="header">
            <table style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px;page-break-inside: avoid;">
                <tr>
                    <td style="width: 17%">
                        <img src="{{ Utils::urlToBase64(url($configuracion['logo_claro'])) }}" width="90" alt="logo empresa">
                    </td>
                    <td style="width: 83%; font-size:16px; font-weight:bold">
                        <div style="text-align: center">{{$configuracion['razon_social']}}}</div>
                    </td>
                </tr>
                <tr>
                    <td style="width: 17%">
                        <div style="text-align: center"></div>
                    </td>
                    <td style="width:83%; font-size:12px">
                        <div style="text-align: center"><strong>REPORTE DE PROVEEDORES
                            </strong>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </tr>
    <tr>
        <td>
            <div style="text-align: center">
                <table style="width: 100%">
                    <tr>
                        <td></td>
                    </tr>
                    <tr>
                        <td>
                            <table style="width: 100%; border: 3px solid #000000;">
                                <tr>
                                    <td style="background-color:#DBDBDB">RUC</td>
                                </tr>

                                @foreach ($reporte as $rpt)
                                    <tr>
                                        <td>{{ $rpt }}</td>
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
