<?php

namespace App\Http\Controllers;

use App\Models\ArchivoSeguimientoTicket;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Src\Config\RutasStorage;
use Src\Shared\EliminarArchivo;
use Src\Shared\GuardarArchivo;
use Src\Shared\Utils;

class ArchivoSeguimientoTicketController extends Controller
{
    public function index()
    {
        $results = ArchivoSeguimientoTicket::filter()->get();
        return response()->json(compact('results'));
    }


    /********************************
     * Se guarda un archivo a la vez
     ********************************/
    public function store(Request $request)
    {
        $request->validate([
            'ticket_id' => 'required|numeric|integer',
        ]);

        $ticket = Ticket::find($request['ticket_id']);

        if (!$ticket) {
            throw ValidationException::withMessages([
                'ticket' => ['El ticket no existe'],
            ]);
        }

        if (!$request->hasFile('file')) {
            throw ValidationException::withMessages([
                'file' => ['Debe seleccionar al menos un archivo.'],
            ]);
        }

        $modelo = $this->guardarArchivoSeguimiento($ticket, $request, RutasStorage::ARCHIVOS_SEGUIMIENTO_TICKETS);

        return response()->json(['modelo' => $modelo, 'mensaje' => 'Subido exitosamente!']);

        // return response()->json(['mensaje' => 'No se pudo subir!']);
    }

    /********************************
     * Se edita un archivo a la vez
     ********************************/
    public function update(Request $request, ArchivoSeguimientoTicket $archivo_seguimiento_ticket)
    {
        $request->validate([
            'ticket_id' => 'required|numeric|integer',
        ]);

        // $archivo_seguimiento->update($request->all());

        $ticket = Ticket::find($request['ticket_id']);

        if (!$ticket) {
            throw ValidationException::withMessages([
                'ticket' => ['El ticket no existe'],
            ]);
        }

        if (!$request->hasFile('file')) {
            throw ValidationException::withMessages([
                'file' => ['Debe seleccionar al menos un archivo.'],
            ]);
        }

        $this->guardarArchivoSeguimiento($ticket, $request, RutasStorage::ARCHIVOS_SEGUIMIENTO_TICKETS);

        return response()->json(['modelo' => $archivo_seguimiento_ticket->refresh(), 'mensaje' => 'Información actualizada exitosamente!']);
    }


    public function destroy(ArchivoSeguimientoTicket $archivo_seguimiento_ticket)
    {
        if ($archivo_seguimiento_ticket) {
            $eliminar = new EliminarArchivo($archivo_seguimiento_ticket);
            $eliminar->execute();
        }
        return response()->json(['mensaje' => 'Archivo eliminado exitosamente!']);
    }

    public function guardarArchivoSeguimiento(Ticket $ticket, Request $request, RutasStorage $ruta)
    {
        $archivo = $request->file('file');

        $path = $archivo->store($ruta->value);
        $ruta_relativa = Utils::obtenerRutaRelativaArchivo($path);
        return $ticket->archivosSeguimientos()->create([
            'nombre' => $archivo->getClientOriginalName(),
            'ruta' => $ruta_relativa,
            'tamanio_bytes' => filesize($archivo),
        ]);
    }
}
