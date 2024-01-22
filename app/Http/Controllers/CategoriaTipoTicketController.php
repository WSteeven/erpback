<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoriaTipoTicketRequest;
use App\Http\Resources\CategoriaTipoTicketResource;
use App\Models\CategoriaTipoTicket;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class CategoriaTipoTicketController extends Controller
{
    private $entidad = 'Categoría';

    public function index()
    {
        $results = CategoriaTipoTicket::latest()->get();
        $results = CategoriaTipoTicketResource::collection($results);
        return response()->json(compact('results'));
    }

     /**********
     * Guardar
     **********/
    public function store(CategoriaTipoTicketRequest $request)
    {
        //Respuesta
        $datos = $request->validated();

        $datos['departamento_id'] = $datos['departamento'];

        $modelo = CategoriaTipoTicket::create($datos);
        $modelo = new CategoriaTipoTicketResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /*************
     * Actualizar
     *************/
    public function update(CategoriaTipoTicketRequest $request, CategoriaTipoTicket  $categoria_ticket)
    {
        if ($request->isMethod('patch')) {
            $categoria_ticket->update($request->except(['id', ...$request->keys()]));
        }

        // Respuesta
        $categoria_ticket->update($request->validated());
        $modelo = new CategoriaTipoTicketResource($categoria_ticket->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }
}
