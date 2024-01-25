<?php

namespace App\Mail\Tickets;

use App\Models\ConfiguracionGeneral;
use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class EnviarMailTicket extends Mailable
{
    use Queueable, SerializesModels;

    public Ticket $ticket;
    public ConfiguracionGeneral $configuracion;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
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
            from: new Address($this->ticket->solicitante->user->email, $this->ticket->solicitante->nombres . ' ' . $this->ticket->solicitante->apellidos),
            subject: 'Notificación: Ticket ' . $this->ticket->codigo . ' - ' . $this->ticket->estado,
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
            view: 'email.tickets.ticket',
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
