<?php

namespace App\Mail\Medico;

use App\Models\ConfiguracionGeneral;
use App\Models\Empleado;
use App\Models\Medico\SolicitudExamen;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class CambioFechaHoraSolicitudExamenMail extends Mailable
{
    use Queueable, SerializesModels;

    public SolicitudExamen $solicitud_examen;
    public ConfiguracionGeneral $configuracion;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(SolicitudExamen $solicitud_examen)
    {
        $this->solicitud_examen = $solicitud_examen;
        $this->configuracion = ConfiguracionGeneral::first();
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        $autorizador = $this->solicitud_examen->autorizador;
        $nombreAutorizador = Empleado::extraerNombresApellidos($autorizador);

        return new Envelope(
            from: new Address($autorizador->user->email, $nombreAutorizador),
            subject: 'Cambio de fecha y hora de solicitud de examen médico. Código: SOL.EX-' . $this->solicitud_examen->id . '.',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'email.medico.cambio_fecha_hora_solicitud',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
