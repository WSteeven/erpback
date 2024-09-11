<?php

namespace App\Mail\Vehiculos;

use App\Models\ConfiguracionGeneral;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EnviarReporteBitacorasDiariasMail extends Mailable
{
    use Queueable, SerializesModels;

    public ConfiguracionGeneral $configuracion;
    public array $registros_realizados;
    public Collection $vehiculos_sin_bitacora;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($registros_realizados, $vehiculos_sin_bitacora)
    {
        $this->registros_realizados = $registros_realizados;
        $this->vehiculos_sin_bitacora = $vehiculos_sin_bitacora;
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
            from: new Address(env('MAIL_USERNAME'), 'MODULO VEHICULOS'),
            subject: 'Reporte Bitacoras Diarias ' . Carbon::yesterday()->format('Y-m-d'),
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
            view: 'email.vehiculos.reporte_diario_bitacoras', with: [
                'fecha' => Carbon::yesterday()->format('Y-m-d'),
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
