<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ventas\ClienteClaroRequest;
use App\Http\Resources\Ventas\ClienteClaroResource;
use App\Models\Ventas\ClienteClaro;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Src\Shared\Utils;

class ClienteClaroController extends Controller
{
    private $entidad = 'Cliente Claro';
    public function __construct()
    {
        $this->middleware('can:puede.ver.cliente_claro')->only('index', 'show');
        $this->middleware('can:puede.crear.cliente_claro')->only('store');
    }
    public function index(Request $request)
    {
        $results = [];
        $results = ClienteClaro::ignoreRequest(['campos'])->filter()->get();
         $results = ClienteClaroResource::collection($results);
        return response()->json(compact('results'));
    }
    public function show(Request $request, ClienteClaro $cliente_claro)
    {
        $modelo = new ClienteClaroResource($cliente_claro);
        return response()->json(compact('modelo'));
    }
    public function store(ClienteClaroRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $cliente_claro = ClienteClaro::create($datos);
            $modelo = new ClienteClaroResource($cliente_claro);
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    public function update(ClienteClaroRequest $request, ClienteClaro $cliente_claro)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $cliente_claro->update($datos);
            $modelo = new ClienteClaroResource($cliente_claro->refresh());
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    public function destroy(Request $request, ClienteClaro $cliente_claro)
    {
        $cliente_claro->delete();
        return response()->json(compact('ClienteClaro'));
    }
}
