<?php

namespace App\Http\Controllers;

use App\Http\Requests\MarcaRequest;
use App\Http\Resources\CategoriaResource;
use App\Http\Resources\MarcaResource;
use App\Models\Marca;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class MarcaController extends Controller
{
    private $entidad = 'Marca';
    /**
     * Listar
     */
    public function index()
    {
        $results = MarcaResource::collection(Marca::all());
        return response()->json(compact('results'));
    }


    /**
     * Guardar
     */
    public function store(MarcaRequest $request)
    {
        //Respuesta
        $modelo = Marca::create($request->validated());
        $modelo = new MarcaResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));
    }


    /**
     * Consultar
     */
    public function show(Marca $marca)
    {
        $modelo = new MarcaResource($marca);
        return response()->json(compact('modelo'));
    }


    /**
     * Actualizar
     */
    public function update(MarcaRequest $request, Marca  $marca)
    {
        
        //Respuesta
        $marca->update($request->validated());
        $modelo = new MarcaResource($marca->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Eliminar
     */
    public function destroy(Marca $marca)
    {
        $marca->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
