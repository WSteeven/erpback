<!DOCTYPE html>
<html lang="en">
@php
    use Src\Shared\Utils;
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estado del ticket</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #fff;
            color: #283593;
        }

        h2 {
            color: #0879dc;
            margin-top: 0;
        }

        p {
            margin-bottom: 20px;
            color: #000;
        }

        small {
            color: #666;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background-color: #0879dc25;
            color: #fff;
            text-align: center;
            padding: 10px 0;
        }

        .logo {
            display: block;
            margin: 0 auto;
            width: 100px;
            height: 100px;
        }

        .cuerpo {
            padding: 16px;
            border-bottom: 1px solid #ccc;
            border-left: 1px solid #ccc;
            border-right: 1px solid #ccc;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img src="{{ Utils::urlToBase64(url($configuracion->logo_claro)) }}" alt="logo" class="logo" />
            {{-- <h2>JP CONSTRUCTRED C.Ltda.</h2> --}}
        </div>

        <div class="cuerpo">

            <h3>{{ 'Código de solicitud de exámenes: SOL.EX-' . $solicitud_examen->id }}</h3>

            <p>Estimad@, {{ $solicitud_examen->solicitante->nombres }}
                {{ $solicitud_examen->solicitante->apellidos . ', ' }}
                se le notifica que
                {{ $solicitud_examen->autorizador->nombres . ' ' . $solicitud_examen->autorizador->apellidos }} ha
                actualizado las fecha y hora de asistencia para realización de exámenes médicos.
            </p>

            <small>Este mensaje de correo electrónico es generado automáticamente. Por favor, no lo responda.</small>
        </div>
    </div>
</body>

</html>
