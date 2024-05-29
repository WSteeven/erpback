<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\TipoEvaluacionRequest;
use App\Http\Resources\Medico\TipoEvaluacionResource;
use App\Models\Medico\TipoEvaluacion;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class TipoEvaluacionController extends Controller
{
    private $entidad = 'Tipo de evaluacion';

    public function __construct()
    {
        $this->middleware('can:puede.ver.tipos_evaluaciones')->only('index', 'show');
        $this->middleware('can:puede.crear.tipos_evaluaciones')->only('store');
        $this->middleware('can:puede.editar.tipos_evaluaciones')->only('update');
        $this->middleware('can:puede.eliminar.tipos_evaluaciones')->only('destroy');
    }

    public function index()
    {
        $results = [];
        $results = TipoEvaluacion::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }

    public function store(TipoEvaluacionRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $tipo_evaluacion = TipoEvaluacion::create($datos);
            $modelo = new TipoEvaluacionResource($tipo_evaluacion);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de tipo de evaluacion' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function show(TipoEvaluacion $tipo_evaluacion)
    {
        $modelo = new TipoEvaluacionResource($tipo_evaluacion);
        return response()->json(compact('modelo'));
    }


    public function update(TipoEvaluacionRequest $request, TipoEvaluacion $tipo_evaluacion)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $tipo_evaluacion->update($datos);
            $modelo = new TipoEvaluacionResource($tipo_evaluacion->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de tipo de evaluacion' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function destroy(TipoEvaluacion $tipo_evaluacion)
    {
        try {
            DB::beginTransaction();
            $tipo_evaluacion->delete();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
            DB::commit();
            return response()->json(compact('mensaje'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de tipo de evaluacion' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
}
