<?php

namespace App\Mail\VentasClaro;

use App\Models\ConfiguracionGeneral;
use App\Models\Ventas\Venta;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EnviarMailVentaSuspendida extends Mailable
{
    use Queueable, SerializesModels;

    public Venta $venta;
    public ConfiguracionGeneral $configuracion;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($venta)
    {
        $this->venta = $venta;
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
            // from: new Address('JPCONSTRUCRED'),
            from: new Address('no-reply@jpconstrucred.com', 'NOTIFICACIONES JP CONSTRUCRED C.LTDA'),
            subject: 'NOTIFCACION Venta Suspendida',
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
            view: 'email.ventasClaro.venta_suspendida',
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
