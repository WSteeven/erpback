@php
    use Carbon\Carbon;
@endphp
    <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Notificación del Sistema - FIRSTRED - {{ $configuracion->razon_social ?? 'Sistema' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            padding: 20px;
        }

        .container {
            background-color: #ffffff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #2c3e50;
            font-size: 22px;
        }

        p {
            line-height: 1.6;
        }

        .info-message {
            background-color: #e8f0fe;
            border-left: 4px solid #3498db;
            padding: 15px;
            margin-top: 20px;
            border-radius: 5px;
            font-family: monospace;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 14px;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        table th {
            background-color: #3498db;
            color: #ffffff;
            text-align: left;
        }

        .footer {
            margin-top: 25px;
            font-size: 12px;
            color: #777;
            border-top: 1px solid #ccc;
            padding-top: 15px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>ℹ️ Notificación del sistema</h1>

    <p><strong>Fecha y hora:</strong> {{ Carbon::now()->format('d/m/Y H:i:s') }}</p>

    <div class="info-message">
        {{ $mensaje }}
    </div>

    @if(!empty($datos) && is_array($datos))
        <table>
            <thead>
            <tr>
                @foreach(array_keys((array) $datos) as $key)
                    <th>{{ ucwords(str_replace('_', ' ', $key)) }}</th>
                @endforeach
            </tr>
            </thead>
            <tbody>
            <tr>
                @foreach((array) $datos as $valor)
                    <td>{{ is_array($valor) || is_object($valor) ? json_encode($valor) : $valor }}</td>
                @endforeach
            </tr>
            </tbody>
        </table>
    @endif



    <div class="footer">
        Este es un mensaje automático generado por el sistema.<br>
        Si tiene dudas, contacte al equipo de soporte técnico.
    </div>
</div>
</body>
</html>
