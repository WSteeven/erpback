<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperacion de Contraseña</title>
</head>
<body>
    <h2>{{$configuracion['razon_social']}}</h2>
    @php
    $logo = 'data:image/png;base64,' . base64_encode(file_get_contents(public_path() . $configuracion['logo_claro']));
    @endphp
    <img src="{{$logo}}" alt="logo" width="100" height="100"/>
    <h2> Estimado {{ $username }} ha recibido este correo porque realizó una solicitud de recuperación de contraseña para su cuenta. </h2>
    <p>Tu codigo de confirmacion es:  {{ $confirmation_code}}</p>
</body>
</html>
