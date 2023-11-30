<!DOCTYPE html>
<html lang="es">
{{-- Aquí codigo PHP --}}
@php
    $fecha = new Datetime();
    $logo_principal = 'data:image/png;base64,' . base64_encode(file_get_contents(public_path() . $configuracion['logo_claro']));
    $logo_watermark = 'data:image/png;base64,' . base64_encode(file_get_contents(public_path() . $configuracion['logo_marca_agua']));
    // $rol_pago = $roles_pago[0];
@endphp

<head>
    <meta charset="utf-8">
    <title>Reporte de proveedores</title>
    <style>
        @page {
            margin: 0cm 15px;
            margin-top: 2%;
		    margin-bottom: 2%;
        }

        header {
            top: 0cm;
            left: 0cm;
            right: 0cm;
            height: 2.5cm;

            /** Estilos extra personales **/
            text-align: center;
            line-height: 42%;
        }

        body {
            background-image: url({{ $logo_watermark }});
            background-repeat: no-repeat;
            background-position: center;
            background-size: contain;
        }

        /** Definir las reglas del pie de página **/
        footer {
            position: fixed;
            bottom: 5px;
            font-size: 10pt;
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
            margin-top: 0%;
            top: 0px;
            left: 0cm;
            right: 0cm;
            /* margin-bottom: 5cm; */
            font-size: 12px;
        }

        div {
            color: #000000 !important;
        }

        h1 {
            text-align: center;
            text-transform: uppercase;
        }

        table.cuerpo {
            border: #b2b2b200 1px solid;
            font-size: 10pt;
            margin-top: -1.05cm;

        }

        .cuerpo td,
        .cuerpo th {
            border: black 1px solid;
        }

        table.descripcion {
            width: 100%;
        }

        .descripcion td,
        descripcion th {
            border: none;
        }


        .subtitulo-rol {
            text-align: center;
        }

        .encabezado-rol {
            text-align: center;
        }

        .encabezado-tabla-rol {
            text-align: center;
        }

        .row {
            width: 100%;
        }
    </style>
</head>


<body>
    <header>
        <table
            style="color:#000000; table-layout:fixed; width: 100%; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10pt;">
            <tr class="row" style="width:auto">
                <td>
                    <div class="col-md-3"><img src="{{ $logo_principal }}" width="90"></div>
                </td>
                <td>
                    <p class="encabezado-rol"> <strong>{{$configuracion['razon_social']}}</strong></p>
                    <p class="encabezado-rol"><strong>RUC {{$configuracion['ruc']}}</strong></p>
                    <div class="encabezado-rol"><b>REPORTE DE PROVEEDORES </b></div>
                </td>
                <td>
                    {{-- Columna vacia --}}
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
                    <div style="margin: 0%; margin-bottom: 6px; margin-top: 0px;" align="center">Esta información es
                        propiedad de {{$configuracion['razon_social']}} <br>Válida únicamente para fines autorizados.
                    </div>
                    <div style="margin: 0%; margin-bottom: 0px; margin-top: 0px;" align="center">Generado por:
                        {{ auth('sanctum')->user()->empleado->nombres }}
                        {{ auth('sanctum')->user()->empleado->apellidos }} el
                        {{ $fecha->format('d-m-Y H:i') }}
                    </div>
                </td>
                <td></td>
            </tr>
        </table>
    </footer>

    <!-- aqui va el contenido del document<br><br>o -->
    <main>
        <br>
        <table border="1" style="border-collapse:collapse; margin-bottom: 2%; padding-bottom: 6%;width: 100%" align="center">
            <thead>
                <td class="encabezado-tabla-rol"><strong>RUC</strong> </td>
                <td class="encabezado-tabla-rol"><strong>RAZON SOCIAL</strong></td>
                <td class="encabezado-tabla-rol"><strong>CIUDAD</strong></td>
                <td class="encabezado-tabla-rol"><strong>DIRECCION</strong></td>
                <td class="encabezado-tabla-rol"><strong>CELULAR</strong></td>
                <td class="encabezado-tabla-rol"><strong>ESTADO</strong></td>
                <td class="encabezado-tabla-rol"><strong>CALIFICACION</strong></td>
                <td class="encabezado-tabla-rol"><strong>CATEGORIAS</strong></td>
                <td class="encabezado-tabla-rol"><strong>DEPT. CALIFICADORES</strong></td>
            </thead>
            <tbody>
                @foreach ($reporte as $rpt)
                    <tr>
                        <td>{{ $rpt['ruc'] }}</td>
                        <td>{{ $rpt['razon_social'] }}</td>
                        <td>{{ $rpt['ciudad'] }}</td>
                        <td>{{ $rpt['direccion'] }}</td>
                        <td>{{ $rpt['celular'] }}</td>
                        <td>{{ $rpt['estado_calificado'] }}</td>
                        <td align="center">{{ $rpt['calificacion'] }}</td>
                        <td>{{ $rpt['categorias'] }}</td>
                        <td>{{ $rpt['departamentos'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </main>


</body>

</html>
