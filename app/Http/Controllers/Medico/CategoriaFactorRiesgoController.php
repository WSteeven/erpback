<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\CategoriaFactorRiesgoRequest;
use App\Http\Resources\Medico\CategoriaFactorRiesgoResource;
use App\Models\Medico\CategoriaFactorRiesgo;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class CategoriaFactorRiesgoController extends Controller
{
    private $entidad = 'Tipo de factor de riesgo';

    public function __construct()
    {
        $this->middleware('can:puede.ver.categorias_factores_riesgos')->only('index', 'show');
        $this->middleware('can:puede.crear.categorias_factores_riesgos')->only('store');
        $this->middleware('can:puede.editar.categorias_factores_riesgos')->only('update');
        $this->middleware('can:puede.eliminar.categorias_factores_riesgos')->only('destroy');
    }

    public function index()
    {
        $results = [];
        $results = CategoriaFactorRiesgo::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }

    public function store(CategoriaFactorRiesgoRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $categoria_factor_riesgo = CategoriaFactorRiesgo::create($datos);
            $modelo = new CategoriaFactorRiesgoResource($categoria_factor_riesgo);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de categoria factor riesgo' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function show(CategoriaFactorRiesgoRequest $request, CategoriaFactorRiesgo $categoria_factor_riesgo)
    {
        $modelo = new CategoriaFactorRiesgoResource($categoria_factor_riesgo);
        return response()->json(compact('modelo'));
    }


    public function update(CategoriaFactorRiesgoRequest $request, CategoriaFactorRiesgo $categoria_factor_riesgo)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $categoria_factor_riesgo->update($datos);
            $modelo = new CategoriaFactorRiesgoResource($categoria_factor_riesgo->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de categoria factor riesgo' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function destroy(CategoriaFactorRiesgoRequest $request, CategoriaFactorRiesgo $categoria_factor_riesgo)
    {
        try {
            DB::beginTransaction();
            $categoria_factor_riesgo->delete();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
            DB::commit();
            return response()->json(compact('mensaje'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de categoria factor riesgo' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
}
