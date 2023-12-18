<?php

namespace App\Http\Controllers\Tareas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tareas\TransferenciaMaterialEmpleadoRequest;
use App\Http\Requests\Tareas\TransferenciaProductoEmpleadoRequest;
use App\Http\Requests\Tareas\TransferirMaterialEmpleadoRequest;
use App\Http\Resources\Tareas\TransferenciaMaterialEmpleadoResource;
use App\Http\Resources\Tareas\TransferenciaProductoEmpleadoResource;
use App\Models\Tareas\TransferenciaProductoEmpleado;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Src\Shared\Utils;

class TransferenciaProductoEmpleadoController extends Controller
{
    private $entidad = 'Transferencia';

    public function index() {
        $results = TransferenciaProductoEmpleado::get();
        return response()->json(compact('results'));
    }

    public function store(TransferenciaProductoEmpleadoRequest $request)
    {
        // Log::channel('testing')->info('Log', ['recibido en el store de devoluciones', $request->all()]);
        // $url = '/devoluciones';
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

            // Respuesta
            $transferencia = TransferenciaProductoEmpleado::create($datos);
            // Log::channel('testing')->info('Log', ['devolucion creada', $devolucion]);
            $modelo = new TransferenciaProductoEmpleadoResource($transferencia);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

            /*foreach ($request->listadoProductos as $listado) {
                $devolucion->detalles()->attach($listado['id'], ['cantidad' => $listado['cantidad']]);
            }*/

            DB::commit();
            // $msg = 'Devolución N°' . $devolucion->id . ' ' . $devolucion->solicitante->nombres . ' ' . $devolucion->solicitante->apellidos . ' ha realizado una devolución en la sucursal ' . $devolucion->sucursal->lugar . ' . La autorización está ' . $devolucion->autorizacion->nombre;
            // event(new DevolucionCreadaEvent($msg, $url, $devolucion, $devolucion->solicitante_id, $devolucion->per_autoriza_id, false));
        } catch (Exception $e) {
            DB::rollBack();
            // Log::channel('testing')->info('Log', ['ERROR del catch', $e->getMessage(), $e->getLine()]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro'], 422);
        }

        return response()->json(compact('mensaje', 'modelo'));
    }
}
