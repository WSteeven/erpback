<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\TipoFactorRiesgoRequest;
use App\Http\Resources\Medico\TipoFactorRiesgoResource;
use App\Models\Medico\TipoFactorRiesgo;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class TipoFactorRiesgoController extends Controller
{
    private $entidad = 'Tipo de factor de riesgo';

    public function __construct()
    {
        $this->middleware('can:puede.ver.tipos_factores_riesgos')->only('index', 'show');
        $this->middleware('can:puede.crear.tipos_factores_riesgos')->only('store');
        $this->middleware('can:puede.editar.tipos_factores_riesgos')->only('update');
        $this->middleware('can:puede.eliminar.tipos_factores_riesgos')->only('destroy');
    }

    public function index()
    {
        $results = [];
        $results = TipoFactorRiesgo::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }

    public function store(TipoFactorRiesgoRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $tipo_factor_riesgo = TipoFactorRiesgo::create($datos);
            $modelo = new TipoFactorRiesgoResource($tipo_factor_riesgo);
            $this->tabla_roles($tipo_factor_riesgo);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de tipo de factor de riesgo' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function show(TipoFactorRiesgoRequest $request, TipoFactorRiesgo $tipo_factor_riesgo)
    {
        $modelo = new TipoFactorRiesgoResource($tipo_factor_riesgo);
        return response()->json(compact('modelo'));
    }


    public function update(TipoFactorRiesgoRequest $request, TipoFactorRiesgo $tipo_factor_riesgo)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $tipo_factor_riesgo->update($datos);
            $modelo = new TipoFactorRiesgoResource($tipo_factor_riesgo->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de tipo de factor de riesgo' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function destroy(TipoFactorRiesgoRequest $request, TipoFactorRiesgo $tipo_factor_riesgo)
    {
        try {
            DB::beginTransaction();
            $tipo_factor_riesgo->delete();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
            DB::commit();
            return response()->json(compact('mensaje'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de tipo de factor de riesgo' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }}
