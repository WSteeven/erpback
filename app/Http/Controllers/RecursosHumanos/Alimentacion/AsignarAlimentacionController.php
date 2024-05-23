<?php

namespace App\Http\Controllers\RecursosHumanos\Alimentacion;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecursosHumanos\Alimentacion\AsignarAlimentacionRequest;
use App\Http\Resources\RecursosHumanos\Alimentacion\AsignarAlimentacionResource;
use App\Models\Empleado;
use App\Models\RecursosHumanos\Alimentacion\AsignarAlimentacion;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Src\Shared\Utils;

class AsignarAlimentacionController extends Controller
{
    private $entidad = 'Asignar Alimentacion';
    public function __construct()
    {
        $this->middleware('can:puede.ver.asignar_alimentaciones')->only('index', 'show');
        $this->middleware('can:puede.crear.asignar_alimentaciones')->only('store');
        $this->middleware('can:puede.editar.asignar_alimentaciones')->only('update');
        $this->middleware('can:puede.eliminar.asignar_alimentaciones')->only('destroy');
    }
    /**
     * Listar
     */
    public function index(Request $request)
    {
        $results = AsignarAlimentacion::filter()->get();
        $results = AsignarAlimentacionResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Guardar
     */
    public function store(AsignarAlimentacionRequest $request)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $modelo = null;
            if (isset($datos['empleado'])) {
                $modelo = new AsignarAlimentacionResource($this->asignarAlimentacionEmpleado($datos));
            } else {
                $modelo = $this->asignarAlimentacionArray($datos['empleados'],$datos['valor_minimo']);
            }
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['ERROR store de ordenes de compras:', $e->getMessage(), $e->getLine()]);
            return response()->json(['ERROR' => $e->getMessage() . ', ' . $e->getLine()], 422);
        }
    }
    private function asignarAlimentacionEmpleado($datos){
        $asignar_alimentacion = AsignarAlimentacion::create([
            'empleado_id' => $datos['empleado'],
            'valor_minimo' => $datos['valor_minimo'],
        ]);
        return $asignar_alimentacion;
    }
    private function asignarAlimentacionArray($empleados,$valor_minimo){
        foreach ($empleados as $empleado) {
            $registro = AsignarAlimentacion::where('empleado_id', $empleado['id'])->first();
            if (!$registro) {
                AsignarAlimentacion::create([
                    'empleado_id' => $empleado['id'],
                    'valor_minimo' => $valor_minimo,
                ]);
            }
        }
    }
    /**
     * Consultar
     */
    public function show(AsignarAlimentacion $asignar_alimentacion)
    {
        $modelo = new AsignarAlimentacionResource($asignar_alimentacion);
        return response()->json(compact('modelo'));
    }

    /**
     * Actualizar
     */
    public function update(AsignarAlimentacionRequest $request, AsignarAlimentacion $asignar_alimentacion)
    {
        try {
            DB::beginTransaction();
            //Adaptacion de foreign keys
            $datos = $request->validated();
            $datos['solicitante_id'] = $request->safe()->only(['solicitante'])['solicitante'];
            $datos['cliente_id'] = $request->safe()->only(['cliente'])['cliente'];
            $datos['estado_id'] = $request->safe()->only(['estado'])['estado'];
            if ($request->preorden) $datos['preorden_id'] = $request->safe()->only(['preorden'])['preorden'];
            if ($request->pedido) $datos['pedido_id'] = $request->safe()->only(['pedido'])['pedido'];
            //Creación de la asignar_alimentacion
            $asignar_alimentacion->update($datos);
            // Sincronizar los detalles de la orden de compra
            AsignarAlimentacion::guardarDetalles($asignar_alimentacion, $request->listadoProductos);
            //Respuesta
            $modelo = new AsignarAlimentacionResource($asignar_alimentacion);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['ERROR update de asignar_alimentacion:', $e->getMessage(), $e->getLine()]);
            return response()->json(['ERROR' => $e->getMessage() . ', ' . $e->getLine()], 422);
        }
    }
}
