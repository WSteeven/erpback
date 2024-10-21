<!DOCTYPE html>
<html lang="es">

<head>
    @php
        $logo_principal = 'data:image/png;base64,' . base64_encode(file_get_contents(public_path() . $configuracion['logo_claro']));
    @endphp

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>¡Felicidades! Has Sido Contratado</title>
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
        <p>¡Hola {{ $postulacion['nombres_apellidos'] }}!</p>
        <br>

        <p>¡Nos complace anunciarte que has sido <strong>contratado</strong> para el puesto de
            <strong>{{ $postulacion['nombre'] }}</strong> en <strong>{{ $configuracion->razon_social }}</strong>!</p>

        <p>Después de un exhaustivo proceso de selección, estamos encantados de contar contigo en nuestro equipo en esta
            valiosa oportunidad.</p>

        <p>Para prepararte para tu nuevo rol, nuestro equipo de Recursos Humanos se pondrá en contacto contigo en las
            próximas horas para proporcionarte los detalles específicos sobre tu primer día y cualquier capacitación
            necesaria.</p>


        <p>Si tienes alguna pregunta o necesitas información adicional, no dudes en contactarnos. Puedes escribirnos a
            <a href="mailto:{{$departamento_rrhh->correo}}">{{$departamento_rrhh->correo}}</a> o llamarnos al <a
                href="tel:{{$departamento_rrhh->telefono}}">{{$departamento_rrhh->telefono}}</a>.</p>

        <p>Estamos emocionados por tenerte a bordo y deseamos verte en tu primer día. ¡Felicidades nuevamente y
            bienvenido a la familia de <strong>{{ $configuracion->razon_social }}</strong>!</p>

    </div>
    <div class="footer">
        <br>
        <p>Atentamente,</p>
        <p><strong><a href="{{$url}}">FIRSTRED ERP</a></strong> <br>
            <strong>{{ $configuracion->razon_social }} </strong><br>
            <strong><a href="https://{{$configuracion->sitio_web}}">{{ strtolower($configuracion->sitio_web) }}</a></strong>
        </p>
        <img src="{{ $logo_principal }}" alt="logo" width="120"/>
    </div>
    <br><br>
    <small><i>Este mensaje de correo electrónico es generado automáticamente. Por favor, no lo respondas. </i></small>
</div>
</body>

</html>
