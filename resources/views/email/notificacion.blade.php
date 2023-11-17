<!DOCTYPE html>
<html lang="en">
    @php
        $logo_principal = 'data:image/png;base64,' . base64_encode(file_get_contents(public_path() . $configuracion['logo_claro']));
    @endphp
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mensaje de Prueba</title>
</head>
<body>
    <h3>Este es un mensaje de prueba</h3>
    <img src="{{ $logo_principal }}" alt="logo" width="100" height="100"/>
</body>
</html>
