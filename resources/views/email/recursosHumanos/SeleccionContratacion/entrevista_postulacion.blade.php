<!DOCTYPE html>
<html lang="es">

<head>
    @php
        use Src\Shared\Utils;
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
        <p>Nos complace informarte que hemos programado una entrevista para el puesto de
            <strong>{{ $postulacion['nombre'] }}</strong> en
            <strong>{{ $configuracion->razon_social }}</strong>. A continuación, te proporcionamos los
            detalles de la entrevista:</p>
        @if($entrevista->canton_id && $entrevista->presencial)
        <p><strong>Ciudad:</strong> {{$canton}}</p>
        @endif
        <p><strong>Fecha y hora:</strong> {{$entrevista->fecha_hora}}</p>
        <p><strong>Duración Aproximada:</strong> {{$entrevista->duracion}} minutos</p>
        <p><strong>Ubicación de la Entrevista:</strong></p>
        @if($entrevista->presencial)
            <p>{{$entrevista->direccion}}</p>
        @else
            <p>Virtual (Zoom)</p>
            <p><strong>Link:</strong> <a href="{{ $entrevista->link }}" target="_blank">{{ $entrevista->link }}</a></p>
        @endif
        <p><strong>Instrucciones Importantes:</strong></p>
        <ul>
            <li><strong>Llega con al menos 15 minutos de antelación:</strong> Te pedimos que llegues al lugar de la
                entrevista con al menos 15 minutos de antelación para completar cualquier formalidad previa y para que
                puedas acomodarte antes de que comience la entrevista.
            </li>
            <li><strong>Documentación:</strong> Por favor, trae contigo una copia actualizada de tu currículum y
                cualquier otro material que consideres relevante para la entrevista.
            </li>
            <li><strong>Contacto:</strong> Si necesitas reprogramar la entrevista o si tienes alguna pregunta, no dudes
                en ponerte en contacto con nosotros al <a href="tel:{{$departamento_rrhh->telefono}}">{{$departamento_rrhh->telefono}}</a> o a
                <a href="mailto:{{$departamento_rrhh->correo}}">{{$departamento_rrhh->correo}}</a>.
            </li>
        </ul>
        <p>Agradecemos tu interés en <strong>{{ $configuracion->razon_social }}</strong> y esperamos conocerte en la
            fecha y hora
            acordadas. Si tienes alguna duda o necesitas más información, no dudes en ponerte en contacto con nosotros.
        </p>


    </div>
    <div class="footer">
        <br>
        <p>Atentamente,</p>
        <p><strong><a href="{{$url}}">FIRSTRED ERP</a></strong> <br>
            <strong>{{ $configuracion->razon_social }} </strong><br>
            <strong><a href="https://{{$configuracion->sitio_web}}">{{ strtolower($configuracion->sitio_web) }}</a></strong>
        </p>
        <img src="{{ Utils::urlToBase64(url($configuracion->logo_claro)) }}" alt="logo" width="120"/>
    </div>
    <br><br>
    <small><i>Este mensaje de correo electrónico es generado automáticamente. Por favor, no lo respondas. </i></small>
</div>
</body>

</html>
