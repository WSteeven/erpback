<?php

namespace Src\App\Medico;

use App\Http\Resources\Medico\CitaMedicaResource;
use App\Models\Medico\CitaMedica;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CitaMedicaService
{
    public function cancelar(CitaMedica $cita_medica)
    {
        $cita_medica->estado_cita_medica = CitaMedica::CANCELADO;
        $cita_medica->fecha_hora_cancelado = Carbon::now();
        $cita_medica->motivo_cancelacion = request('motivo_cancelacion');
        $cita_medica->save();

        $modelo = new CitaMedicaResource($cita_medica->refresh());
        // $mensaje = 'Cita médica cancelada exitosamente!';

        // Mail::to($ticket->responsable->user->email)->send(new EnviarMailTicket($ticket));

        // event(new TicketEvent($ticket->refresh(), $modelo->solicitante_id, $modelo->responsable_id));
        // event(new ActualizarNotificacionesEvent());
        return $modelo;
        // return response()->json(compact('modelo', 'mensaje'));
    }

    public function rechazar(CitaMedica $cita_medica)
    {
        $cita_medica->estado_cita_medica = CitaMedica::RECHAZADO;
        $cita_medica->fecha_hora_rechazo = Carbon::now();
        $cita_medica->motivo_rechazo = request('motivo_rechazo');
        $cita_medica->save();

        $modelo = new CitaMedicaResource($cita_medica->refresh());
        $mensaje = 'Cita médica rechazada exitosamente!';

        // Mail::to($ticket->responsable->user->email)->send(new EnviarMailTicket($ticket));

        // event(new TicketEvent($ticket->refresh(), $modelo->solicitante_id, $modelo->responsable_id));
        // event(new ActualizarNotificacionesEvent());

        return response()->json(compact('modelo', 'mensaje'));
    }
}
