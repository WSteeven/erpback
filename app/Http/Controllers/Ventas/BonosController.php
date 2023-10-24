<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ventas\BonosRequest;
use App\Http\Resources\Ventas\BonosResource;
use App\Models\Ventas\Bonos;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Src\Shared\Utils;

class BonosController extends Controller
{
    private $entidad = 'Bonos';
    public function __construct()
    {
        $this->middleware('can:puede.ver.bonos')->only('index', 'show');
        $this->middleware('can:puede.crear.bonos')->only('store');
    }
    public function index(Request $request)
    {
        $results = [];
        $results = Bonos::ignoreRequest(['campos'])->filter()->get();
        $results = BonosResource::collection($results);
        return response()->json(compact('results'));
    }
    public function show(Request $request, Bonos $umbral)
    {
        $results = new BonosResource($umbral);

        return response()->json(compact('results'));
    }
    public function store(BonosRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $umbral = Bonos::create($datos);
            $modelo = new BonosResource($umbral);
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    public function update(BonosRequest $request, Bonos $umbral)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $umbral->update($datos);
            $modelo = new BonosResource($umbral->refresh());
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    public function destroy(Request $request, Bonos $umbral)
    {
        $umbral->delete();
        return response()->json(compact('umbral'));
    }
}
