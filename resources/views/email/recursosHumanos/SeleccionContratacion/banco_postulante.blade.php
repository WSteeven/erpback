<!DOCTYPE html>
<html lang="es">

<head>
    @php
        use Src\Shared\Utils;
    @endphp

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualización sobre tu Postulación</title>
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
        <p>Espero que este mensaje te encuentre bien.</p>

        <p>Queremos agradecerte sinceramente por tu interés en el puesto de
            <strong>{{ $postulacion['nombre'] }}</strong> en <strong>{{ $configuracion->razon_social }}</strong> y por
            el tiempo dedicado a tu postulación.</p>
        <p>Después de revisar tu perfil y experiencia, hemos determinado que, aunque no se ajusta completamente a los
            requisitos del puesto al que postulaste, tu experiencia y habilidades podrían ser adecuadas para otros roles
            dentro de nuestra organización.</p>
        <p>Por esta razón, hemos decidido reclasificar tu candidatura en nuestro banco de postulantes. Esto significa
            que tu perfil será considerado para futuras vacantes que puedan alinearse mejor con tu experiencia y
            habilidades.</p>
        <p>En caso de que se abra una vacante que se ajuste a tu perfil, nos pondremos en contacto contigo para explorar
            posibles oportunidades. Por el momento, el proceso de selección para esta vacante ha concluido.</p>

        <p>Agradecemos nuevamente tu interés en <strong>{{$configuracion->razon_social}}</strong> y te deseamos mucho
            éxito en tus futuros proyectos profesionales.</p>


    </div>
    <div class="footer">
        <br>
        <p>Atentamente,</p>
        <p><strong><a href="{{$url}}">FIRSTRED ERP</a></strong> <br>
            <strong>{{ $configuracion->razon_social }} </strong><br>
            <strong><a
                    href="https://{{$configuracion->sitio_web}}">{{ strtolower($configuracion->sitio_web) }}</a></strong>
        </p>
        <img src="{{ Utils::urlToBase64(url($configuracion->logo_claro)) }}" alt="logo" width="120"/>
    </div>
    <br><br>
    <small><i>Este mensaje de correo electrónico es generado automáticamente. Por favor, no lo respondas. </i></small>
</div>
</body>

</html>
