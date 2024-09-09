<!DOCTYPE html>
<html lang="en">
@php
    $logo_principal = 'data:image/png;base64,' . base64_encode(file_get_contents(public_path() . $configuracion['logo_claro']));
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bitácora vehicular generada</title>
</head>

<body>
    <h2>JPCONSTRUCTRED C. Ltda.</h2>
    <img src="{{ $logo_principal }}" alt="logo" width="100" height="100" />
    <h2> Estimado Administrador de Vehículos, {{ $bitacora->chofer->nombres }} {{ $bitacora->chofer->apellidos }} ha generado una
        bitácora vehícular. </h2>
    <p>Por favor vea el archivo adjunto con los detalles</p>

    <small>Este mensaje de correo electrónico es generado automáticamente. Por favor, no lo responda.</small>
</body>

</html>
