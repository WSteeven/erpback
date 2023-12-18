<?php

namespace App\Http\Controllers;

use App\Events\PedidoAutorizadoEvent;
use App\Events\PedidoCreadoEvent;
use App\Exports\Bodega\PedidoExport;
use App\Http\Requests\PedidoRequest;
use App\Http\Resources\PedidoResource;
use App\Models\Autorizacion;
use App\Models\ConfiguracionGeneral;
use App\Models\DetallePedidoProducto;
use App\Models\EstadoTransaccion;
use App\Models\Inventario;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\Sucursal;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Src\App\Bodega\PedidoService;
use Src\App\RegistroTendido\GuardarImagenIndividual;
use Src\Config\EstadosTransacciones;
use Src\Config\RutasStorage;
use Src\Shared\Utils;

class PedidoController extends Controller
{
    private $entidad = 'Pedido';
    private $servicio;
    public function __construct()
    {
        $this->servicio = new PedidoService();
        $this->middleware('can:puede.ver.pedidos')->only('index', 'show');
        $this->middleware('can:puede.crear.pedidos')->only('store');
        $this->middleware('can:puede.editar.pedidos')->only('update');
        $this->middleware('can:puede.eliminar.pedidos')->only('destroy');
    }

    /**
     * Listar
     */
    public function index(Request $request)
    {
        $estado = $request['estado'];
        $results = [];

        if (auth()->user()->hasRole(User::ROL_ADMINISTRADOR)) {
            $results = $this->servicio->filtrarPedidosAdministrador($estado);
        } else if (auth()->user()->hasRole(User::ROL_BODEGA) && !auth()->user()->hasRole(User::ROL_ACTIVOS_FIJOS)) { //para que unicamente el bodeguero pueda ver las transacciones pendientes
            // Log::channel('testing')->info('Log', ['Es bodeguero:', $estado]);
            $results = $this->servicio->filtrarPedidosBodeguero($estado);
        } else if (auth()->user()->hasRole(User::ROL_ACTIVOS_FIJOS)) {
            $results = $this->servicio->filtrarPedidosActivosFijos($estado);
        } else if (auth()->user()->hasRole(User::ROL_BODEGA_TELCONET)) {
            $results = $this->servicio->filtrarPedidosBodegueroTelconet($estado);
        } else {
            // Log::channel('testing')->info('Log', ['Es empleado:', $estado]);
            $results = $this->servicio->filtrarPedidosEmpleado($estado);
        }


        // Log::channel('testing')->info('Log', ['Resultados:', $estado, $results]);
        if (!empty($results)) {
            $results = PedidoResource::collection($results);
        } else {
            $results = [];
        }
        return response()->json(compact('results'));
    }

