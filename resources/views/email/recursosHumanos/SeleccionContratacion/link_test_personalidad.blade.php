<!DOCTYPE html>
<html lang="es">

<head>
    @php
        use Src\Shared\Utils;
    @endphp

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evaluación de Personalidad 16PF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            width: 80%;
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .content {
            margin: 20px 0;
        }

        .footer {
            margin-top: 20px;
            font-size: 0.9em;
            text-align: center;
            color: #666;
        }

        a {
            color: #007BFF;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .btn {
            display: inline-block;
            padding: 12px 20px;
            font-size: 14px;
            font-weight: bold;
            color: #fff !important;
            background-color: #007BFF;
            border-radius: 6px;
            text-decoration: none;
            margin-top: 20px;
        }

        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
<div class="container">
    <div class="content">
        <p>Estimado/a {{ $postulacion['nombres_apellidos'] }},</p>
        <br>
        <p>Como parte del proceso de selección para el puesto de
            <strong>{{ $postulacion['nombre'] }}</strong> en
            <strong>{{ $configuracion->razon_social }}</strong>, es necesario que completes la
            <strong>Evaluación de Personalidad 16PF</strong>.</p>

        <p>Tu participación en esta evaluación es indispensable para continuar con el proceso de selección.
            Te recordamos que:</p>
        <ul>
            <li><strong>La evaluación debe completarse en su totalidad:</strong> No hacerlo podría afectar los resultados obtenidos.</li>
            <li><strong>No hacerla será motivo de descarte:</strong> Es un requisito obligatorio para avanzar en el proceso.</li>
            <li><strong>Tienes 48 horas a partir de este email para realizar esta evaluación:</strong> En caso de no hacerlo, se asumirá que no tienes interés en continuar y tu postulación será descartada automáticamente.</li>
        </ul>

        <p>Para iniciar la evaluación, haz clic en el siguiente botón:</p>
        <p style="text-align: center;">
            <a href="{{ $linkEvaluacion }}" target="_blank" class="btn">Completar Evaluación 16PF</a>
        </p>

        <p>Te recomendamos realizar la prueba en un lugar tranquilo, sin interrupciones, y con una conexión a internet estable.</p>
    </div>

    <div class="footer">
        <br>
        <p>Atentamente,</p>
        <p><strong><a href="{{$url}}">FIRSTRED ERP</a></strong> <br>
            <strong>{{ $configuracion->razon_social }}</strong> <br>
            <strong><a href="https://{{$configuracion->sitio_web}}">{{ strtolower($configuracion->sitio_web) }}</a></strong>
        </p>
        <img src="{{ Utils::urlToBase64(url($configuracion->logo_claro)) }}" alt="logo" width="120"/>
    </div>
    <br><br>
    <small><i>Este mensaje de correo electrónico es generado automáticamente. Por favor, no lo respondas. </i></small>
</div>
</body>

</html>
