<?php

namespace App\Http\Controllers\RecursosHumanos;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecursosHumanos\PlanificadorRequest;
use App\Http\Resources\RecursosHumanos\PlanificadorResource;
use App\Models\ConfiguracionGeneral;
use App\Models\RecursosHumanos\Planificador;
use Barryvdh\DomPDF\Facade\Pdf;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;
use Throwable;

class PlanificadorController extends Controller
{
    private string $entidad = 'Planificador';

    public function __construct()
    {
        $this->middleware('can:puede.ver.planificadores')->only('index', 'show');
        $this->middleware('can:puede.crear.planificadores')->only('store');
        $this->middleware('can:puede.editar.planificadores')->only('update');
        $this->middleware('can:puede.eliminar.planificadores')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $results = Planificador::all();
        $results = PlanificadorResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PlanificadorRequest $request
     * @return JsonResponse
     * @throws Throwable|ValidationException
     */
    public function store(PlanificadorRequest $request)
    {
        $datos = $request->validated();
        try {
            DB::beginTransaction();
            $plan = Planificador::create($datos);

            $modelo = new PlanificadorResource($plan);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            throw Utils::obtenerMensajeErrorLanzable($th);
        }
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Display the specified resource.
     *
     * @param Planificador $plan
     * @return JsonResponse
     */
    public function show(Planificador $plan)
    {
        $modelo = new PlanificadorResource($plan);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param PlanificadorRequest $request
     * @param Planificador $plan
     * @return JsonResponse
     */
    public function update(PlanificadorRequest $request, Planificador $plan)
    {
        $plan->update($request->validated());
        $modelo = new PlanificadorResource($plan);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('modelo', 'mensaje'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Planificador $plan
     * @return JsonResponse
     */
    public function destroy(Planificador $plan)
    {
        $plan->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }

    /**
     * @throws ValidationException
     */
    public function imprimir(Planificador $plan)
    {
        $configuracion = ConfiguracionGeneral::first();

        try {
            $pdf = Pdf::loadView('recursos-humanos.reporte_planificacion', compact('plan', 'configuracion'));
//            $pdf->setPaper('A4');
            $pdf->setPaper('A4', 'landscape');
            $pdf->getDomPDF()->setCallbacks(['totalPages' => true]);
            $pdf->render();
            return $pdf->output();
        } catch (Throwable $th) {
            throw Utils::obtenerMensajeErrorLanzable($th, 'Imprmir Reporte');
        }
    }
}
