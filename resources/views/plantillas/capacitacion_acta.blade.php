<!DOCTYPE html>
<html lang="es">
@php
    use Illuminate\Support\Facades\Auth;

    $fechaGeneracion = new DateTime();
    $logo_principal = '';

    if (!empty($configuracion->logo_claro ?? null)) {
        $ruta_logo = public_path($configuracion->logo_claro);
        if (file_exists($ruta_logo)) {
            $logo_principal = 'data:image/png;base64,' . base64_encode(file_get_contents($ruta_logo));
        }
    }

    $razon_social = $configuracion->razon_social ?? 'Empresa';
@endphp

<head>
    <meta charset="UTF-8">
    <title>Acta de Capacitación</title>
    <style>
        @page {
            margin: 0.6cm 1.5cm 0.6cm 1.5cm;
        }
        body {
            font-family: sans-serif;
            font-size: 12px;
            color: #000000;
            padding-top: 1.6cm;
            padding-bottom: 1.6cm;
        }
        header, footer {
            position: fixed;
            left: 0cm; right: 0cm;
            height: 1.4cm;
        }
        header { top: 0cm; }
        footer { bottom: 0cm; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
        }
        th { background-color: #f0f0f0; }
        .no-border-table td { border: none !important; font-size: 11px; }
    </style>
</head>

<body>

<header>
    <table class="no-border-table">
        <tr>
            <td style="width: 10%;">
                @if ($logo_principal)
                    <img src="{{ $logo_principal }}" width="60">
                @endif
            </td>
            <td style="text-align: center;">
                <strong style="font-size: 14px;">ACTA DE CAPACITACIÓN</strong><br>
                <strong style="font-size: 12px;">Tema: {{ $capacitacion->tema }}</strong>
            </td>
            <td style="text-align: right; width: 20%;">
                {{ $razon_social }}
            </td>
        </tr>
    </table>
</header>

<footer>
    <table class="no-border-table">
        <tr>
            <td class="page">Página</td>
            <td style="text-align: center;">
                <div>Esta información es propiedad de {{ $razon_social }}. Prohibida su divulgación.</div>
                <div>Generado por: {{ Auth::user()->empleado->nombres ?? '' }}
                    {{ Auth::user()->empleado->apellidos ?? '' }}
                    el {{ $fechaGeneracion->format('d-m-Y H:i') }}
                </div>
            </td>
            <td></td>
        </tr>
    </table>
</footer>

<main>
    <p><strong>Fecha:</strong> {{ $capacitacion->fecha }}</p>
    <p><strong>Horario:</strong> {{ $capacitacion->hora_inicio }} - {{ $capacitacion->hora_fin }} (Duración: {{ $capacitacion->duracion }})</p>
    <p><strong>Modalidad:</strong> {{ $capacitacion->modalidad }}</p>
    <p><strong>Capacitador:</strong> {{ $capacitacion->capacitador->nombres }} {{ $capacitacion->capacitador->apellidos }}</p>

    <h3 style="text-align: center; margin-top: 20px;">Lista de Asistentes</h3>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Identificación</th>
                <th>Nombres</th>
                <th>Apellidos</th>
                <th>Área</th>
                <th>Firma</th>
            </tr>
        </thead>
        <tbody>
            @foreach($capacitacion->asistentes as $i => $empleado)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $empleado->identificacion }}</td>
                    <td>{{ $empleado->nombres }}</td>
                    <td>{{ $empleado->apellidos }}</td>
                    <td>{{ $empleado->departamento->nombre ?? '-' }}</td>
                    <td style="height: 40px;"></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</main>

<script type="text/php">
    if (isset($pdf)) {
        $text = "Pág {PAGE_NUM} de {PAGE_COUNT}";
        $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
        $pdf->page_text(500, 820, $text, $font, 9);
    }
</script>

</body>
</html>
