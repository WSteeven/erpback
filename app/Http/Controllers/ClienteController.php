<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClienteRequest;
use App\Http\Resources\ClienteResource;
use App\Models\Cliente;
use App\Models\Empresa;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Src\Shared\Utils;

class ClienteController extends Controller
{
    private $entidad = 'Cliente';
    public function __construct()
    {
        $this->middleware('can:puede.ver.clientes')->only('index', 'show');
        $this->middleware('can:puede.crear.clientes')->only('store');
        $this->middleware('can:puede.editar.clientes')->only('update');
        $this->middleware('can:puede.eliminar.clientes')->only('destroy');
    }
    /**
     * Listar
     */
    public function index(Request $request)
    {
        $search = $request['search'];
        $campos = explode(',', $request['campos']);
        $results = [];
        if ($request['campos']) {
            $results = Cliente::ignoreRequest(['campos'])->filter()->get($campos);
            // return response()->json(compact('results'));
        } else if ($search) {
            $empresa = Empresa::select('id')->where('razon_social', 'LIKE', '%' . $search . '%')->first();
            // Log::channel('testing')->info('Log', ['empresa', $empresa->id]);

            if ($empresa) $results = ClienteResource::collection(Cliente::where('empresa_id', $empresa->id)->get());
        } else {
            $results = Cliente::all();
            Log::channel('testing')->info('Log', ['entro en el else grande', $results]);
        }

        $results = ClienteResource::collection($results);
        return response()->json(compact('results'));
    }
    /**
     * Guardar
     */
    public function store(ClienteRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            //Adaptacion de claves foraneas
            $datos['empresa_id'] = $request->safe()->only(['empresa'])['empresa'];
            $datos['parroquia_id'] = $request->safe()->only(['parroquia'])['parroquia'];

            //Respuesta
            $modelo = Cliente::create($datos);
            $modelo = new ClienteResource($modelo);
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['ERROR en el insert de clientes', $e->getMessage(), $e->getLine()]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . $e->getLine()], 422);
        }
    }


    /**
     * Consultar
     */
    public function show(Cliente $cliente)
    {
        $modelo = new ClienteResource($cliente);
        return response()->json(compact('modelo'));
    }


    /**
     * Actualizar
     */
    public function update(ClienteRequest $request, Cliente  $cliente)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            //Adaptacion de claves foraneas
            $datos['empresa_id'] = $request->safe()->only(['empresa'])['empresa'];
            $datos['parroquia_id'] = $request->safe()->only(['parroquia'])['parroquia'];

            // Respuesta
            $cliente->update($datos);
            $modelo = new ClienteResource($cliente->refresh());
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['ERROR al actualizar el cliente', $e->getMessage(), $e->getLine()]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al actualizar el registro' . $e->getMessage() . $e->getLine()], 422);
        }
    }

    /**
     * Eliminar
     */
    public function destroy(Cliente $cliente)
    {
        $cliente->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
