<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\FactorRiesgoRequest;
use App\Http\Resources\Medico\FactorRiesgoResource;
use App\Models\Medico\FactorRiesgo;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class FactorRiesgoController extends Controller
{
    private $entidad = 'Factor de riesgo';

    public function __construct()
    {
        $this->middleware('can:puede.ver.factores_riesgos')->only('index', 'show');
        $this->middleware('can:puede.crear.factores_riesgos')->only('store');
        $this->middleware('can:puede.editar.factores_riesgos')->only('update');
        $this->middleware('can:puede.eliminar.factores_riesgos')->only('destroy');
    }

    public function index()
    {
        $results = [];
        $results = FactorRiesgo::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }

    public function store(FactorRiesgoRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $factor_riesgo = FactorRiesgo::create($datos);
            $modelo = new FactorRiesgoResource($factor_riesgo);
            $this->tabla_roles($factor_riesgo);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de factor de riesgo' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function show(FactorRiesgoRequest $request, FactorRiesgo $factor_riesgo)
    {
        $modelo = new FactorRiesgoResource($factor_riesgo);
        return response()->json(compact('modelo'));
    }


    public function update(FactorRiesgoRequest $request, FactorRiesgo $factor_riesgo)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $factor_riesgo->update($datos);
            $modelo = new FactorRiesgoResource($factor_riesgo->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de factor de riesgo' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function destroy(FactorRiesgoRequest $request, FactorRiesgo $factor_riesgo)
    {
        try {
            DB::beginTransaction();
            $factor_riesgo->delete();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
            DB::commit();
            return response()->json(compact('mensaje'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de factor de riesgo' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
}
