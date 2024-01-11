<?php

namespace App\Http\Controllers\RecursosHumanos\Alimentacion;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecursosHumanos\Alimentacion\DetalleAlimentacionRequest;
use App\Http\Resources\RecursosHumanos\Alimentacion\DetalleAlimentacionResource;
use App\Models\RecursosHumanos\Alimentacion\AsignarAlimentacion;
use App\Models\RecursosHumanos\Alimentacion\DetalleAlimentacion;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Src\Shared\Utils;

class DetalleAlimentacionController extends Controller
{

    private $entidad = 'Detalle de Alimentacion';
    public function __construct()
    {
        $this->middleware('can:puede.ver.detalle_detalle_alimentacion')->only('index', 'show');
        $this->middleware('can:puede.crear.detalle_detalle_alimentacion')->only('store');
        $this->middleware('can:puede.editar.detalle_detalle_alimentacion')->only('update');
        $this->middleware('can:puede.eliminar.detalle_detalle_alimentacion')->only('destroy');
    }
    /**
     * Listar
     */
    public function index(Request $request)
    {
        $results = DetalleAlimentacion::filter()->get();
        $results = DetalleAlimentacionResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Guardar
     */
    public function store(DetalleAlimentacionRequest $request)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $asignaciones_detalle_alimentacion = AsignarAlimentacion::get();
            foreach ($asignaciones_detalle_alimentacion as $asignacion_detalle_alimentacion) {
                    DetalleAlimentacion::create([
                        'empleado_id' => $asignacion_detalle_alimentacion['empleado_id'],
                        'valor_asignado' => $asignacion_detalle_alimentacion['valor_minimo'],
                        'fecha_corte' => Carbon::now()->format('Y-m-d'),
                        'alimentacion_id' => $datos['alimentacion_id']
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
    public function show(DetalleAlimentacion $detalle_alimentacion)
    {
        $modelo = new DetalleAlimentacionResource($detalle_alimentacion);
        return response()->json(compact('modelo'));
    }

    /**
     * Actualizar
     */
    public function update(DetalleAlimentacionRequest $request, DetalleAlimentacion $detalle_alimentacion)
    {
        try {
            DB::beginTransaction();
            //Adaptacion de foreign keys
            $datos = $request->validated();
            //Creación de la detalle_alimentacion
            $detalle_alimentacion->update($datos);
            //Respuesta
            $modelo = new DetalleAlimentacionResource($detalle_alimentacion);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
             DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['ERROR update de detalle_alimentacion:', $e->getMessage(), $e->getLine()]);
            return response()->json(['ERROR' => $e->getMessage() . ', ' . $e->getLine()], 422);
        }
    }
    public function destroy(Request $request, DetalleAlimentacion $detalle_alimentacion)
    {
        $detalle_alimentacion->delete();
        return response()->json(compact('detalle_alimentacion'));
    }
}
