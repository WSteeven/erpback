<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualización de Estado de Postulación</title>
    <style>
        body {
            font-family: Arial, sans-serif;
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
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #007BFF;
            color: #fff;
            padding: 10px;
            border-radius: 8px 8px 0 0;
            text-align: center;
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
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Actualización de tu Postulación</h1>
        </div>
        <div class="content">
            <p>Estimado/a {{ $postulacion->nombres_apellidos }},</p>

            <p>¡Gracias por postularte para el puesto de <strong>{{ $postulacion->nombre }}</strong> en <strong>{{ $configuracion->razon_social}}</strong>!</p>

            <p>Queremos informarte que hemos recibido tu solicitud y que nuestro equipo de reclutamiento está en proceso de revisión.</p>

            <p>Queremos asegurarte que hemos visto tu postulación y que la estamos evaluando detenidamente. En breve, uno de nuestros reclutadores se pondrá en contacto contigo para proporcionarte más detalles sobre los próximos pasos del proceso de selección, si es que tu perfil avanza a la siguiente etapa.</p>

            <p>Te agradecemos nuevamente por tu interés en unirte a <strong>{{ $configuracion->razon_social}}</strong> y por tomarte el tiempo de postularte.</p>

            <p>¡Te deseamos mucha suerte!</p>


            <small>Este mensaje de correo electrónico es generado automáticamente. Por favor, no lo responda.</small>
        </div>
        <div class="footer">
            <p>Atentamente,</p>
            <p>Dept. Recursos Humanos <br>
               {{ $configuracion->razon_social}} <br>
               recursos_humanos@jpconstrucred.com <br>
               <a href="www.jpconstrucred.com" style="color: #007BFF;">{{ strtolower($configuracion->sitio_web) }}</a>
            </p>
        </div>
    </div>
</body>
</html>
