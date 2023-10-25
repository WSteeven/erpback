<?php

namespace App\Http\Controllers\FondosRotativos;

use App\Http\Controllers\Controller;
use App\Http\Requests\FondosRotativos\UmbralFondosRotativosRequest;
use App\Http\Resources\FondosRotativos\UmbralFondosRotativosResource;
use App\Models\FondosRotativos\UmbralFondosRotativos;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Src\Shared\Utils;

class UmbralFondosRotativosController extends Controller
{
    private $entidad = 'Umbral';
    public function __construct()
    {
        $this->middleware('can:puede.ver.umbral_fondos_rotativos')->only('index', 'show');
        $this->middleware('can:puede.crear.umbral_fondos_rotativos')->only('store');
    }
    public function index(Request $request)
    {
        $results = [];
        $results = UmbralFondosRotativos::ignoreRequest(['campos'])->filter()->get();
        $results = UmbralFondosRotativosResource::collection($results);
        return response()->json(compact('results'));
    }
    public function show(Request $request, UmbralFondosRotativos $umbral)
    {
        $results = new UmbralFondosRotativosResource($umbral);

        return response()->json(compact('results'));
    }
    public function store(UmbralFondosRotativosRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $umbral = UmbralFondosRotativos::create($datos);
            $modelo = new UmbralFondosRotativosResource($umbral);
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    public function update(UmbralFondosRotativosRequest $request, UmbralFondosRotativos $umbral)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $umbral->update($datos);
            $modelo = new UmbralFondosRotativosResource($umbral->refresh());
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    public function destroy(Request $request, UmbralFondosRotativos $umbral)
    {
        $umbral->delete();
        return response()->json(compact('umbral'));
    }
}
