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
        $this->middleware('can:puede.ver.asignar_alimentacion')->only('index', 'show');
        $this->middleware('can:puede.crear.asignar_alimentacion')->only('store');
        $this->middleware('can:puede.editar.asignar_alimentacion')->only('update');
        $this->middleware('can:puede.eliminar.asignar_alimentacion')->only('destroy');
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
            foreach ($datos['empleados'] as $empleado) {
                $registro = AsignarAlimentacion::where('empleado_id', $empleado['id'])->first();
                if (!$registro) {
                    AsignarAlimentacion::create([
                        'empleado_id' => $empleado['id'],
                        'valor_minimo' => $datos['valor_minimo'],
                    ]);
                }
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
    public function show(AsignarAlimentacion $prefactura)
    {
        $modelo = new AsignarAlimentacionResource($prefactura);
        return response()->json(compact('modelo'));
    }

    /**
     * Actualizar
     */
    public function update(AsignarAlimentacionRequest $request, AsignarAlimentacion $prefactura)
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

            Log::channel('testing')->info('Log', ['Datos validados:', $datos]);

            //Creación de la prefactura
            $prefactura->update($datos);
            // Sincronizar los detalles de la orden de compra
            AsignarAlimentacion::guardarDetalles($prefactura, $request->listadoProductos);

            //Respuesta
            $modelo = new AsignarAlimentacionResource($prefactura);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');


            DB::commit();

            // // aqui se debe lanzar la notificacion en caso de que la prefactura sea autorizacion pendiente
            // if ($prefactura->estado_id === $estado_completo->id && $prefactura->autorizacion_id === $autorizacion_aprobada->id) {
            // $prefactura->latestNotificacion()->update(['leida'=>true]);//marcando como leída la notificacion en caso de que esté vigente
            // event(new AsignarAlimentacionActualizadaEvent($prefactura, true));// crea el evento de la orden de compra actualizada al solicitante
            // }

            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['ERROR update de asignar_alimentacion:', $e->getMessage(), $e->getLine()]);
            return response()->json(['ERROR' => $e->getMessage() . ', ' . $e->getLine()], 422);
        }
    }
}
