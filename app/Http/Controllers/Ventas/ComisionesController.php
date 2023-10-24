<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ventas\ComisionesRequest;
use App\Http\Resources\Ventas\ComisionesResource;
use App\Models\Ventas\Comisiones;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Src\Shared\Utils;

class ComisionesController extends Controller
{
    private $entidad = 'Comisiones';
    public function __construct()
    {
        $this->middleware('can:puede.ver.comisiones')->only('index', 'show');
        $this->middleware('can:puede.crear.comisiones')->only('store');
    }
    public function index(Request $request)
    {
        $results = [];
        $results = Comisiones::ignoreRequest(['campos'])->filter()->get();
        $results = ComisionesResource::collection($results);
        return response()->json(compact('results'));
    }
    public function show(Request $request, Comisiones $umbral)
    {
        $results = new ComisionesResource($umbral);

        return response()->json(compact('results'));
    }
    public function store(ComisionesRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $umbral = Comisiones::create($datos);
            $modelo = new ComisionesResource($umbral);
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    public function update(ComisionesRequest $request, Comisiones $umbral)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $umbral->update($datos);
            $modelo = new ComisionesResource($umbral->refresh());
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    public function destroy(Request $request, Comisiones $umbral)
    {
        $umbral->delete();
        return response()->json(compact('umbral'));
    }
}
