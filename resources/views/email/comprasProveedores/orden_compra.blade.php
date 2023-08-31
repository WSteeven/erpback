<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orden de compra generada</title>
</head>
<body>
    <h2>JPCONSTRUCTRED C. Ltda.</h2>
    <img src="img/logo.png" alt="logo" width="100" height="100"/>
    <h2> Estimado Proveedor, {{ $orden->solicitante->nombres }}  {{ $orden->solicitante->apellidos }} ha generado una orden de compra para usted. </h2>
    <p>Por favor vea el archivo adjunto y sirvase a proveernos lo indicado en esta orden de compra</p>
</body>
</html>