    /**
     * Guardar
     */
    public function store(PedidoRequest $request)
    {
        $idsSucursalesTelconet = Sucursal::where('lugar', 'LIKE', '%telconet%')->get('id');
        $url = '/pedidos';
        // Log::channel('testing')->info('Log', ['Request recibida en pedido:', $request->all()]);
        try {
            DB::beginTransaction();
            // Adaptacion de foreign keys
            $datos = $request->validated();
            $datos['solicitante_id'] = $request->safe()->only(['solicitante'])['solicitante'];
            $datos['responsable_id'] = $request->safe()->only(['responsable'])['responsable'];
            $datos['autorizacion_id'] = $request->safe()->only(['autorizacion'])['autorizacion'];
            $datos['per_autoriza_id'] = $request->safe()->only(['per_autoriza'])['per_autoriza'];
            $datos['per_retira_id'] = $request->safe()->only(['per_retira'])['per_retira'];
            $datos['sucursal_id'] = $request->safe()->only(['sucursal'])['sucursal'];
            $datos['estado_id'] = $request->safe()->only(['estado'])['estado'];
            $datos['cliente_id'] = $request->safe()->only(['cliente'])['cliente'];
            if ($request->proyecto) $datos['proyecto_id'] = $request->safe()->only(['proyecto'])['proyecto'];
            if ($request->etapa) $datos['etapa_id'] = $request->safe()->only(['etapa'])['etapa'];
            if ($request->tarea) $datos['tarea_id'] = $request->safe()->only(['tarea'])['tarea'];

            if ($datos['evidencia1']) $datos['evidencia1'] = (new GuardarImagenIndividual($datos['evidencia1'], RutasStorage::PEDIDOS))->execute();
            if ($datos['evidencia2']) $datos['evidencia2'] = (new GuardarImagenIndividual($datos['evidencia2'], RutasStorage::PEDIDOS))->execute();


            // Respuesta
            $pedido = Pedido::create($datos);
            $modelo = new PedidoResource($pedido);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

            foreach ($request->listadoProductos as $listado) {
                $pedido->detalles()->attach($listado['id'], ['cantidad' => $listado['cantidad'], 'solicitante_id' => $listado['solicitante']]);
            }
            DB::commit();

            if ($pedido->autorizacion->nombre == Autorizacion::APROBADO) {
                //Metodo para verificar si el detalle existe en alguna bodega de propiedad del cliente de la bodega
                Inventario::verificarExistenciasDetalles($pedido);
            }

            /* Sending a notification to the user who autorized the order. */
            //logica para los eventos de las notificaciones
            if ($pedido->solicitante_id == $pedido->per_autoriza_id && $pedido->autorizacion->nombre === Autorizacion::APROBADO) {
                //No se hace nada y se crea la logica
                $msg = 'Pedido N°' . $pedido->id . ' ' . $pedido->solicitante->nombres . ' ' . $pedido->solicitante->apellidos . ' ha realizado un pedido en la sucursal ' . $pedido->sucursal->lugar . ' indicando que tú eres el responsable de los materiales, el estado del pedido es ' . $pedido->autorizacion->nombre;
                event(new PedidoCreadoEvent($msg, $url, $pedido, $pedido->solicitante_id, $pedido->responsable_id, false));
                $msg = 'Hay un pedido recién autorizado en la sucursal ' . $pedido->sucursal->lugar . ' pendiente de despacho';
                $esPedidoTelconet = collect($idsSucursalesTelconet)->contains(function ($item) use ($pedido) {
                    return $item->id == $pedido->sucursal_id;
                });
                if ($esPedidoTelconet) event(new PedidoAutorizadoEvent($msg, User::BODEGA_TELCONET, $url, $pedido, true));
                else event(new PedidoAutorizadoEvent($msg, User::ROL_BODEGA, $url, $pedido, true));
            } else {
                $msg = 'Pedido N°' . $pedido->id . ' ' . $pedido->solicitante->nombres . ' ' . $pedido->solicitante->apellidos . ' ha realizado un pedido en la sucursal ' . $pedido->sucursal->lugar . ' y está ' . $pedido->autorizacion->nombre . ' de autorización';
                event(new PedidoCreadoEvent($msg, $url,  $pedido, $pedido->solicitante_id, $pedido->per_autoriza_id, false));
            }

            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['ERROR del catch', $e->getMessage(), $e->getLine()]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro'], 422);
        }
    }

    /**
     * Consultar
     */
    public function show(Pedido $pedido)
    {
        $modelo = new PedidoResource($pedido);
        return response()->json(compact('modelo'));
    }

