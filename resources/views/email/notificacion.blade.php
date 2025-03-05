<!DOCTYPE html>
<html lang="en">
    @php
        use Src\Shared\Utils;
    @endphp
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mensaje de Prueba</title>
</head>
<body>
    <h3>Este es un mensaje de prueba</h3>
    <img src="{{ Utils::urlToBase64(url($configuracion->logo_claro)) }}" alt="logo" width="100" height="100"/>
</body>
</html>
