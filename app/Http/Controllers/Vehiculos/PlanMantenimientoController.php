<?php

namespace App\Http\Controllers\Vehiculos;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vehiculos\PlanMantenimientoRequest;
use App\Http\Resources\Vehiculos\PlanMantenimientoResource;
use App\Models\Vehiculos\PlanMantenimiento;
use App\Models\Vehiculos\Vehiculo;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class PlanMantenimientoController extends Controller
{
    private $entidad = 'Plan de mantenimiento';
    public function __construct()
    {
        $this->middleware('can:puede.ver.planes_mantenimientos')->only('index', 'show');
        $this->middleware('can:puede.crear.planes_mantenimientos')->only('store');
        $this->middleware('can:puede.editar.planes_mantenimientos')->only('update');
        $this->middleware('can:puede.eliminar.planes_mantenimientos')->only('destroy');
    }

    public function index()
    {
        $results = Vehiculo::filter()->orderBy('placa', 'asc')->get();
        $results = PlanMantenimientoResource::collection($results);
        return response()->json(compact('results'));
    }

    public function store(PlanMantenimientoRequest $request)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            throw new Exception('No puedes guardar un plan de mantenimiento');
            // $servicio = Servicio::create($datos);
            // $modelo = new ServicioResource($servicio);
            // $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (\Exception $e) {
            $mensaje = '(' . $e->getLine() . ') Hubo un error al registrar el plan de mantenimiento: ' . $e->getMessage();
            throw ValidationException::withMessages([
                '500' => [$mensaje],
            ]);
            return response()->json(compact('mensaje'), 500);
        }
    }

    public function show(Vehiculo $vehiculo)
    {
        $modelo = new PlanMantenimientoResource($vehiculo);
        return response()->json(compact('modelo'));
    }

    public function update(PlanMantenimientoRequest $request, Vehiculo $vehiculo)
    {
        Log::channel('testing')->info('Log', ['request on update', $request->all()]);
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            foreach ($request->listadoServicios as $servicio) {
                $plan = PlanMantenimiento::upsert([
                    'vehiculo_id' => $request->vehiculo,
                    'servicio_id' => $servicio['id'],
                    'aplicar_desde' => $request->aplicar_desde,
                    'aplicar_cada' => $servicio['intervalo'],
                    'activo' => $request->activo,
                ], uniqueBy: ['vehiculo_id', 'servicio_id'], update: ['aplicar_desde', 'aplicar_cada', 'activo']);
            }
            // throw new Exception('No se puede actualizar ');
            // $servicio->update($datos);
            $modelo = new PlanMantenimientoResource($vehiculo);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (\Exception $e) {
            $mensaje = '(' . $e->getLine() . ') Hubo un error al actualizar el plan de mantenimiento: ' . $e->getMessage();
            throw ValidationException::withMessages([
                '500' => [$mensaje],
            ]);
        }
    }
}
