<?php

namespace App\Mail\RecursosHumanos\SeleccionContratacion;

use App\Http\Resources\RecursosHumanos\SeleccionContratacion\PostulacionResource;
use App\Models\ConfiguracionGeneral;
use App\Models\Departamento;
use App\Models\RecursosHumanos\SeleccionContratacion\Postulacion;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EnviarLinkTestPersonalidadMail extends Mailable
{
    use Queueable, SerializesModels;

    public array $postulacion;
    public ConfiguracionGeneral $configuracion;
    public Departamento $departamento_rrhh;
    public string $linkEvaluacion;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Postulacion $postulacion, string $link)
    {
        $resource = new PostulacionResource($postulacion);
        $this->postulacion = $resource->resolve();
        $this->configuracion = ConfiguracionGeneral::first();
        $this->departamento_rrhh = Departamento::where('nombre', Departamento::DEPARTAMENTO_RRHH)->first();
        $this->linkEvaluacion = $link;
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
            subject: 'Test Personalidad para continuar con el proceso de selección',
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
            view: 'email.recursosHumanos.SeleccionContratacion.link_test_personalidad',
            with: [
                'url' => env('SPA_URL', 'https://firstred.jpconstrucred.com'),
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
