<!DOCTYPE html>
<html lang="es">

<head>
    @php
        $logo_principal = 'data:image/png;base64,' . base64_encode(file_get_contents(public_path() . $configuracion['logo_claro']));
    @endphp

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notificación de Estado de Postulación</title>
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
    </style>
</head>

<body>
<div class="container">
    <div class="content">
        <p>Estimado/a {{ $postulacion['nombres_apellidos'] }},</p>
        <br>
        <p>Gracias por tu interés en la posición de <strong>{{ $postulacion['nombre'] }}</strong> en
            <strong>{{ $configuracion->razon_social }}</strong>. Apreciamos el tiempo y el esfuerzo que dedicaste a tu
            postulación y a la entrevista y/o exámenes médicos.</p>

        <p>Después de una revisión exhaustiva, lamentamos informarte que hemos decidido proceder con otros candidatos
            que mejor se alinean con los requisitos del puesto en esta ocasión.</p>

        <p>Te animamos a que sigas visitando nuestra página de empleos para futuras oportunidades que puedan ajustarse a
            tu perfil y experiencia.</p>

        <p>Agradecemos sinceramente tu interés en formar parte de <strong>{{ $configuracion->razon_social }}</strong> y
            te deseamos mucho éxito en tu búsqueda laboral.</p>

    </div>
    <div class="footer">
        <br>
        <p>Atentamente,</p>
        <p><strong><a href="{{$url}}">FIRSTRED ERP</a></strong> <br>
            <strong>{{ $configuracion->razon_social }} </strong><br>
            <strong><a href="https://www.jpconstrucred.com">{{ strtolower($configuracion->sitio_web) }}</a></strong>
        </p>
        <img src="{{ $logo_principal }}" alt="logo" width="120"/>
    </div>
    <br><br>
    <small><i>Este mensaje de correo electrónico es generado automáticamente. Por favor, no lo respondas. </i></small>
</div>
</body>

</html>
