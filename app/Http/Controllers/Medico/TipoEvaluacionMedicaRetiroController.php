<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\TipoEvaluacionMedicaRetiroRequest;
use App\Http\Resources\Medico\TipoEvaluacionMedicaRetiroResource;
use App\Models\Medico\TipoEvaluacionMedicaRetiro;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class TipoEvaluacionMedicaRetiroMedicaRetiroController extends Controller
{
    private $entidad = 'Tipo de evaluaciones medicas de retiro';

    public function __construct()
    {
        $this->middleware('can:puede.ver.tipos_evaluaciones_medica_retiros')->only('index', 'show');
        $this->middleware('can:puede.crear.tipos_evaluaciones_medica_retiros')->only('store');
        $this->middleware('can:puede.editar.tipos_evaluaciones_medica_retiros')->only('update');
        $this->middleware('can:puede.eliminar.tipos_evaluaciones_medica_retiros')->only('destroy');
    }

    public function index()
    {
        $results = [];
        $results = TipoEvaluacionMedicaRetiro::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }

    public function store(TipoEvaluacionMedicaRetiroRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $tipo_evaluacion_medica_retiro = TipoEvaluacionMedicaRetiro::create($datos);
            $modelo = new TipoEvaluacionMedicaRetiroResource($tipo_evaluacion_medica_retiro);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de tipo de evaluaciones medicas de retiro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function show(TipoEvaluacionMedicaRetiro $tipo_evaluacion_medica_retiro)
    {
        $modelo = new TipoEvaluacionMedicaRetiroResource($tipo_evaluacion_medica_retiro);
        return response()->json(compact('modelo'));
    }


    public function update(TipoEvaluacionMedicaRetiroRequest $request, TipoEvaluacionMedicaRetiro $tipo_evaluacion_medica_retiro)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $tipo_evaluacion_medica_retiro->update($datos);
            $modelo = new TipoEvaluacionMedicaRetiroResource($tipo_evaluacion_medica_retiro->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de tipo de evaluaciones medicas de retiro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function destroy(TipoEvaluacionMedicaRetiro $tipo_evaluacion_medica_retiro)
    {
        try {
            DB::beginTransaction();
            $tipo_evaluacion_medica_retiro->delete();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
            DB::commit();
            return response()->json(compact('mensaje'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de tipo de evaluaciones medicas de retiro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
}
