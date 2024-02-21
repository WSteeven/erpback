<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ventas\BonoRequest;
use App\Http\Resources\Ventas\BonoResource;
use App\Models\Ventas\Bono;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Src\Shared\Utils;

class BonoController extends Controller
{
    private $entidad = 'Bono';
    public function __construct()
    {
        $this->middleware('can:puede.ver.bonos')->only('index', 'show');
        $this->middleware('can:puede.crear.bonos')->only('store');
        $this->middleware('can:puede.editar.bonos')->only('update');
        $this->middleware('can:puede.eliminar.bonos')->only('destroy');
    }
    public function index(Request $request)
    {
        $results = [];
        $results = Bono::ignoreRequest(['campos'])->filter()->get();
        $results = BonoResource::collection($results);
        return response()->json(compact('results'));
    }
    public function show(Request $request, Bono $umbral)
    {
        $results = new BonoResource($umbral);

        return response()->json(compact('results'));
    }
    public function store(BonoRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $umbral = Bono::create($datos);
            $modelo = new BonoResource($umbral);
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    public function update(BonoRequest $request, Bono $umbral)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $umbral->update($datos);
            $modelo = new BonoResource($umbral->refresh());
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    public function destroy(Request $request, Bono $umbral)
    {
        $umbral->delete();
        return response()->json(compact('umbral'));
    }
}
