<?php

namespace App\Http\Controllers\Tareas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tareas\TransferenciaMaterialEmpleadoRequest;
use App\Http\Requests\Tareas\TransferenciaProductoEmpleadoRequest;
use App\Http\Requests\Tareas\TransferirMaterialEmpleadoRequest;
use App\Http\Resources\Tareas\TransferenciaMaterialEmpleadoResource;
use App\Http\Resources\Tareas\TransferenciaProductoEmpleadoResource;
use App\Models\Autorizacion;
use App\Models\Tareas\TransferenciaProductoEmpleado;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Src\App\Tareas\TransferenciaProductoEmpleadoService;
use Src\Config\Autorizaciones;
use Src\Shared\Utils;

class TransferenciaProductoEmpleadoController extends Controller
{
    private $entidad = 'Transferencia';
    private $transferenciaService;

    public function __construct()
    {
        $this->transferenciaService = new TransferenciaProductoEmpleadoService();
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
            $datos['tarea_origen_id'] = $request['tarea_origen'];
            $datos['tarea_destino_id'] = $request['tarea_destino'];
            $datos['autorizador_id'] = $request['autorizador'];
            $datos['autorizacion_id'] = $request['autorizacion'];

            $transferencia = TransferenciaProductoEmpleado::create($datos);

            foreach ($request->listado_productos as $listado) {
                $transferencia->detallesTransferenciaProductoEmpleado()->attach($listado['id'], ['cantidad' => $listado['cantidad']]);
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
        $datos['tarea_origen_id'] = $request['tarea_origen'];
        $datos['tarea_destino_id'] = $request['tarea_destino'];
        $datos['autorizacion_id'] = $request['autorizacion'];
        $datos['autorizador_id'] = $request['autorizador'];

        $transferencia_producto_empleado->update($datos);

        //borrar los registros de la tabla intermedia para guardar los modificados
        $transferencia_producto_empleado->detallesTransferenciaProductoEmpleado()->detach();

        //Guardar los productos seleccionados
        foreach ($request->listado_productos as $listado) {
            $transferencia_producto_empleado->detallesTransferenciaProductoEmpleado()->attach($listado['id'], ['cantidad' => $listado['cantidad']]);
        }

        $modelo = new TransferenciaProductoEmpleadoResource($transferencia_producto_empleado->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        // $msg = $transferencia_producto_empleado->autoriza->nombres . ' ' . $transferencia_producto_empleado->autoriza->apellidos . ' ha actualizado tu devolución, el estado de Autorización es: ' . $transferencia_producto_empleado->autorizacion->nombre;
        // event(new DevolucionActualizadaSolicitanteEvent($msg, $url, $devolucion, $devolucion->per_autoriza_id, $devolucion->solicitante_id, true)); //Se usa para notificar al tecnico que se actualizó la devolucion

        /* if ($transferencia_producto_empleado->autorizacion->nombre === Autorizacion::APROBADO) {
            $transferencia_producto_empleado->latestNotificacion()->update(['leida' => true]);
            $msg = 'Hay una devolución recién autorizada en la sucursal ' . $devolucion->sucursal->lugar . ' pendiente de despacho';
            // event(new DevolucionAutorizadaEvent($msg, User::ROL_BODEGA, $url, $devolucion, true));
        } */
        return response()->json(compact('mensaje', 'modelo'));
    }
}
