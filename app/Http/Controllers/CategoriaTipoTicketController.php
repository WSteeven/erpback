<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoriaTipoTicketRequest;
use App\Http\Resources\CategoriaTipoTicketResource;
use App\Models\CategoriaTipoTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
    public function update(CategoriaTipoTicketRequest $request, CategoriaTipoTicket  $categoria_tipo_ticket)
    {
        if ($request->isMethod('patch')) {
            $keys = $request->keys();
            unset($keys['id']);
            $categoria_tipo_ticket->update($request->only($request->keys()));
        }

        // Respuesta
        $categoria_tipo_ticket->update($request->validated());
        $modelo = new CategoriaTipoTicketResource($categoria_tipo_ticket->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }
}
