<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ventas\ClienteClaroRequest;
use App\Http\Resources\Ventas\ClienteClaroResource;
use App\Models\User;
use App\Models\Ventas\ClienteClaro;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class ClienteClaroController extends Controller
{
    private $entidad = 'Cliente Claro';
    public function __construct()
    {
        $this->middleware('can:puede.ver.clientes_claro')->only('index', 'show');
        $this->middleware('can:puede.crear.clientes_claro')->only('store');
        $this->middleware('can:puede.editar.clientes_claro')->only('update');
        $this->middleware('can:puede.eliminar.clientes_claro')->only('destroy');
    }
    public function index(Request $request)
    {
        $results = [];
        if (auth()->user()->hasRole([User::SUPERVISOR_VENTAS])) {
            $results = ClienteClaro::where('supervisor_id', auth()->user()->empleado->id)->ignoreRequest(['campos'])->filter()->get();
        } else {
            $results = ClienteClaro::ignoreRequest(['campos'])->filter()->get();
        }
        $results = ClienteClaroResource::collection($results);
        return response()->json(compact('results'));
    }
    public function store(ClienteClaroRequest $request)
    {
        try {
            // Adaptacion de foreign keys
            $datos = $request->validated();
            $datos['supervisor_id'] = $request->safe()->only(['supervisor'])['supervisor'];
            $datos['canton_id'] = $request->safe()->only(['canton'])['canton'];
            $datos['parroquia_id'] = $request->safe()->only(['parroquia'])['parroquia'];

            DB::beginTransaction();
            $cliente = ClienteClaro::create($datos);
            $modelo = new ClienteClaroResource($cliente);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage() . '. ' . $e->getLine()],
            ]);
        }
        return response()->json(compact('mensaje', 'modelo'));
    }

    public function show(Request $request, ClienteClaro $cliente)
    {
        $modelo = new ClienteClaroResource($cliente);
        return response()->json(compact('modelo'));
    }

    public function update(ClienteClaroRequest $request, ClienteClaro $cliente)
    {
        try {
            // Adaptacion de foreign keys
            $datos = $request->validated();
            $datos['supervisor_id'] = $request->safe()->only(['supervisor'])['supervisor'];

            DB::beginTransaction();
            $cliente->update($datos);
            $modelo = new ClienteClaroResource($cliente->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            throw ValidationException::withMessages([
                'Error al actualizar registro' => [$e->getMessage() . '. ' . $e->getLine()],
            ]);
        }
        return response()->json(compact('mensaje', 'modelo'));
    }
    public function destroy(Request $request, ClienteClaro $cliente)
    {
        $cliente->delete();
        return response()->json(compact('ClienteClaro'));
    }

    /**
     * desactivar
     */
    public function desactivar(ClienteClaro $cliente)
    {
        $cliente->activo = !$cliente->activo;
        $cliente->save();
        $modelo  = new ClienteClaroResource($cliente->refresh());
        return response()->json(compact('modelo'));
    }
}
