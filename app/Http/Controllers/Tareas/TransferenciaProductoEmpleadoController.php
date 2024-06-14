<?php

namespace App\Http\Controllers\Tareas;

use App\Events\Tareas\NotificarTransferenciaProductosRealizadaEvent;
use App\Http\Resources\Tareas\TransferenciaProductoEmpleadoResource;
use App\Http\Requests\Tareas\TransferenciaProductoEmpleadoRequest;
use Src\App\Tareas\TransferenciaProductoEmpleadoService;
use App\Models\Tareas\TransferenciaProductoEmpleado;
use Src\App\Tareas\ProductoTareaEmpleadoService;
use App\Http\Controllers\Controller;
use App\Models\Autorizacion;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Src\Shared\Utils;
use Exception;
use Illuminate\Validation\ValidationException;
use Src\App\ArchivoService;
use Src\Config\RutasStorage;

class TransferenciaProductoEmpleadoController extends Controller
{
    private $entidad = 'Transferencia';
    private $transferenciaService;
    private $productosTareaEmpleadoService;
    private $archivoService;

    public function __construct()
    {
        $this->transferenciaService = new TransferenciaProductoEmpleadoService();
        $this->productosTareaEmpleadoService = new ProductoTareaEmpleadoService();
        $this->archivoService = new ArchivoService();
    }

    public function index(Request $request)
    {
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
            $cliente_id = $request['cliente'];

            $transferencia = TransferenciaProductoEmpleado::create($datos);

            foreach ($request->listado_productos as $listado) {
                $transferencia->detallesTransferenciaProductoEmpleado()->attach($listado['id'], ['cantidad' => $listado['cantidad']]); //, 'cliente_id' => $cliente_id]);
            }

            $modelo = new TransferenciaProductoEmpleadoResource($transferencia);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

            DB::commit();
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
        try {
            DB::beginTransaction();

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

                $esTransferenciaDeStock = !$datos['proyecto_origen_id'] && !$datos['etapa_origen_id'] && !$datos['tarea_origen_id'];
                Log::channel('testing')->info('Log', compact('esTransferenciaDeStock'));

                $this->transferenciaService->ajustarValoresProducto($transferencia_producto_empleado, $esTransferenciaDeStock);

                $emisor_id = $transferencia_producto_empleado->empleado_origen_id;
                $destinatario_id = $transferencia_producto_empleado->empleado_destino_id;

                event(new NotificarTransferenciaProductosRealizadaEvent($transferencia_producto_empleado));
            }

            $modelo = new TransferenciaProductoEmpleadoResource($transferencia_producto_empleado->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages(['error' => $e->getMessage()]);
        }

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

    /**
     * Listar archivos
     */
    public function indexFiles(Request $request, TransferenciaProductoEmpleado $transferencia)
    {
        try {
            $results = $this->archivoService->listarArchivos($transferencia);
        } catch (\Throwable $th) {
            return $th;
        }
        return response()->json(compact('results'));
    }

    /**
     * Guardar archivos
     */
    public function storeFiles(Request $request, TransferenciaProductoEmpleado $transferencia)
    {
        try {
            $modelo  = $this->archivoService->guardarArchivo($transferencia, $request->file, RutasStorage::TRANSFERENCIAS_PRODUCTOS_EMPLEADOS->value . '_' . $transferencia->id);
            $mensaje = 'Archivo subido correctamente';
        } catch (Exception $ex) {
            return $ex;
        }
        return response()->json(compact('mensaje', 'modelo'), 200);
    }
}
