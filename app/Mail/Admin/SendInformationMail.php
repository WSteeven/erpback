<?php

namespace App\Mail\Admin;

use App\Models\ConfiguracionGeneral;
use App\Models\Empleado;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendInformationMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $mensaje;
    private string $tipo;
    public array $datos;
    public ConfiguracionGeneral $configuracion;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $mensaje, string $tipo, array $datos=[])
    {
        $this->mensaje = $mensaje;
        $this->tipo = $tipo;
        $this->datos = $datos;
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
            from: new Address(env('MAIL_USERNAME'), Empleado::extraerNombresApellidos(auth()->user()->empleado)),
            subject: match ($this->tipo){
                'NUEVO_EMPLEADO_CREADO'=>'Nuevo empleado registrado en el sistema',
                default => 'Información'
            },
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
            view: 'admin.email.information',
            with: [
                'mensaje' => $this->mensaje,
                'datos' => $this->datos,
                'configuracion' => $this->configuracion,
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
