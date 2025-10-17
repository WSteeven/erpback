<?php

namespace App\Mail\RecursosHumanos;

use App\Models\ConfiguracionGeneral;
use App\Models\Empleado;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Attachment;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmpleadoDesvinculadoMail extends Mailable
{
    use Queueable, SerializesModels;

    public Empleado $empleado;
    public mixed $resumen;
    public ConfiguracionGeneral $configuracion;
    private $pdf;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($empleado, $resumen, $configuracion, $pdf)
    {
        $this->empleado = $empleado;
        $this->resumen = $resumen;
        $this->pdf = $pdf;
        $this->configuracion = $configuracion;
    }

    /**
     * Get the message envelope.
     *
     * @return Envelope
     */
    public function envelope()
    {
        return new Envelope(
            from: new Address(env('MAIL_FROM_ADDRESS'), $this->configuracion->razon_social),
            subject: 'Desvinculación de Empleado ' . Empleado::extraerNombresApellidos($this->empleado),
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
            view: 'email.recursosHumanos.empleado_desvinculado',
            with: [
                'url' => env('SPA_URL', $this->configuracion->sitio_web_erp ?? 'https://firstred.jpconstrucred.com'),
                'link' => env('SPA_URL', $this->configuracion->sitio_web_erp ?? 'https://firstred.jpconstrucred.com') . '/puestos-aplicados',
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
        $filename = 'Desvinculación ' . Empleado::extraerNombresApellidos($this->empleado) . '.pdf';

        return [
            Attachment::fromData(fn() => $this->pdf, $filename)->withMime('application/pdf')
        ];
    }
}
