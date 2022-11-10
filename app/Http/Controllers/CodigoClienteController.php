<?php

namespace App\Http\Controllers;

use App\Http\Requests\CodigoClienteRequest;
use App\Http\Resources\CodigoClienteResource;
use App\Models\CodigoCliente;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class CodigoClienteController extends Controller
{
    private $entidad = 'Codigo de cliente';
    public function __construct()
    {
        $this->middleware('can:puede.ver.codigos_clientes')->only('index', 'show');
        $this->middleware('can:puede.crear.codigos_clientes')->only('store');
        $this->middleware('can:puede.editar.codigos_clientes')->only('update');
        $this->middleware('can:puede.eliminar.codigos_clientes')->only('update');
    }
    /**
     * Listar
     */
    public function index(Request $request)
    {
        $page = $request['page'];
        $results = [];

        if ($page) {
            $results = CodigoCliente::simplePaginate($request['offset']);
            CodigoClienteResource::collection($results);
            $results->appends(['offset' => $request['offset']]);
        } else {
            $results = CodigoCliente::filter()->get();
            CodigoClienteResource::collection($results);
        }
        return response()->json(compact('results'));
    }

    /**
     * Guardar
     *
     */
    public function store(CodigoClienteRequest $request)
    {
        //Adaptacion de foreign keys
        $datos = $request->validated();
        $datos['cliente_id'] = $request->safe()->only(['cliente'])['cliente'];
        $datos['producto_id'] = $request->safe()->only(['producto'])['producto'];

        //Respuesta
        $modelo = CodigoCliente::create($datos);
        $modelo = new CodigoClienteResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Consultar
     */
    public function show(CodigoCliente $codigo_cliente)
    {
        $modelo = new CodigoClienteResource($codigo_cliente);
        return response()->json(compact('modelo'));
    }

    /**
     * Actualizar
     */
    public function update(CodigoClienteRequest $request, CodigoCliente $codigo_cliente)
    {
        //Adaptacion de foreign keys
        $datos = $request->validated();
        $datos['cliente_id'] = $request->safe()->only(['cliente'])['cliente'];
        $datos['producto_id'] = $request->safe()->only(['producto'])['producto'];

        //Respuesta
        $codigo_cliente->update($datos);
        $modelo = new CodigoClienteResource($codigo_cliente->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('modelo', 'mensaje'));
    }

    /**
     * Eliminar
     */
    public function destroy(CodigoCliente $codigo_cliente)
    {
        $codigo_cliente->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
