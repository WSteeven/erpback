@php
    use Carbon\Carbon;
@endphp
    <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Notificación de Excepción - FIRSTRED - {{$configuracion->razon_social}}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            color: #333;
            padding: 20px;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #c0392b;
        }

        p {
            line-height: 1.5;
        }

        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #999;
        }

        pre {
            background-color: #f4f4f4;
            padding: 10px;
            border-radius: 4px;
            overflow-x: auto;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>⚠️ Se ha producido una excepción en el sistema</h1>

    <p><strong>Fecha y hora:</strong> {{ Carbon::now()->format('d/m/Y H:i:s') }}</p>

    <p><strong>Mensaje de la excepción:</strong></p>
    <pre>{{ $mensaje }}</pre>

    <p>Por favor, revise el sistema para resolver este problema.</p>

    <div class="footer">
        Este es un mensaje automático generado por el sistema.<br>
        Si tiene dudas, contacte al equipo de soporte.
    </div>
</div>
</body>
</html>
