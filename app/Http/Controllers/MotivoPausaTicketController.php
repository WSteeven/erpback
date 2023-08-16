<?php

namespace App\Http\Controllers;

use App\Models\MotivoPausaTicket;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class MotivoPausaTicketController extends Controller
{
    private $entidad = 'Motivo de pausa';

    public function index()
    {
        $results = MotivoPausaTicket::filter()->get();
        return response()->json(compact('results'));
    }

    public function store(Request $request)
    {
        $request->validate(['motivo' => 'required', 'activo' => 'nullable|boolean']);
        $modelo = MotivoPausaTicket::create($request->all());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
    }

    public function update(Request $request, MotivoPausaTicket $motivo_pausa_ticket)
    {
        $request->validate(['motivo' => 'required', 'activo' => 'required|boolean']);
        $motivo_pausa_ticket->update($request->all());
        $modelo = $motivo_pausa_ticket->refresh();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('mensaje', 'modelo'));
    }

    public function show(MotivoPausaTicket $motivo_pausa_ticket)
    {
        $modelo = $motivo_pausa_ticket;
        return response()->json(compact('modelo'));
    }
}
