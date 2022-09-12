<?php

namespace App\Http\Controllers;

use App\Http\Requests\SucursalRequest;
use App\Http\Resources\SucursalResource;
use App\Models\Sucursal;
use Illuminate\Http\Request;
use Psy\Sudo;
use Src\Shared\Utils;

class SucursalController extends Controller
{
    private $entidad = 'Sucursal';

    /**
     * Listar
     */
    public function index()
    {
        $results = SucursalResource::collection(Sucursal::all());
        return response()->json(compact('results'));
    }


    /**
     * Guardar
     */
    public function store(SucursalRequest $request)
    {
        $sucursal = Sucursal::create($request->validated());
        $modelo = new SucursalResource($sucursal);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Consultar
     */
    public function show(Sucursal $sucursal)
    {
        $modelo = new SucursalResource($sucursal);
        return response()->json(compact('modelo'));
    }

    /**
     * Actualizar
     */
    public function update(SucursalRequest $request, Sucursal  $sucursal)
    {
        $sucursal->update($request->validated());
        $modelo = new SucursalResource($sucursal->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Eliminar
     */
    public function destroy(Sucursal $sucursal)
    {
        $sucursal->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
