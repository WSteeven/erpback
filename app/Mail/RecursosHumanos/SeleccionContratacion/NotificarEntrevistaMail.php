<?php

namespace App\Mail\RecursosHumanos\SeleccionContratacion;

use App\Http\Resources\RecursosHumanos\SeleccionContratacion\PostulacionResource;
use App\Models\Canton;
use App\Models\ConfiguracionGeneral;
use App\Models\Departamento;
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

    public Entrevista $entrevista;
    public array $postulacion;
    public ?string $canton;
    public ConfiguracionGeneral $configuracion;
    public Departamento $departamento_rrhh;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Postulacion $postulacion, Entrevista $entrevista)
    {
        $this->entrevista = $entrevista;
        $resource = new PostulacionResource($postulacion);
        $this->postulacion = $resource->resolve();
        $this->configuracion = ConfiguracionGeneral::first();
        $this->canton = Canton::find($this->entrevista->canton_id)?->canton;
        $this->departamento_rrhh = Departamento::where('nombre', Departamento::DEPARTAMENTO_RRHH)->first();

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
                'link' => env('SPA_URL', 'https://sistema.jpconstrucred.com') . '/puestos-aplicados',
//                'canton'=>
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
