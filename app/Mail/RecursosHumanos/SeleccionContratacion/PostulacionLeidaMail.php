<?php

namespace App\Mail\RecursosHumanos\SeleccionContratacion;

use App\Http\Resources\RecursosHumanos\SeleccionContratacion\PostulacionResource;
use App\Models\ConfiguracionGeneral;
use App\Models\RecursosHumanos\SeleccionContratacion\Postulacion;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PostulacionLeidaMail extends Mailable
{
    use Queueable, SerializesModels;
    public array $postulacion;
    public ConfiguracionGeneral $configuracion;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Postulacion $postulacion)
    {
        $resource =  new PostulacionResource($postulacion);
        $this->postulacion = $resource->resolve();
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
            from: new Address(env('MAIL_USERNAME'), 'Proceso de Postulación'),
            subject: 'Actualización de tu Postulación',
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
            view: 'email.recursosHumanos.SeleccionContratacion.actualizacion_postulacion',
            with: [
                'url' => env('SPA_URL', 'https://sistema.jpconstrucred.com'),
                'link' => env('SPA_URL', 'https://sistema.jpconstrucred.com') . '/puestos-aplicados'
            ]
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
