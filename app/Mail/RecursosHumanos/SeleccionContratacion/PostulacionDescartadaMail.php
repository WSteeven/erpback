<?php

namespace App\Mail\RecursosHumanos\SeleccionContratacion;

use App\Http\Resources\RecursosHumanos\SeleccionContratacion\PostulacionResource;
use App\Models\ConfiguracionGeneral;
use App\Models\RecursosHumanos\SeleccionContratacion\Postulacion;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PostulacionDescartadaMail extends Mailable
{
    use Queueable, SerializesModels;

    public array $postulacion;
    public ConfiguracionGeneral $configuracion;
    private bool $antes_entrevista;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Postulacion $postulacion, bool $antes_entrevista)
    {
        $this->antes_entrevista = $antes_entrevista;
        $resource = new PostulacionResource($postulacion);
        $this->postulacion = $resource->resolve();
        $this->configuracion = ConfiguracionGeneral::first();
    }

    /**
     * Get the message envelope.
     *
     * @return Envelope
     */
    public function envelope()
    {
        return new Envelope(
            from: new Address(env('MAIL_USERNAME'), 'Proceso de Postulación'),
            subject: 'Fin del proceso de Postulación',
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
            view: $this->antes_entrevista?'email.recursosHumanos.SeleccionContratacion.postulacion_descartada':'email.recursosHumanos.SeleccionContratacion.postulacion_descartada_despues_entrevista',
            with: [
                'url' => env('SPA_URL', 'https://firstred.jpconstrucred.com'),
                'link' => env('SPA_URL', 'https://firstred.jpconstrucred.com') . '/puestos-aplicados'
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
