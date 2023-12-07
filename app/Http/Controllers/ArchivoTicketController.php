<?php

namespace App\Http\Controllers;

use App\Models\ArchivoTicket;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Src\Config\RutasStorage;
use Src\Shared\EliminarArchivo;
use Src\Shared\GuardarArchivo;

class ArchivoTicketController extends Controller
{
    public function index()
    {
        $results = ArchivoTicket::filter()->get();
        return response()->json(compact('results'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'tickets_id' => 'nullable|string',
        ]);

        $tickets_id = explode(',', $request['tickets_id']);
        foreach ($tickets_id as $id) {
            $ticket = Ticket::find($id);

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

            $guardarArchivo = new GuardarArchivo($ticket, $request, RutasStorage::TICKETS);
            $guardarArchivo->execute();
        }

        return response()->json(['mensaje' => 'Subido exitosamente!']);
    }

    public function storeOld(Request $request)
    {
        $request->validate([
            'ticket_id' => 'required|numeric|integer',
            'tickets_id' => 'nullable|array',
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

        $guardarArchivo = new GuardarArchivo($ticket, $request, RutasStorage::TICKETS);
        $modelo = $guardarArchivo->execute();

        return response()->json(['modelo' => $modelo, 'mensaje' => 'Subido exitosamente!']);
    }


    public function update(Request $request, ArchivoTicket $archivo_ticket)
    {
        $archivo_ticket->update($request->all());
        return response()->json(['modelo' => $archivo_ticket->refresh(), 'mensaje' => 'Información actualizada exitosamente!']);
    }


    public function destroy(ArchivoTicket $archivo_ticket)
    {
        if ($archivo_ticket) {
            $eliminar = new EliminarArchivo($archivo_ticket);
            $eliminar->execute();
        }
        return response()->json(['mensaje' => 'Archivo eliminado exitosamente!']);
    }
}
