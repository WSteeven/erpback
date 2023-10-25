<?php

namespace App\Mail;

use App\Models\Empleado;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class RolPagoEmail extends Mailable
{
    use Queueable, SerializesModels;
    private $reportes;
    private $pdf;
    private Empleado $empleado;
    private $ruta_archivo;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($reportes, $pdf, $empleado, $ruta_archivo)
    {
        $this->reportes = $reportes;
        $this->pdf = $pdf;
        $this->empleado = $empleado;
        $this->ruta_archivo = $ruta_archivo;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            from: new Address('no-reply@jpconstrucred.com', 'JP CONSTRUCRED C.LTDA'),
            subject: 'Rol de Pagos de ' . $this->reportes['roles_pago'][0]['mes'],
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
            view: 'email.recursosHumanos.rol_pago',
            with: $this->reportes
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        $filename = 'rol_pago' . time() . '.pdf';
        if ($this->ruta_archivo != null) {
            $path = str_replace("storage/", "public/",  $this->ruta_archivo);
            return [
                Attachment::fromStorage($path)
                    ->as('rol_pago_' .$this->reportes['roles_pago'][0]['mes'])
                    ->withMime('application/pdf'),
            ];
        } else {
            return [
                Attachment::fromData(fn () => $this->pdf, $filename)->withMime('application/pdf')
            ];
        }
    }
}
