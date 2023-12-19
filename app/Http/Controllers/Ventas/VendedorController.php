<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ventas\VendedorRequest;
use App\Http\Resources\Ventas\VendedorResource;
use App\Models\Ventas\Vendedor;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Src\Shared\Utils;

class VendedorController extends Controller
{
    private $entidad = 'Vendedor';
    public function __construct()
    {
        $this->middleware('can:puede.ver.vendedor')->only('index', 'show');
        $this->middleware('can:puede.crear.vendedor')->only('store');
    }
    public function index(Request $request)
    {
        $results = [];
        $results = Vendedor::ignoreRequest(['campos'])->filter()->with('jefe_inmediato_info')->get();
         $results = VendedorResource::collection($results);
        return response()->json(compact('results'));
    }
    public function show(Request $request, Vendedor $vendedor)
    {
        $modelo = new VendedorResource($vendedor);
        return response()->json(compact('modelo'));
    }
    public function store(VendedorRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $vendedor = Vendedor::create($datos);
            $modelo = new VendedorResource($vendedor);
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    public function update(VendedorRequest $request, Vendedor $vendedor)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $vendedor->update($datos);
            $modelo = new VendedorResource($vendedor->refresh());
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    public function destroy(Request $request, Vendedor $vendedor)
    {
        $vendedor->delete();
        return response()->json(compact('vendedor'));
    }
}
