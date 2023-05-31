<?php

namespace App\Http\Controllers;

use App\Models\MotivoCanceladoTicket;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class MotivoCanceladoTicketController extends Controller
{
    private $entidad = 'Motivo de cancelación';

    public function index()
    {
        if (request('campos')) {
            $campos = explode(',', request('campos'));
            $results = MotivoCanceladoTicket::ignoreRequest(['campos'])->filter()->get($campos);
        } else {
            $results = MotivoCanceladoTicket::ignoreRequest()->filter()->get();
        }
        return response()->json(compact('results'));
    }

    public function store(Request $request)
    {
        $request->validate(['motivo' => 'required', 'activo' => 'nullable|boolean']);
        $modelo = MotivoCanceladoTicket::create($request->all());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
    }

    public function update(Request $request, MotivoCanceladoTicket $motivo_cancelado_ticket)
    {
        $request->validate(['motivo' => 'required', 'activo' => 'required|boolean']);
        $motivo_cancelado_ticket->update($request->all());
        $modelo = $motivo_cancelado_ticket->refresh();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('mensaje', 'modelo'));
    }

    public function show(MotivoCanceladoTicket $motivo_cancelado_ticket)
    {
        $modelo = $motivo_cancelado_ticket;
        return response()->json(compact('modelo'));
    }
}
