<!DOCTYPE html>
<html lang="es">

<head>
    @php
        $logo_principal = 'data:image/png;base64,' . base64_encode(file_get_contents(public_path() . $configuracion['logo_claro']));
    @endphp

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Entrevista</title>
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
        <p>Nos complace informarte que has sido seleccionado/a para el puesto de
            <strong>{{ $postulacion['nombre'] }}</strong> en <strong>{{ $configuracion->razon_social }}</strong>.
            Queremos felicitarte por tu excelente desempeño durante el proceso de entrevistas y por tu interés en formar
            parte de nuestro equipo.</p>

        <p>Como último paso antes de formalizar tu contratación, necesitamos que te realices unos exámenes médicos para
            asegurarnos de que todo está en orden desde el punto de vista de la salud. Este proceso es un requisito
            estándar para todos los nuevos empleados y se realiza para garantizar la seguridad y el bienestar en el
            entorno laboral.</p>

        <p>Nuestro <strong>médico ocupacional</strong> se pondrá en contacto contigo para coordinar y notificarte el
            día, hora y lugar donde deberás realizarte los exámenes.</p>

        <p>En caso de que nuestro médico ocupacional nos comunique que los resultados son favorables, procederemos a
            formalizar tu contratación de inmediato. En caso contrario, lamentablemente no podremos continuar con el
            proceso de contratación</p>

        <p>Si tienes alguna pregunta o necesitas más información, no dudes
            en ponerte en contacto con nosotros al <a href="tel:+593 98 890 9837">+593 98 890 9837</a> o a
            <a href="mailto:recursos_humanos@jpconstrucred.com">recursos_humanos@jpconstrucred.com</a></p>


        <p>Agradecemos tu interés en <strong>{{ $configuracion->razon_social }}</strong> y esperamos que este sea el
            comienzo de una fructífera colaboración.
        </p>


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
