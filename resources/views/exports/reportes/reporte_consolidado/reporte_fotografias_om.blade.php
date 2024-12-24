<!DOCTYPE html>
<html lang="es">

@php
    use Carbon\Carbon;

    $fecha = Carbon::now();
    $logo_watermark ='data:image/png;base64,' . base64_encode(file_get_contents(public_path() . $configuracion->logo_marca_agua));
    $copyright = 'Esta informacion es propiedad de ' . $configuracion->razon_social . ' - Prohibida su divulgacion';

@endphp

<head>
    <meta charset="utf-8">
    <title>Fotografías de Gastos</title>
    <style>
        @page {
            margin: 2px 15px 5px 15px;
        }

        body {
            background-image: url({{ $logo_watermark }});
            background-repeat: no-repeat;
            background-position: center;
            background-size: contain;

            /** Define el texto **/
            font-family: Arial, sans-serif;
        }

        /** Definir las reglas del encabezado **/
        header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 2cm;

            /** Estilos extra personales **/
            text-align: center;
            line-height: 1.5cm;
        }

        /** Definir las reglas del pie de página **/
        footer {
            position: fixed;
            bottom: 5px;
            left: 0;
            right: 0;
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
            left: 0;
            right: 0;
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

        .header-table td {
            line-height: normal;
            vertical-align: center;
        }

    </style>
</head>
<body>
<header>
    <table class="header-table"
           style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:14px;">
        <tr class="row" style="width:auto">
            <td style="width: 10%">
                <div class="col-md-3">
                    @if(file_exists(public_path($configuracion->logo_claro)))
                        <img src="{{ url($configuracion->logo_claro) }}" width="90" alt="Logo">
                    @endif
                </div>
            </td>
            <td style="width: 68%">
                <div style="text-align: center"><b>{{ $titulo }}</b>
                </div>
            </td>
            <td style="width: 22%">
            </td>
        </tr>
    </table>
</header>
<footer>
    <table style="width: 100%;">
        <tr>
            <td style="line-height: normal;">
                <div style="margin: 0; text-align: center">{{ $copyright }}
                </div>
                <div style="margin: 0; text-align: center">Generado por el
                    Usuario:
                    {{ auth('sanctum')->user()->empleado->nombres }}
                    {{ auth('sanctum')->user()->empleado->apellidos }} el
                    {{ $fecha->format('d-m-Y H:i') }}
                </div>
            </td>
        </tr>
    </table>
</footer>
<main>
    @foreach($gastos as $index => $gasto)
{{--        <h3>Ciudad: {{$index}}</h3>--}}
        @foreach($gasto as $grupo => $registros)
            <h4>Grupo: {{$grupo?? 'Sin Grupo'}}</h4> <br>
            <div style="width: 100%; margin-top: 10px">
                <table style="width: 100%; border-collapse: collapse">
                    <tr>
                        @foreach($registros as $index => $registro)
                            <td style="text-align: center">
                                <a href="{{ url($registro['comprobante']) }}" target="_blank" title="comprobante">
                                    <img src="{{ url($registro['comprobante']) }}"
                                         style="width: 100%; max-width: 150px; height: auto;" alt="imagen"/>
                                </a>
                            </td>
                            @if(($index+1)%4===0)
                    </tr>
                    <tr>
                        @endif
                        @endforeach
                    </tr>
                </table>
            </div>
        @endforeach
    @endforeach
</main>
</body>

</html>
