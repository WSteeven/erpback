<!DOCTYPE html>
<html lang="es">

<head>
    @php
        use Src\Shared\Utils;
        use App\Models\Empleado;
    @endphp

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Empleado desvinculado</title>
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
        <h3>Desvinculación de Empleado</h3>
        <p>El empleado <strong>{{ Empleado::extraerNombresApellidos($empleado) }}</strong> ha sido desvinculado.</p>
        <p><strong>Motivo:</strong> {{ $empleado->motivo_desvinculacion }}</p>
        <p><strong>Fecha de salida:</strong> {{ $empleado->fecha_salida }}</p>
        <p>Se adjunta el reporte con los detalles y pendientes.</p>

    </div>
    <div class="footer">
        <br>
        <p>Atentamente,</p>
        <p><strong><a href="{{$url}}">FIRSTRED ERP</a></strong> <br>
            <strong>{{ $configuracion->razon_social }} </strong><br>
            <strong><a href="https://{{$configuracion->sitio_web}}">{{ strtolower($configuracion->sitio_web) }}</a></strong>
        </p>
        <img src="{{Utils::urlToBase64(url($configuracion->logo_claro)) }}" alt="logo" width="120"/>
    </div>
    <br><br>
    <small><i>Este mensaje de correo electrónico es generado automáticamente. Por favor, no lo respondas. </i></small>
</div>
</body>

</html>
