<!DOCTYPE html>
<html lang="es">

<head>
    @php
        use Src\Shared\Utils;
    @endphp

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reprogramación de Entrevista</title>
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
        <p>Te informamos que tu entrevista para el puesto de
            <strong>{{ $postulacion['nombre'] }}</strong> en
            <strong>{{ $configuracion->razon_social }}</strong> ha sido reprogramada. A continuación, los nuevos
            detalles:</p>

        @if($entrevista->canton_id && $entrevista->presencial)
            <p><strong>Ciudad:</strong> {{$canton}}</p>
        @endif

        <p><strong>Nueva Fecha y Hora:</strong> {{$entrevista->nueva_fecha_hora}}</p>
        <p><strong>Duración Aproximada:</strong> {{$entrevista->duracion}} minutos</p>
        <p><strong>Modalidad:</strong> {{ $entrevista->presencial ? 'Presencial' : 'Virtual' }}</p>

        @if($entrevista->presencial)
            <p><strong>Ubicación:</strong> {{$entrevista->direccion}}</p>
        @else
            <p><strong>Link de la reunión:</strong> <a href="{{ $entrevista->link }}"
                                                       target="_blank">{{ $entrevista->link }}</a></p>
        @endif

        <p><strong>Instrucciones Importantes:</strong></p>
        <ul>
            <li><strong>Llega con al menos 15 minutos de antelación:</strong> Para completar cualquier formalidad previa
                y acomodarte antes de que comience la entrevista.
            </li>
            <li><strong>Documentación:</strong> Trae tu currículum actualizado y cualquier material relevante.</li>
            <li><strong>Contacto:</strong> Para reprogramaciones adicionales o dudas, comunícate al <a
                    href="tel:{{$departamento_rrhh->telefono}}">{{$departamento_rrhh->telefono}}</a> o a <a
                    href="mailto:{{$departamento_rrhh->correo}}">{{$departamento_rrhh->correo}}</a>.
            </li>
        </ul>

        <p>Agradecemos tu interés en <strong>{{ $configuracion->razon_social }}</strong> y esperamos tu participación en
            la nueva fecha y hora.</p>
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
