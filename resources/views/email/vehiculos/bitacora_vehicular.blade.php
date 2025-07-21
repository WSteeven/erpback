<!DOCTYPE html>
<html lang="en">
@php
    use Src\Shared\Utils;
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bitácora vehicular generada</title>
</head>

<body>
    <h2>JPCONSTRUCTRED C. Ltda.</h2>
    <img src="{{ Utils::urlToBase64(url($configuracion->logo_claro)) }}" alt="logo" width="100" height="100" />
    <h2> Estimado Administrador de Vehículos, {{ $bitacora->chofer->nombres }} {{ $bitacora->chofer->apellidos }} ha generado una
        bitácora vehícular. </h2>
    <p>Por favor vea el archivo adjunto con los detalles</p>

    <small>Este mensaje de correo electrónico es generado automáticamente. Por favor, no lo responda.</small>
</body>

</html>
