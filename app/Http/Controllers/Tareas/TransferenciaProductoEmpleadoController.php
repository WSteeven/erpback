<?php

namespace App\Http\Controllers\Tareas;

use App\Http\Resources\Tareas\TransferenciaProductoEmpleadoResource;
use App\Http\Requests\Tareas\TransferenciaProductoEmpleadoRequest;
use Src\App\Tareas\TransferenciaProductoEmpleadoService;
use App\Models\Tareas\TransferenciaProductoEmpleado;
use Src\App\Tareas\ProductoTareaEmpleadoService;
use App\Http\Controllers\Controller;
use App\Models\Autorizacion;
use App\Models\MaterialEmpleadoTarea;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Src\Shared\Utils;
use Exception;

class TransferenciaProductoEmpleadoController extends Controller
{
    private $entidad = 'Transferencia';
    private $transferenciaService;
    private $productosTareaEmpleadoService;

    public function __construct()
    {
        $this->transferenciaService = new TransferenciaProductoEmpleadoService();
        $this->productosTareaEmpleadoService = new ProductoTareaEmpleadoService();
    }

    public function index(Request $request)
    {
        // $results = TransferenciaProductoEmpleado::filter()->get();
        $results = $this->transferenciaService->filtrarTransferencias($request);
        $results = TransferenciaProductoEmpleadoResource::collection($results);
        return response()->json(compact('results'));
    }

    public function store(TransferenciaProductoEmpleadoRequest $request)
    {
        try {
            DB::beginTransaction();

            // Adaptacion de foreign keys
            $datos = $request->validated();
            $datos['solicitante_id'] = $request['solicitante'];
            $datos['empleado_origen_id'] = $request['empleado_origen'];
            $datos['empleado_destino_id'] = $request['empleado_destino'];
            $datos['proyecto_origen_id'] = $request['proyecto_origen'];
            $datos['proyecto_destino_id'] = $request['proyecto_destino'];
            $datos['etapa_origen_id'] = $request['etapa_origen'];
            $datos['etapa_destino_id'] = $request['etapa_destino'];
            $datos['tarea_origen_id'] = $request['tarea_origen'];
            $datos['tarea_destino_id'] = $request['tarea_destino'];
            $datos['autorizador_id'] = $request['autorizador'];
            $datos['cliente_id'] = $request['cliente'];
            $datos['autorizacion_id'] = Autorizacion::PENDIENTE_ID; // $request['autorizacion'];
            $cliente_id = $request['cliente_id'];

            $transferencia = TransferenciaProductoEmpleado::create($datos);

            foreach ($request->listado_productos as $listado) {
                $transferencia->detallesTransferenciaProductoEmpleado()->attach($listado['id'], ['cantidad' => $listado['cantidad']]);//, 'cliente_id' => $cliente_id]);
            }

            $modelo = new TransferenciaProductoEmpleadoResource($transferencia);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

            DB::commit();
            // $msg = 'Devolución N°' . $devolucion->id . ' ' . $devolucion->solicitante->nombres . ' ' . $devolucion->solicitante->apellidos . ' ha realizado una devolución en la sucursal ' . $devolucion->sucursal->lugar . ' . La autorización está ' . $devolucion->autorizacion->nombre;
            // event(new DevolucionCreadaEvent($msg, $url, $devolucion, $devolucion->solicitante_id, $devolucion->per_autoriza_id, false));
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['ERROR del catch', $e->getMessage(), $e->getLine()]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro'], 422);
        }

        return response()->json(compact('mensaje', 'modelo'));
    }

    public function show(TransferenciaProductoEmpleado $transferencia_producto_empleado)
    {
        $modelo = new TransferenciaProductoEmpleadoResource($transferencia_producto_empleado);
        return response()->json(compact('modelo'));
    }

