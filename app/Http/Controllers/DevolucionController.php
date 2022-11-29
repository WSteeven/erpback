<?php

namespace App\Http\Controllers;

use App\Http\Requests\DevolucionRequest;
use App\Http\Resources\DevolucionResource;
use App\Models\Devolucion;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class DevolucionController extends Controller
{
    private $entidad = 'Devolución';
    public function __construct()
    {
        $this->middleware('can:puede.ver.devoluciones')->only('index', 'show');
        $this->middleware('can:puede.crear.devoluciones')->only('store');
        $this->middleware('can:puede.editar.devoluciones')->only('update');
        $this->middleware('can:puede.eliminar.devoluciones')->only('destroy');
    }

    /**
     * Listar
     */
    public function index(Request $request)
    {
        $page = $request['page'];
        $campos = explode(',', $request['page']);
        $results = [];

        if ($request['campos']) {
            $results = Devolucion::all($campos);
            $results = DevolucionResource::collection($results);
            return response()->json(compact('results'));
        } else 
        if ($page) {
            $results = Devolucion::simplePaginate($request['offset']);
        } else {
            $results = Devolucion::ignoreRequest(['campos'])->filter()->get();
        }

        DevolucionResource::collection($results);

        return response()->json(compact('results'));
    }

    /**
     * Guardar
     */
    public function store(DevolucionRequest $request)
    {
        // Adaptacion de foreign keys
        $datos = $request->validated();
        $datos['solicitante_id'] = $request->safe()->only(['solicitante'])['solicitante'];
        $datos['tarea_id'] = $request->safe()->only(['tarea'])['tarea'];
        $datos['sucursal_id'] = $request->safe()->only(['sucursal'])['sucursal'];

        // Respuesta
        $modelo = Devolucion::create($datos);
        $modelo = new DevolucionResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Consultar
     */
    public function show(Devolucion $devolucion)
    {
        $modelo = new DevolucionResource($devolucion);
        return response()->json(compact('modelo'));
    }

    /**
     * Actualizar
     */
    public function update(DevolucionRequest $request, Devolucion $devolucion)
    {
        // Adaptacion de foreign keys
        $datos = $request->validated();
        $datos['solicitante_id'] = $request->safe()->only(['solicitante'])['solicitante'];
        $datos['tarea_id'] = $request->safe()->only(['tarea'])['tarea'];
        $datos['sucursal_id'] = $request->safe()->only(['sucursal'])['sucursal'];

        // Respuesta
        $devolucion->update($datos);
        $modelo = new DevolucionResource($devolucion->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Eliminar
     */
    public function destroy(Devolucion $devolucion)
    {
        $devolucion->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
