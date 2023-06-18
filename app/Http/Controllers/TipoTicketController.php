<?php

namespace App\Http\Controllers;

use App\Http\Requests\TipoTicketRequest;
use App\Http\Resources\TipoTicketResource;
use App\Models\TipoTicket;
use Src\Shared\Utils;

class TipoTicketController extends Controller
{
    private $entidad = 'Tipo de ticket';

    public function listar()
    {
        $campos = explode(',', request('campos'));

        if (request('campos')) {
            return TipoTicket::ignoreRequest(['campos'])->filter()->latest()->get($campos);
        } else {
            return TipoTicketResource::collection(TipoTicket::filter()->latest()->get());
        }
    }

    /*********
     * Listar
     *********/
    public function index()
    {
        $results = $this->listar();
        return response()->json(compact('results'));
    }


    /**********
     * Guardar
     **********/
    public function store(TipoTicketRequest $request)
    {
        //Respuesta
        $datos = $request->validated();

        $datos['departamento_id'] = $datos['departamento'];

        $modelo = TipoTicket::create($datos);
        $modelo = new TipoTicketResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));
    }


    /************
     * Consultar
     ************/
    public function show(TipoTicket $tipo_ticket)
    {
        $modelo = new TipoTicketResource($tipo_ticket);
        return response()->json(compact('modelo'));
    }


    /*************
     * Actualizar
     *************/
    public function update(TipoTicketRequest $request, TipoTicket  $tipo_ticket)
    {
        // Respuesta
        $tipo_ticket->update($request->validated());
        $modelo = new TipoTicketResource($tipo_ticket->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }
}