    /**
     * Actualizar
     */
    public function update(PedidoRequest $request, Pedido $pedido)
    {
        $idsSucursalesTelconet = Sucursal::where('lugar', 'LIKE', '%telconet%')->get('id');
        $url = '/pedidos';
        // Log::channel('testing')->info('Log', ['entro en el update del pedido',$idsSucursalesTelconet]);
        try {
            DB::beginTransaction();
            // Adaptacion de foreign keys
            $datos = $request->validated();
            $datos['solicitante_id'] = $request->safe()->only(['solicitante'])['solicitante'];
            $datos['responsable_id'] = $request->safe()->only(['responsable'])['responsable'];
            $datos['autorizacion_id'] = $request->safe()->only(['autorizacion'])['autorizacion'];
            $datos['per_autoriza_id'] = $request->safe()->only(['per_autoriza'])['per_autoriza'];
            $datos['per_retira_id'] = $request->safe()->only(['per_retira'])['per_retira'];
            $datos['sucursal_id'] = $request->safe()->only(['sucursal'])['sucursal'];
            $datos['estado_id'] = $request->safe()->only(['estado'])['estado'];
            $datos['cliente_id'] = $request->safe()->only(['cliente'])['cliente'];
            if ($request->proyecto) $datos['proyecto_id'] = $request->safe()->only(['proyecto'])['proyecto'];
            if ($request->etapa) $datos['etapa_id'] = $request->safe()->only(['etapa'])['etapa'];
            if ($request->tarea) $datos['tarea_id'] = $request->safe()->only(['tarea'])['tarea'];

            if ($datos['evidencia1'] && Utils::esBase64($datos['evidencia1'])) $datos['evidencia1'] = (new GuardarImagenIndividual($datos['evidencia1'], RutasStorage::PEDIDOS))->execute();
            else unset($datos['evidencia1']);

            if ($datos['evidencia2'] && Utils::esBase64($datos['evidencia2'])) $datos['evidencia2'] = (new GuardarImagenIndividual($datos['evidencia2'], RutasStorage::PEDIDOS))->execute();
            else unset($datos['evidencia2']);

            // Respuesta
            $pedido->update($datos);
            $modelo = new PedidoResource($pedido->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

            //modifica los datos del listado, en caso de requerirse
            $pedido->detalles()->detach();
            foreach ($request->listadoProductos as $listado) {
                $pedido->detalles()->attach($listado['id'], ['cantidad' => $listado['cantidad'], 'solicitante_id' => $listado['solicitante_id']]);
            }
            DB::commit();

            if ($pedido->autorizacion->nombre == Autorizacion::APROBADO) {
                //Metodo para verificar si el detalle existe en alguna bodega de propiedad del cliente de la bodega
                Inventario::verificarExistenciasDetalles($pedido);
            }


            // Log::channel('testing')->info('Log', ['antes de verificar si se aprobó', $pedido]);
            // Log::channel('testing')->info('Log', ['Verificar las notificaciones', $pedido->latestNotificacion()]);
            if ($pedido->autorizacion->nombre === Autorizacion::APROBADO) {
                $pedido->latestNotificacion()->update(['leida' => true]);
                $msg = 'Hay un pedido recién autorizado en la sucursal ' . $pedido->sucursal->lugar . ' pendiente de despacho';
                $esPedidoTelconet = collect($idsSucursalesTelconet)->contains(function ($item) use ($pedido) {
                    return $item->id == $pedido->sucursal_id;
                });
                if ($esPedidoTelconet) event(new PedidoAutorizadoEvent($msg, User::BODEGA_TELCONET, $url, $pedido, true));
                else event(new PedidoAutorizadoEvent($msg, User::ROL_BODEGA, $url, $pedido, true));
            }

            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['mensaje' => 'Ha ocurrido un error al actualizar el registro. ' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    /**
     * Eliminar
     */
    public function destroy(Pedido $pedido)
    {
        $pedido->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }


    /**
     * Consultar datos sin el método show.
     */
    public function showPreview(Pedido $pedido)
    {
        // Log::channel('testing')->info('Log', ['El pedido consultado es: ', $pedido]);
        $modelo = new PedidoResource($pedido);

        return response()->json(compact('modelo'), 200);
    }

    /**
     * La función "corregirPedido" toma una solicitud y un pedido, actualiza la cantidad de productos
     * del pedido, guarda los cambios y devuelve el pedido modificado como respuesta JSON.
     *
     * @param Request request El parámetro  es una instancia de la clase Request, que
     * representa una solicitud HTTP. Contiene información sobre la solicitud, como el método de
     * solicitud, los encabezados y los datos de entrada.
     * @param Pedido pedido El parámetro "" es una instancia del modelo "Pedido". Representa un
     * pedido específico en el sistema.
     *
     * @return una respuesta JSON con el pedido modificado como objeto PedidoResource.
     */
    public function corregirPedido(Request $request, Pedido $pedido)
    {
        //aqui se hace todo un proceso y se devuelve el pedido ya modificado
        if (count($request->listadoProductos) > 0) {
            foreach ($request->listadoProductos as $listado) {
                $pedido->detalles()->updateExistingPivot($listado['id'], ['cantidad' => $listado['cantidad']]);
                $detalle = DetallePedidoProducto::where('pedido_id', $pedido->id)->where('detalle_id', $listado['id'])->first();
                $detalle->cantidad = $listado['cantidad'];
                $detalle->save();
                DetallePedidoProducto::verificarDespachoItems($detalle);
            }
        }

        $modelo = new PedidoResource($pedido);
        return response()->json(compact('modelo'), 200);
    }

    /**
     * La función `eliminarDetallePedido` elimina un artículo específico de un pedido y devuelve un
     * mensaje de éxito.
     *
     * @param Request request El parámetro  es una instancia de la clase Request, que se
     * utiliza para recuperar los datos enviados en la solicitud HTTP. Contiene información como el
     * método de solicitud, los encabezados y cualquier dato enviado en el cuerpo de la solicitud. En
     * este caso, se utiliza para recuperar los valores del 'pedido
     *
     * @return una respuesta JSON que contiene el mensaje "El elemento ha sido eliminado con éxito" (El
     * elemento se ha eliminado con éxito).
     */
    public function eliminarDetallePedido(Request $request)
    {
        $detalle = DetallePedidoProducto::where('pedido_id', $request->pedido_id)->where('detalle_id', $request->detalle_id)->first();
        $detalle->delete();
        $mensaje = 'El item ha sido eliminado con éxito';
        return response()->json(compact('mensaje'));
    }
    /**
     * Imprimir
     */
    public function imprimir(Pedido $pedido)
    {
        $configuracion = ConfiguracionGeneral::first();
        $resource = new PedidoResource($pedido);
        try {
            $pdf = Pdf::loadView('pedidos.pedido', ['pedido' => $resource->resolve(), 'configuracion' => $configuracion]);
            $pdf->setPaper('A5', 'landscape');
            $pdf->setOption(['isRemoteEnabled' => true]);
            $pdf->render();
            $file = $pdf->output(); //SE GENERA EL PDF
            $filename = "pedido_" . $resource->id . "_" . time() . ".pdf";

            $ruta = storage_path() . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'pedidos' . DIRECTORY_SEPARATOR . $filename;

            // $filename = storage_path('public\\pedidos\\').'Pedido_'.$resource->id.'_'.time().'.pdf';
            // Log::channel('testing')->info('Log', ['El pedido es', $resource, $configuracion]);
            // file_put_contents($ruta, $file); en caso de que se quiera guardar el documento en el backend
            return $file;
        } catch (Exception $ex) {
            Log::channel('testing')->info('Log', ['ERROR', $ex->getMessage(), $ex->getLine()]);
            $mensaje = $ex->getMessage() . '. ' . $ex->getLine();
            return response()->json(compact('mensaje'));
        }
    }

    public function mostrar(Pedido $pedido)
    {
        $resource = new PedidoResource($pedido);

        return view('pedidos.pedido', [$resource->resolve(), 'usuario' => auth()->user()->empleado]);
    }

    public function anular(Request $request, Pedido $pedido)
    {
        $autorizacion = Autorizacion::where('nombre', Autorizacion::CANCELADO)->first();
        $request->validate(['motivo' => ['required', 'string']]);
        $pedido->causa_anulacion = $request['motivo'];
        $pedido->autorizacion_id = $autorizacion->id;
        $pedido->save();

        $modelo = new PedidoResource($pedido->refresh());
        return response()->json(compact('modelo'));
    }
    public function marcarCompletado(Request $request, Pedido $pedido)
    {
        $request->validate(['motivo' => ['required', 'string']]);
        $pedido->observacion_bodega = $request['motivo'];
        $pedido->estado_id = EstadosTransacciones::COMPLETA;
        $pedido->save();

        $modelo = new PedidoResource($pedido->refresh());
        return response()->json(compact('modelo'));
    }

    public function reportes(Request $request)
    {
        try {
            $configuracion = ConfiguracionGeneral::first();
            $estadisticas = [];
            $results = $this->servicio->filtrarPedidosReporte($request);
            $registros = $this->servicio->empaquetarDatos($results);
            switch ($request->accion) {
                case 'excel':
                    return Excel::download(new PedidoExport(collect($registros), $configuracion), 'reporte_pedidos.xlsx');
                    break;
                case 'pdf':
                    try {
                        $vista = 'pedidos.pedidos';
                        $reporte = $registros;
                        $pdf = Pdf::loadView($vista, compact(['reporte', 'configuracion']));
                        $pdf->setPaper('A4', 'landscape');
                        $pdf->render();
                        return $pdf->stream();
                    } catch (Exception $ex) {
                        Log::channel('testing')->info('Log', ['ERROR', $ex->getMessage(), $ex->getLine()]);
                        throw ValidationException::withMessages([
                            'Error al generar reporte' => [$ex->getMessage()],
                        ]);
                    }
                    break;
                default:
                    break;
            }
        } catch (Exception $ex) {
            throw ValidationException::withMessages([
                'Error' => [$ex->getMessage() . '. ' . $ex->getLine()],
            ]);
        }

        $results = PedidoResource::collection($results);
        return response()->json(compact('results', 'estadisticas'));
    }

    //retorna un qr
    public function qrview()
    {
        return view('qrcode');
    }

    public function encabezado()
    {
        $pdf = Pdf::loadView('pedidos.encabezado_pie_numeracion');
        $pdf->setPaper('A4', 'portrait');
        $pdf->render();
        // $pdf->output();
        // $pdf->stream();

        return $pdf->stream();
        // return view('pedidos.encabezado_pie_numeracion');
    }
    public function example()
    {
        $pdf = new Pdf();
        $pdf = Pdf::loadView('pedidos.example', compact('pdf'));
        $pdf->render();
        return $pdf->stream();
    }

    public function auditoria()
    {
        $producto = Producto::find(1);
        // $results = $producto->audits; //obtiene todos los eventos de un registro
        // $results = $producto->audits()->with('user')->get(); //obtiene el usuario que hizo la evento
        $results = $producto->audits()->latest()->first()->getMetadata(); //obtiene los metadatos de un evento
        $results = $producto->audits()->latest()->first()->getModified(); //obtiene las propiedades modificadas del registro afectado
        return response()->json(compact('results'));
    }
}
