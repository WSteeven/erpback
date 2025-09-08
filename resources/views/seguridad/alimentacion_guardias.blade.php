<!DOCTYPE html>
<html lang="es">
@php
    use Illuminate\Support\Arr;

    $fecha = new DateTime();
    $logo_principal = '';
    /* $logo_principal = Utils::urlToBase64(url($configuracion['logo_claro'])); */

    if (!empty($configuracion->logo_claro ?? null)) {
        $ruta_logo = public_path($configuracion->logo_claro);
        if (file_exists($ruta_logo)) {
            $logo_principal = 'data:image/png;base64,' . base64_encode(file_get_contents($ruta_logo));
        }
    }

    $razon_social = $configuracion->razon_social ?? ($configuracion['razon_social'] ?? 'Empresa');
@endphp

<head>
    <meta charset="UTF-8">
    <title>Reporte General de Alimentación</title>
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
            left: 0cm;
            right: 0cm;
            height: 1.4cm;
        }

        header {
            top: 0cm;
        }

        footer {
            bottom: 0cm;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
        }

        th {
            background-color: #f0f0f0;
        }

        .no-border-table td {
            border: none !important;
            padding: 0px 2px;
            font-size: 11px;
        }

        .guardia-titulo {
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 5px;
            font-size: 14px;
        }

        .total {
            font-weight: bold;
            text-align: right;
            margin-top: 10px;
        }

        footer .page:after {
            content: counter(page);
        }
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
                <strong style="font-size: 12px;">REPORTE GENERAL DE ALIMENTACIÓN DE GUARDIAS POR {{ $fecha->format('d-m-Y') }}</strong>
            </td>
            <td style="text-align: right; width: 20%;">
                Sistema de Seguridad
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
                <div>
                    Generado por: {{ auth('sanctum')->user()->empleado->nombres ?? '' }}
                    {{ auth('sanctum')->user()->empleado->apellidos ?? '' }} el {{ $fecha->format('d-m-Y H:i') }}
                </div>
            </td>
            <td></td>
        </tr>
    </table>
</footer>

<main>
    @foreach ($detalle ?? [] as $item)
        @php
            $guardia = $item['guardia'] ?? ($total['guardia'] ?? 'SIN NOMBRE');
            $detalleGuardia = $item['detalle'] ?? $detalle ?? [];
            $montoGuardia = $item['monto_total'] ?? ($total['monto_total'] ?? 0);
        @endphp

        <div class="guardia-titulo">Guardia: {{ $guardia }}</div>

        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Zona</th>
                    <th>Jornadas</th>
                    <th>Valor ($)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($detalleGuardia as $r)
                    <tr>
                        <td>{{ $r['fecha'] ?? '-' }}</td>
                        <td>{{ $r['zona'] ?? '-' }}</td>
                        <td>
                            {{ is_array($r['jornadas'] ?? null) ? implode(', ', $r['jornadas']) : '-' }}
                        </td>
                        <td>{{ number_format($r['monto'] ?? 0, 2, '.', '') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total">
            TOTAL GUARDIA: <strong>${{ number_format($montoGuardia, 2, ',', '.') }}</strong>
        </div>
    @endforeach

    <hr style="margin-top: 30px; margin-bottom: 10px;">

    <div class="total" style="font-size: 14px;">
        TOTAL GENERAL DE ALIMENTACIÓN:
        <strong>${{ number_format($total['monto_total'] ?? 0, 2, ',', '.') }}</strong>
    </div>
</main>


<script type="text/php">
    if (isset($pdf)) {
        $text = "Pág {PAGE_NUM} de {PAGE_COUNT}";
        $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
        $pdf->page_text(10, 820, $text, $font, 10);
    }
</script>

</body>
</html>
