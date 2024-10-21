<?php

namespace App\Mail\ComprasProveedores;

use App\Models\ComprasProveedores\OrdenCompra;
use App\Models\ConfiguracionGeneral;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Src\App\ComprasProveedores\OrdenCompraService;

class EnviarMailOrdenCompraProveedor extends Mailable
{
    use Queueable, SerializesModels;
    public OrdenCompra $orden;
    private OrdenCompraService $servicio;
    public ConfiguracionGeneral $configuracion;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(OrdenCompra $orden_compra)
    {
        $this->orden = $orden_compra;
        $this->servicio = new OrdenCompraService();
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
            from: new Address($this->orden->solicitante->user->email, $this->orden->solicitante->nombres . ' ' . $this->orden->solicitante->apellidos),
            subject: 'Orden de Compra ' . $this->configuracion->razon_social,
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
            view: 'email.comprasProveedores.orden_compra',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        if ($this->orden->file && Storage::exists($this->orden->file)) {
            // //Si existe el pdf se envía nomás al proveedor
            // return [
            //     Attachment::fromStorage($this->orden->file)
            //         ->as('orden_compraN' . $this->orden->id)
            //         ->withMime('application/pdf'),
            // ];

            //en caso que no exista se genera uno nuevo y se adjunta
            $ruta = $this->servicio->generarPdf($this->orden, true, false);

            return Attachment::fromStorage($ruta)->as('orden_compra N' . $this->orden->id)->withMime('application/pdf');
        } else {
            //en caso que no exista se genera uno nuevo y se adjunta
            $ruta = $this->servicio->generarPdf($this->orden, true, false);

            return Attachment::fromStorage($ruta)->as('orden_compra N' . $this->orden->id)->withMime('application/pdf');
        }
    }
}
