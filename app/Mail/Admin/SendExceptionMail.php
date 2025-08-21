<?php

namespace App\Mail\Admin;

use App\Models\ConfiguracionGeneral;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendExceptionMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $mensaje;
    public ConfiguracionGeneral $configuracion;
    public string $remitente;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $mensaje = "", string $remitente = 'Sistema')
    {
        $this->mensaje = $mensaje;
        $this->remitente = $remitente;
        $this->configuracion = ConfiguracionGeneral::first();
    }

    /**
     * Get the message envelope.
     *
     * @return Envelope
     */
    public function envelope()
    {
        return new Envelope(from: new Address(env('MAIL_USERNAME'), $this->remitente),
            subject: 'Error de Exception',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return Content
     */
    public function content()
    {
        return new Content(
            view: 'admin.email.exception',
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
