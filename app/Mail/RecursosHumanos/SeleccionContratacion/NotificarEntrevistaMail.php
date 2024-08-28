<?php

namespace App\Mail\RecursosHumanos\SeleccionContratacion;

use App\Http\Resources\RecursosHumanos\SeleccionContratacion\PostulacionResource;
use App\Models\ConfiguracionGeneral;
use App\Models\RecursosHumanos\SeleccionContratacion\Entrevista;
use App\Models\RecursosHumanos\SeleccionContratacion\Postulacion;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotificarEntrevistaMail extends Mailable
{
    use Queueable, SerializesModels;

    public array $postulacion;
    public string $fecha_hora;
    public int $duracion;
    public ConfiguracionGeneral $configuracion;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Postulacion $postulacion, Entrevista $entrevista)
    {
        $resource = new PostulacionResource($postulacion);
        $this->postulacion = $resource->resolve();
        $this->configuracion = ConfiguracionGeneral::first();
        $this->fecha_hora = $entrevista->fecha_hora;
        $this->duracion = $entrevista->duracion;
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
            subject: 'Agendamiento de Entrevista',
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
            view: 'email.recursosHumanos.SeleccionContratacion.entrevista_postulacion',
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
