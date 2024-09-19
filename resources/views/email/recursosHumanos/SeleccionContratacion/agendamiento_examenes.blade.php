<!DOCTYPE html>
<html lang="es">

<head>
    @php
        $logo_principal = 'data:image/png;base64,' . base64_encode(file_get_contents(public_path() . $configuracion['logo_claro']));
    @endphp

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notificación de Exámenes Médicos</title>
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
        <p>Nos dirigimos a ti para informarte que debes realizarte los exámenes médicos necesarios para el proceso de
            selección para el puesto de <strong>{{ $postulacion['nombre'] }}</strong>.</p>
        <p> A continuación, te proporcionamos los detalles:</p>

        @if($examen->canton_id)
            <p><strong>Ciudad:</strong> {{$canton}}</p>
        @endif
        <p><strong>Fecha y Hora:</strong> {{ $examen->fecha_hora }}</p>
        <p><strong>Laboratorio:</strong> {{ $examen->laboratorio }}</p>
        <p><strong>Dirección:</strong> {{ $examen->direccion }}</p>

        <p><strong>Instrucciones Importantes:</strong></p>
        <ul>
            <li><strong>Documentación:</strong> Lleva contigo tu cédula de identidad o documento equivalente.</li>
            <li><strong>Preparación:</strong> {{$examen->indicaciones}}.
            </li>
            <li><strong>Contacto:</strong> Si tienes alguna pregunta, contacta a:
                <ul>
                    <li><strong>Médico Ocupacional: </strong> <a href="tel:+593 96 810 3615">+593 96 810 3615</a> o
                        <a href="mailto:medico_ocupacional@jpconstrucred.com">medico_ocupacional@jpconstrucred.com.</a>
                    </li>
                    <li><strong>Recursos Humanos: </strong> <a href="tel:+593 98 890 9837">+593 98 890 9837</a> o
                        <a href="mailto:recursos_humanos@jpconstrucred.com">recursos_humanos@jpconstrucred.com</a>.
                    </li>
                </ul>
            </li>
        </ul>

        <p>Agradecemos tu cooperación y te deseamos éxito en los exámenes. Si necesitas más información, no dudes en
            ponerte en contacto con nosotros.</p>


    </div>
    <div class="footer">
        <br>
        <p>Atentamente,</p>
        <p><strong><a href="{{$url}}">FIRSTRED ERP</a></strong> <br>
            <strong>{{ $configuracion->razon_social }} </strong><br>
            <strong><a
                    href="https://{{$configuracion->sitio_web}}">{{ strtolower($configuracion->sitio_web) }}</a></strong>
        </p>
        <img src="{{ $logo_principal }}" alt="logo" width="120"/>
    </div>
    <br><br>
    <small><i>Este mensaje de correo electrónico es generado automáticamente. Por favor, no lo respondas. </i></small>
</div>
</body>

</html>
