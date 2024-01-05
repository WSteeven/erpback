<?php

namespace App\Http\Controllers\RecursosHumanos\Alimentacion;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecursosHumanos\Alimentacion\AlimentacionRequest;
use App\Http\Resources\RecursosHumanos\Alimentacion\AlimentacionResource;
use App\Models\RecursosHumanos\Alimentacion\Alimentacion;
use App\Models\RecursosHumanos\Alimentacion\AsignarAlimentacion;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Src\Shared\Utils;

class AlimentacionController extends Controller
{
    private $entidad = 'Alimentacion';
    public function __construct()
    {
        $this->middleware('can:puede.ver.alimentacion')->only('index', 'show');
        $this->middleware('can:puede.crear.alimentacion')->only('store');
        $this->middleware('can:puede.editar.alimentacion')->only('update');
        $this->middleware('can:puede.eliminar.alimentacion')->only('destroy');
    }
    /**
     * Listar
     */
    public function index(Request $request)
    {
        $results = Alimentacion::filter()->get();
        $results = AlimentacionResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Guardar
     */
    public function store(AlimentacionRequest $request)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $asignaciones_alimentacion = AsignarAlimentacion::get();
            foreach ($asignaciones_alimentacion as $asignacion_alimentacion) {
                    Alimentacion::create([
                        'empleado_id' => $asignacion_alimentacion['empleado_id'],
                        'valor_asignado' => $asignacion_alimentacion['valor_minimo'],
                        'fecha_corte' => Carbon::now()->format('Y-m-d'),
                    ]);

            }
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            $modelo = [];
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['ERROR store de ordenes de compras:', $e->getMessage(), $e->getLine()]);
            return response()->json(['ERROR' => $e->getMessage() . ', ' . $e->getLine()], 422);
        }
    }
    /**
     * Consultar
     */
    public function show(Alimentacion $alimentacion)
    {
        $modelo = new AlimentacionResource($alimentacion);
        return response()->json(compact('modelo'));
    }

    /**
     * Actualizar
     */
    public function update(AlimentacionRequest $request, Alimentacion $alimentacion)
    {
        try {
            DB::beginTransaction();
            //Adaptacion de foreign keys
            $datos = $request->validated();
            //Creación de la alimentacion
            $alimentacion->update($datos);
            //Respuesta
            $modelo = new AlimentacionResource($alimentacion);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
             DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['ERROR update de alimentacion:', $e->getMessage(), $e->getLine()]);
            return response()->json(['ERROR' => $e->getMessage() . ', ' . $e->getLine()], 422);
        }
    }
    public function destroy(Request $request, Alimentacion $alimentacion)
    {
        $alimentacion->delete();
        return response()->json(compact('alimentacion'));
    }
}
