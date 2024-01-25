<!DOCTYPE html>
<html lang="en">
@php
    $logo_principal = 'data:image/png;base64,' . base64_encode(file_get_contents(public_path() . $configuracion['logo_claro']));
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estado del ticket</title>
</head>

<body>
    <!-- <h5>Estamos realizando pruebas, por favor no prestar atención a este correo.</h5> -->
    <h2>JPCONSTRUCTRED C. Ltda.</h2>
    <img src="{{ $logo_principal }}" alt="logo" width="100" height="100" />
    <p>Estimado, {{ $ticket->solicitante->nombres }} {{ $ticket->solicitante->apellidos . ', ' }}
        se le notifica que {{ $ticket->responsable->nombres . ' ' . $ticket->responsable->apellidos }} ha
        {{ $ticket->estado }} el ticket con asunto: {{ $ticket->asunto }}.
    </p>
    @if (!is_null($ticket->motivo_cancelado_ticket_id))
        <p><b>Motivo de cancelación: </b>{{ $ticket->motivoCanceladoTicket?->motivo }}</p>
    @endif

    @if ($ticket->ticketsRechazados->isNotEmpty())
        <p><b>Motivo de rechazo: </b>{{ $ticket->ticketsRechazados->last()->motivo }}</p>
    @endif

    <p>Éste correo es automático, por favor, no lo responda.</p>
</body>

</html>