    public function update(TransferenciaProductoEmpleadoRequest $request, TransferenciaProductoEmpleado $transferencia_producto_empleado)
    {
        $url = '/transferencia-producto-empleado';

        $datos = $request->validated();
        $datos['solicitante_id'] = $request['solicitante'];
        $datos['empleado_origen_id'] = $request['empleado_origen'];
        $datos['empleado_destino_id'] = $request['empleado_destino'];
        $datos['proyecto_origen_id'] = $request['proyecto_origen'];
        $datos['proyecto_destino_id'] = $request['proyecto_destino'];
        $datos['etapa_origen_id'] = $request['etapa_origen'];
        $datos['etapa_destino_id'] = $request['etapa_destino'];
        $datos['tarea_origen_id'] = $request['tarea_origen'];
        $datos['tarea_destino_id'] = $request['tarea_destino'];
        $datos['autorizacion_id'] = $request['autorizacion'];
        $datos['autorizador_id'] = $request['autorizador'];
        // $cliente_id = $request['cliente_id'];

        $transferencia_producto_empleado->update($datos);

        // Borrar los registros de la tabla intermedia para guardar los modificados
        $transferencia_producto_empleado->detallesTransferenciaProductoEmpleado()->detach();

        // Guardar los productos seleccionados
        foreach ($request->listado_productos as $listado) {
            $transferencia_producto_empleado->detallesTransferenciaProductoEmpleado()->attach($listado['id'], ['cantidad' => $listado['cantidad']]); ///, 'cliente_id' => $cliente_id]);
        }

        if ($datos['autorizacion_id'] === Autorizacion::APROBADO_ID) {
            $mensaje = 'dentro de if';
            Log::channel('testing')->info('Log', compact('mensaje'));

            $this->ajustarValoresProducto($transferencia_producto_empleado);
        }

        $modelo = new TransferenciaProductoEmpleadoResource($transferencia_producto_empleado->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        /* $proyecto_id = request('proyecto_id');
        $etapa_id = request('etapa_id');
        $tarea_id = request('tarea_id'); */

        // $results = $this->productosTareaEmpleadoService->listarProductosConStock($empleado_origen_id, $proyecto_id, $etapa_id, $tarea_id);


        // $msg = $transferencia_producto_empleado->autoriza->nombres . ' ' . $transferencia_producto_empleado->autoriza->apellidos . ' ha actualizado tu devolución, el estado de Autorización es: ' . $transferencia_producto_empleado->autorizacion->nombre;
        // event(new DevolucionActualizadaSolicitanteEvent($msg, $url, $devolucion, $devolucion->per_autoriza_id, $devolucion->solicitante_id, true)); //Se usa para notificar al tecnico que se actualizó la devolucion

        /* if ($transferencia_producto_empleado->autorizacion->nombre === Autorizacion::APROBADO) {
            $transferencia_producto_empleado->latestNotificacion()->update(['leida' => true]);
            $msg = 'Hay una devolución recién autorizada en la sucursal ' . $devolucion->sucursal->lugar . ' pendiente de despacho';
            // event(new DevolucionAutorizadaEvent($msg, User::ROL_BODEGA, $url, $devolucion, true));
        } */
        return response()->json(compact('mensaje', 'modelo'));
    }

    private function consultarProducto($empleado_id, $detalle_producto_id, $proyecto_id, $etapa_id, $tarea_id, $cliente_id)
    {
        $consulta = MaterialEmpleadoTarea::where('empleado_id', $empleado_id)
            ->where('detalle_producto_id', $detalle_producto_id)
            ->where('proyecto_id', $proyecto_id)
            ->where('etapa_id', $etapa_id)
            ->where('cliente_id', $cliente_id)
            ->tieneStock();


        // $sql = $consulta->toSql();
        Log::channel('testing')->info('Log', compact('empleado_id', 'detalle_producto_id', 'proyecto_id', 'etapa_id', 'cliente_id'));

        return $consulta->first();
        // ->where('tarea_id', $tarea_id)
    }

    public function ajustarValoresProducto(TransferenciaProductoEmpleado $transferencia_producto_empleado)
    {
        $cliente_id = $transferencia_producto_empleado->cliente_id; // request('cliente');

        // Origen
        $empleado_origen_id = request('empleado_origen');
        $proyecto_origen_id = request('proyecto_origen');
        $etapa_origen_id = request('etapa_origen');
        $tarea_origen_id = request('tarea_origen');

        // Destino
        $empleado_destino_id = request('empleado_destino');
        $proyecto_destino_id = request('proyecto_destino');
        $etapa_destino_id = request('etapa_destino');
        $tarea_destino_id = request('tarea_destino');

        foreach (request('listado_productos') as $producto) {
            // Restar productos origen
            $productoOrigen = $this->consultarProducto($empleado_origen_id, $producto['id'], $proyecto_origen_id, $etapa_origen_id, $tarea_origen_id, $cliente_id);
            $productoOrigen->cantidad_stock -= $producto['cantidad'];
            $productoOrigen->save();

            Log::channel('testing')->info('Log', compact('productoOrigen'));

            if ($productoOrigen) {
                // Sumar productos destino
                $productoDestino = $this->consultarProducto($empleado_destino_id, $producto['id'], $proyecto_destino_id, $etapa_destino_id, $tarea_destino_id, $cliente_id);

                if ($productoDestino) {
                    $productoDestino->cantidad_stock += $producto['cantidad'];
                    $productoDestino->despachado += $producto['cantidad'];
                    $productoDestino->save();
                } else {
                    MaterialEmpleadoTarea::create([
                        'empleado_id' => $empleado_destino_id,
                        'cantidad_stock' => $producto['cantidad'],
                        'detalle_producto_id' => $producto['id'],
                        'despachado' => $producto['cantidad'],
                        'proyecto_id' => $proyecto_destino_id,
                        'etapa_id' => $etapa_destino_id,
                        'cliente_id' => $productoOrigen->cliente_id,
                    ]);
                }

                Log::channel('testing')->info('Log', compact('productoDestino'));
            }
        }
    }
}
