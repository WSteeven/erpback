<?php

namespace App\Mail\RecursosHumanos\SeleccionContratacion;

use App\Http\Resources\RecursosHumanos\SeleccionContratacion\PostulacionResource;
use App\Models\Canton;
use App\Models\ConfiguracionGeneral;
use App\Models\Departamento;
use App\Models\Empleado;
use App\Models\RecursosHumanos\SeleccionContratacion\Examen;
use App\Models\RecursosHumanos\SeleccionContratacion\Postulacion;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Src\App\EmpleadoService;
use Throwable;

class NotificarAgendamientoExamenesMail extends Mailable
{
    use Queueable, SerializesModels;

    public Examen $examen;
    public array $postulacion;
    public ConfiguracionGeneral $configuracion;
    public ?string $canton;
    public Empleado $medico;
    public Departamento $departamento_rrhh;
    public Departamento $departamento_medico;

    /**
     * Create a new message instance.
     *
     * @return void
     * @throws Throwable
     */
    public function __construct(Postulacion $postulacion, Examen $examen)
    {
        $this->examen = $examen;
        $resource = new PostulacionResource($postulacion);
        $this->postulacion = $resource->resolve();
        $this->configuracion = ConfiguracionGeneral::first();
        $this->canton = Canton::find($this->examen->canton_id)?->canton;
        $this->medico = EmpleadoService::obtenerEmpleadoRolEspecifico(User::ROL_MEDICO);
        $this->departamento_rrhh = Departamento::where('nombre', Departamento::DEPARTAMENTO_RRHH)->first();
        $this->departamento_medico = Departamento::where('nombre', Departamento::DEPARTAMENTO_MEDICO)->first();
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
            subject: 'Agendamiento para Realización de Exámenes Médicos',
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
            view: 'email.recursosHumanos.SeleccionContratacion.agendamiento_examenes',
            with: [
                'url' => env('SPA_URL', $this->configuracion->sitio_web_erp ?? 'https://firstred.jpconstrucred.com'),
                'link' => env('SPA_URL', $this->configuracion->sitio_web_erp??'https://firstred.jpconstrucred.com') . '/puestos-aplicados',
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
