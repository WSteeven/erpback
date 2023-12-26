<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ventas\BonoPorcentualRequest;
use App\Http\Resources\Ventas\BonoPorcentualResource;
use App\Models\Ventas\BonoPorcentual;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Src\Shared\Utils;

class BonoPorcentualController extends Controller
{
    private $entidad = 'BonoPorcentual';
    public function __construct()
    {
        $this->middleware('can:puede.ver.bono_porcentual')->only('index', 'show');
        $this->middleware('can:puede.crear.bono_porcentual')->only('store');
    }
    public function index(Request $request)
    {
        $results = [];
        $results = BonoPorcentual::ignoreRequest(['campos'])->filter()->get();
        $results = BonoPorcentualResource::collection($results);
        return response()->json(compact('results'));
    }
    public function show(Request $request, BonoPorcentual $bono_porcentual)
    {
        $results = new BonoPorcentualResource($bono_porcentual);

        return response()->json(compact('results'));
    }
    public function store(BonoPorcentualRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $bono_porcentual = BonoPorcentual::create($datos);
            $modelo = new BonoPorcentualResource($bono_porcentual);
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    public function update(BonoPorcentualRequest $request, BonoPorcentual $bono_porcentual)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $bono_porcentual->update($datos);
            $modelo = new BonoPorcentualResource($bono_porcentual->refresh());
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    public function destroy(Request $request, BonoPorcentual $bono_porcentual)
    {
        $bono_porcentual->delete();
        return response()->json(compact('bono_porcentual'));
    }
}
