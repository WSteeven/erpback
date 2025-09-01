<html>

@php
    use App\Models\Empleado;
    use Src\Shared\Utils;
    $fecha = new Datetime();
    $copyright ='Esta informacion es propiedad de ' . $configuracion->razon_social . ' - Prohibida su divulgacion';
@endphp
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>REPORTE VALIJAS DEL {{$peticion['fecha_inicio']}} AL {{$peticion['fecha_fin']}}</title>
    <style>
        @page {
            margin: 2px 15px 5px 15px;
        }

        body {
            background-image: url({{ Utils::urlToBase64(url($configuracion->logo_marca_agua)) }});
            background-size: 50% auto;
            background-repeat: no-repeat;
            background-position: center;
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

        table {
            table-layout: auto;
            /* Adjust column width to fit content */
            width: 100%;
            font-family: Verdana, Arial, Helvetica, sans-serif;
            font-size: 12px;
        }

        table td:nth-child(11) {
            max-width: 10%;
            /* Set max width for "Autorizador" column */
        }

        .main-table {
            color: #000000 !important;
            table-layout: fixed;
            width: 100%;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            font-size: 10px;
            margin-top: 20px;
            border: #000000 1px solid;
            border-collapse: collapse;
        }

        .main-table th,
        .main-table td {
            border: 1px solid #000000;
            padding: 5px; /* opcional, para que no se vea muy pegado */
        }

        .row {
            width: 100%;
        }
    </style>
</head>
<body>
<header>
    <table
        style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:18px;">
        <tr class="row" style="width:auto">
            <td style="width: 10%;">
                <div class="col-md-3"><img src="{{ Utils::urlToBase64(url($configuracion->logo_claro)) }}" width="90"
                                           alt="logo"></div>
            </td>
            <td style="width: 100%">
                <div class="col-md-7"><b style="font-size: 75%; align-items: center">REPORTE VALIJAS
                        DEL {{$peticion['fecha_inicio']}} AL {{$peticion['fecha_fin']}}</b>
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
                <div style="margin: 0 0; text-align: center">{{ $copyright }}
                </div>
                <div style="margin: 0 0; text-align: center">Generado por el
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
    <table
        class="main-table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Courier</th>
            <th>Foto Guía</th>
            <th>Fecha</th>
            <th>Gasto ID</th>
            <th>N° Factura</th>
            <th>Empleado Envía</th>
            <th>Departamento Destino</th>
            <th>Descripción</th>
            <th>Destinatario</th>
            <th>Imagen</th>
        </tr>
        </thead>
        <tbody>
        @foreach($reporte as $valija)
            <tr>
                <td>{{ $valija->id }}</td>
                <td>{{ $valija->envioValija->courier }}</td>
                <td style="vertical-align: middle; overflow: hidden">
                    @if($valija->envioValija->fotografia_guia)
                        <a href="{{ url($valija->envioValija->fotografia_guia) }}" target="_blank">
                            <img src="{{ Utils::urlToBase64(url($valija->envioValija->fotografia_guia)) }}" alt="Evidencia"
                                 width="100">
                        </a>
                    @else
                        Sin imagen
                    @endif
                </td>
                <td>{{ $valija->created_at }}</td>
                <td>{{ $valija->envioValija->gasto_id }}</td>
                <td>{{ $valija->envioValija->gasto->factura ?? $valija->envioValija->gasto->num_comprobante ?? '' }}</td>
                <td>{{ Empleado::extraerNombresApellidos($valija->envioValija->empleado) }}</td>
                <td>{{ $valija->departamento?->nombre }}</td>
                <td>{{ $valija->descripcion }}</td>
                <td>{{ Empleado::extraerNombresApellidos($valija->destinatario) ?? Empleado::extraerNombresApellidos($valija->departamento->responsable) ?? 'N/A' }}</td>
                <td style="vertical-align: middle; overflow: hidden">
                    @if($valija->imagen_evidencia)
                        <a href="{{ url($valija->imagen_evidencia) }}" target="_blank">
                            <img src="{{ Utils::urlToBase64(url($valija->imagen_evidencia)) }}" alt="Evidencia"
                                 width="100">
                        </a>
                    @else
                        Sin imagen
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</main>
</body>
</html>
