<!DOCTYPE html>
<html lang="en">
@php
    use Src\Shared\Utils;
@endphp
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperacion de Contraseña</title>
</head>
<body>
    <h2>{{$configuracion->razon_social}}</h2>

{{--    <img src="{{Utils::urlToBase64(url($configuracion->logo_claro))}}" alt="logo" width="100" height="100"/>--}}
    <h2> Estimado {{ $credenciales['username'] }} ha recibido este correo porque realizó una solicitud de recuperación de contraseña para su cuenta. </h2>
    <p>Tu codigo de confirmacion es:  {{ $credenciales['confirmation_code']}}</p>
</body>
</html>
