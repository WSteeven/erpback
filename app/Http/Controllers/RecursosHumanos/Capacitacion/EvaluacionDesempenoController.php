<?php

namespace App\Http\Controllers\RecursosHumanos\Capacitacion;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecursosHumanos\Capacitacion\EvaluacionDesempenoRequest;
use App\Http\Resources\RecursosHumanos\Capacitacion\EvaluacionDesempenoResource;
use App\Models\ConfiguracionGeneral;
use App\Models\Departamento;
use App\Models\RecursosHumanos\Capacitacion\EvaluacionDesempeno;
use Barryvdh\DomPDF\Facade\Pdf;
use DB;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;
use Throwable;

class EvaluacionDesempenoController extends Controller
{
    private string $entidad = 'Evaluación de Desempeño';

    public function __construct()
    {
        $this->middleware('can:puede.ver.rrhh_capacitacion_evaluaciones_desempeno')->only('index', 'show');
        $this->middleware('can:puede.crear.rrhh_capacitacion_evaluaciones_desempeno')->only('store');
        $this->middleware('can:puede.editar.rrhh_capacitacion_evaluaciones_desempeno')->only('update');
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $results = EvaluacionDesempeno::filter()->get();
        Log::channel('testing')->info('Log', ['Resultados antes del resource', $results]);
        $results = EvaluacionDesempenoResource::collection($results);
        Log::channel('testing')->info('Log', ['Resultados luego del resource', $results]);
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param EvaluacionDesempenoRequest $request
     * @return JsonResponse
     * @throws ValidationException
     * @throws Throwable
     */
    public function store(EvaluacionDesempenoRequest $request)
    {
        $datos = $request->validated();
        // Respuesta
        try {
            DB::beginTransaction();
            $modelo = EvaluacionDesempeno::create($datos);
            $modelo = new EvaluacionDesempenoResource($modelo);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw Utils::obtenerMensajeErrorLanzable($e);
        }
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Display the specified resource.
     *
     * @param EvaluacionDesempeno $evaluacion
     * @return JsonResponse
     */
    public function show(EvaluacionDesempeno $evaluacion)
    {
        $modelo = new EvaluacionDesempenoResource($evaluacion);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param EvaluacionDesempenoRequest $request
     * @param EvaluacionDesempeno $evaluacion
     * @return JsonResponse
     * @throws ValidationException
     * @throws Throwable
     */
    public function update(EvaluacionDesempenoRequest $request, EvaluacionDesempeno $evaluacion)
    {
        // Adaptacion de foreign keys
        $datos = $request->validated();
        try {
            DB::beginTransaction();
            $evaluacion->update($datos);
            $modelo = new EvaluacionDesempenoResource($evaluacion->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw Utils::obtenerMensajeErrorLanzable($e);
        }
        return response()->json(compact('modelo', 'mensaje'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return Response
     * @throws ValidationException
     */
    public function destroy()
    {
        throw ValidationException::withMessages(['Error' => Utils::metodoNoDesarrollado()]);
    }

    /**
     * @throws ValidationException
     */
    public function imprimir(EvaluacionDesempeno $evaluacion)
    {
        $configuracion = ConfiguracionGeneral::first();
        try {
            $pdf = Pdf::loadView('capacitacion.evaluacion_desempeno', [
                'configuracion' => $configuracion,
                'evaluacion' => $evaluacion,
                'departamento_rrhh' => Departamento::where('nombre', Departamento::DEPARTAMENTO_RRHH)->first(),
            ]);
            $pdf->render();
            return $pdf->output();
        }catch (Exception $e) {
            throw Utils::obtenerMensajeErrorLanzable($e, 'No se puede imprimir el pdf: ');
        }
    }

}
