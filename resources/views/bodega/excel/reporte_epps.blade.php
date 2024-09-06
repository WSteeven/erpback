<!DOCTYPE html>
<html lang="en">

{{--@php--}}
{{--    if ($bodeguero->firma_url) {--}}
{{--            $entrega_firma = 'data:image/png;base64,' . base64_encode(file_get_contents(substr($bodeguero->firma_url, 1)));--}}
{{--        }--}}
{{--        if ($persona_retira->firma_url) {--}}
{{--            $retira_firma = 'data:image/png;base64,' . base64_encode(file_get_contents(substr($persona_retira->firma_url, 1)));--}}
{{--        }--}}
{{--@endphp--}}
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Acta de entrega</title>
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
                    <td>
                        <img src="{{ public_path($configuracion['logo_claro']) }}" width="90" alt="logo empresa">
                    </td>
                    <td style="font-size:16px; text-align: center; font-weight:bold" colspan="4">
                        <div align="center"><strong>COMPROBANTE DE EGRESO</strong></div>
                    </td>
                    <td style="font-size:16px;" colspan="2" align="right"><strong>Sistema de Bodega</strong></td>
                </tr>
                <tr>
                </tr>
                {{--                Aqui va la parte de cabecera de los registros           --}}
                <tr>
                    <td colspan="1">Justificación:</td>
                    <td colspan="2"><strong>  {{$reporte[0]['justificacion']}} </strong></td>
                    <td colspan="1">Solicitante:</td>
                    <td colspan="3"><strong>  {{$reporte[0]['solicitante']}}</strong></td>
                </tr>
                <tr>
                    <td colspan="1">Responsable:</td>
                    <td colspan="2"><strong> {{$reporte[0]['responsable']}}  </strong></td>
                    <td colspan="1">Sucursal:</td>
                    <td colspan="3"><strong> {{$reporte[0]['sucursal']}} </strong></td>
                </tr>
                <tr>
                    <td colspan="1">Autorizado por:</td>
                    <td colspan="2"><strong>{{$reporte[0]['autorizador']}} </strong></td>
                    <td colspan="1">Estado:</td>
                    <td colspan="3"><strong>COMPLETA </strong></td>
                </tr>
                <tr>
                    <td colspan="1">Cliente:</td>
                    <td colspan="2"><strong>  {{$reporte[0]['cliente']}}</strong></td>
                    <td colspan="1">Motivo:</td>
                    <td colspan="3"><strong> {{$reporte[0]['motivo']}}</strong></td>
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
                                    <td style="text-align: center;background-color: #DBDBDB;">N°</td>
                                    <td style="text-align: center;background-color: #DBDBDB;">PRODUCTO</td>
                                    <td style="text-align: center;background-color: #DBDBDB;">FECHA ENTREGA</td>
                                    <td style="text-align: center;background-color: #DBDBDB;">DESCRIPCION</td>
                                    <td style="  text-align: center;background-color: #DBDBDB;">CATEGORIA</td>
                                    <td style="  text-align: center;background-color: #DBDBDB;">CONDICION</td>
                                    <td style="  text-align: center;background-color: #DBDBDB;">DESPACHADO</td>
                                </tr>

                                @foreach ($reporte as $index => $rpt)
                                    <tr>
                                        <td>{{ $index+1 }}</td>
                                        <td>{{ $rpt['producto'] }}</td>
                                        <td>{{ ($rpt['fecha_despacho'])->format('Y-m-d H:i') }}</td>
                                        <td>{{ $rpt['descripcion'] }}</td>
                                        <td>{{ $rpt['categoria'] }}</td>
                                        <td>{{ $rpt['condicion'] }}</td>
                                        <td>{{ $rpt['despachado'] }}</td>
                                    </tr>
                                @endforeach
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>

    {{--    pie de pagina, aquí van las firmas --}}
    <tr>
        <div class="header">
            <table
                style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px;page-break-inside: avoid;">
                <tr>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: center">
                        <img src="{{ public_path($bodeguero->firma_url)  }}" width="90" alt="firma bodega"
                             onerror="this.onerror=null; this.src='{{ public_path('storage/image_not_found.png') }}';">
                    </td>
                    <td colspan="4" style="text-align: center">
                        <img src="{{ public_path($responsable->firma_url)  }}" width="90" alt="firma responsable"
                             onerror="this.onerror=null; this.src='{{ public_path('storage/image_not_found.png') }}';">
                    </td>

                </tr>
                <tr>
                    <td style="text-align: center;" colspan="3"><strong>ENTREGA</strong></td>
                    <td style="text-align: center;" colspan="4"><strong>RECIBE</strong></td>
                </tr>
                <tr>
                    <td style="text-align: center;" colspan="3">{{$bodeguero->nombres}}
                        &nbsp; {{$bodeguero->apellidos}}</td>
                    <td style="text-align: center;" colspan="4">{{$responsable->nombres}}
                        &nbsp; {{$responsable->apellidos}}</td>
                </tr>
                <tr>
                    <td style="text-align: center;" colspan="3"> {{$bodeguero->identificacion}}</td>
                    <td style="text-align: center;" colspan="4"> {{$responsable->identificacion}}</td>
                </tr>

            </table>
        </div>
    </tr>

</table>


</body>

</html>
