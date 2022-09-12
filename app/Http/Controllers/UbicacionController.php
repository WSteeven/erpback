<?php

namespace App\Http\Controllers;

use App\Http\Requests\UbicacionRequest;
use App\Http\Resources\UbicacionResource;
use App\Models\Percha;
use App\Models\Piso;
use App\Models\Ubicacion;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class UbicacionController extends Controller
{
    private $entidad = 'Ubicacion';

    /**
     * Listar
     */
    public function index()
    {
        $results = UbicacionResource::collection(Ubicacion::all());
        return response()->json(compact('results'));
    }

    /**
     * Guardar
     */
    public function store(UbicacionRequest $request)
    {
        if ($request['piso_id'] && $request['percha_id']) {
            $request['codigo'] = Ubicacion::obtenerCodigoUbicacion($request->percha_id, $request->piso_id);
        }else{
            $request->validate(['codigo' => 'required|string|unique:ubicaciones,codigo']);
        }

        $ubicacion = Ubicacion::create($request->validated());
        $modelo = new UbicacionResource($ubicacion);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));
    }


    /**
     * Consultar
     */
    public function show(Ubicacion $ubicacion)
    {
        $modelo = new UbicacionResource($ubicacion);
        return response()->json(compact('modelo'));
    }


    /**
     * Actualizar
     */
    public function update(UbicacionRequest $request, Ubicacion  $ubicacion)
    {
        //Respuesta
        $ubicacion->update($request->validated());
        $modelo = new UbicacionResource($ubicacion->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }


    public function destroy(Ubicacion $ubicacion)
    {
        $ubicacion->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
