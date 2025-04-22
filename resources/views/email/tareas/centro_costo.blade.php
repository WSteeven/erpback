<!DOCTYPE html>
<html lang="en">
@php
    use Src\Shared\Utils;
    // $logo_principal = 'data:image/png;base64,' . base64_encode(file_get_contents(public_path() . $configuracion['logo_claro']));
    $logo_principal = Utils::urlToBase64(url($configuracion['logo_claro']));
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Centro de Costos Creado</title>
</head>

<body>
    <h2>JPCONSTRUCTRED C. Ltda.</h2>
    <img src="{{ $logo_principal }}" alt="logo" width="100" height="100" />
    <h2> Estimado contador, {{ Auth::user()->empleado->nombres }} {{ Auth::user()->empleado->apellidos }} ha creado una
        tarea y se generado un centro de costos nuevo. </h2>
    <p>Información:</p>
    <table style="width: 50%; border-collapse: collapse; border: 1px;">
        <tbody>
            <tr>
                <td style="width: 50%;"><strong>Nombre</strong></td>
                <td style="width: 50%;">{{ $centro_costo->nombre }}</td>
            </tr>
            <tr>
                <td style="width: 50%;"><strong>Cliente</strong></td>
                <td style="width: 50%;">{{ $centro_costo->cliente->empresa->razon_social }}</td>
            </tr>
            <tr>
                <td style="width: 50%;"><strong>Fecha de creación</strong></td>
                <td style="width: 50%;">{{ $centro_costo->created_at }}</td>
            </tr>
        </tbody>
    </table>

    <p>Este correo es automático, por favor, no lo responda.</p>
</body>

</html>
