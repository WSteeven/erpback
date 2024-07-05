<?php

namespace App\Mail\Vehiculos;

use App\Models\ConfiguracionGeneral;
use App\Models\Vehiculos\BitacoraVehicular;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EnviarMailReporteBitacoraDiaria extends Mailable
{
    use Queueable, SerializesModels;
    public BitacoraVehicular $bitacora;
    public ConfiguracionGeneral $configuracion;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($bitacora)
    {
        $this->bitacora = $bitacora;
        $this->configuracion = ConfiguracionGeneral::first();
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            from: "MODULO VEHICULOS",
            subject: 'Reporte Bitacora Vehicular',
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
            view: 'email.vehiculos.bitacora_vehicular',
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
