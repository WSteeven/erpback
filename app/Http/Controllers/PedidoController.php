<?php

namespace App\Http\Controllers;

use App\Events\Bodega\PedidoAutorizadoEvent;
use App\Events\Bodega\PedidoCreadoEvent;
use App\Exports\Bodega\PedidoExport;
use App\Helpers\Filtros\FiltroSearchHelper;
use App\Http\Requests\PedidoRequest;
use App\Http\Resources\PedidoResource;
use App\Models\Autorizacion;
use App\Models\ConfiguracionGeneral;
use App\Models\DetallePedidoProducto;
use App\Models\Inventario;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\Sucursal;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Src\App\Bodega\PedidoService;
use Src\App\RegistroTendido\GuardarImagenIndividual;
use Src\App\Sistema\PaginationService;
use Src\Config\Constantes;
use Src\Config\EstadosTransacciones;
use Src\Config\RutasStorage;
use Src\Shared\Utils;
use Throwable;

class PedidoController extends Controller
{
    private string $entidad = 'Pedido';
    private PedidoService $servicio;
    private PaginationService $paginationService;

    public function __construct()
    {
        $this->servicio = new PedidoService();
        $this->paginationService = new PaginationService();
        $this->middleware('can:puede.ver.pedidos')->only('index', 'show');
        $this->middleware('can:puede.crear.pedidos')->only('store');
        $this->middleware('can:puede.editar.pedidos')->only('update');
        $this->middleware('can:puede.eliminar.pedidos')->only('destroy');
    }

    /**
     * Listar
     * @throws ValidationException
     */
    public function index(Request $request)
    {
        $estado = $request->estado;
        $search = $request->search;
        $filtro = ['clave' => 'autorizacion', 'valor' => 'PENDIENTE'];
        try {

            if (auth()->user()->hasRole(User::ROL_ADMINISTRADOR)) {
                $query = $this->servicio->filtrarPedidosAdministrador($estado);
            } else if (auth()->user()->hasRole([User::ROL_BODEGA, User::ROL_AUXILIAR_BODEGA]) && !auth()->user()->hasRole(User::ROL_ACTIVOS_FIJOS)) { //para que unicamente el bodeguero pueda ver las transacciones pendientes
                $query = $this->servicio->filtrarPedidosBodeguero($estado);
            } else if (auth()->user()->hasRole(User::ROL_ACTIVOS_FIJOS)) {
                $query = $this->servicio->filtrarPedidosActivosFijos($estado);
            } else if (auth()->user()->hasRole(User::ROL_BODEGA_TELCONET)) {
                $query = $this->servicio->filtrarPedidosBodegueroTelconet($estado);
            } else {
                $query = $this->servicio->filtrarPedidosEmpleado($estado);
            }
            $filtrosAlgolia = $this->servicio->obtenerFiltrosIndice($estado);

            Log::channel('testing')->info('Log', ['request', $request->all()]);
            Log::channel('testing')->info('Log', ['query',  $query->count()]);

            $results =  buscarConAlgoliaFiltrado(Pedido::class, $query, 'id', $search,  Constantes::PAGINATION_ITEMS_PER_PAGE, request('page'), !!$request->paginate, $filtrosAlgolia);

            return PedidoResource::collection($results);
        } catch (Exception $e) {
            throw Utils::obtenerMensajeErrorLanzable($e);
        }


//        return response()->json(compact('results'));
    }

    /**
     * Guardar
     * @throws Throwable|ValidationException
     */
    public function store(PedidoRequest $request)
    {
        $ids_sucursales_telconet = Sucursal::where('lugar', 'LIKE', '%telconet%')->get('id');
        $url = '/pedidos';
        try {
            DB::beginTransaction();
            // Adaptacion de foreign keys
            $datos = $request->validated();

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
                $es_pedido_telconet = collect($ids_sucursales_telconet)->contains(function ($item) use ($pedido) {
                    return $item->id == $pedido->sucursal_id;
                });
                if ($es_pedido_telconet) event(new PedidoAutorizadoEvent($msg, User::BODEGA_TELCONET, $url, $pedido, true));
                else event(new PedidoAutorizadoEvent($msg, User::ROL_BODEGA, $url, $pedido, true));
            } else {
                $msg = 'Pedido N°' . $pedido->id . ' ' . $pedido->solicitante->nombres . ' ' . $pedido->solicitante->apellidos . ' ha realizado un pedido en la sucursal ' . $pedido->sucursal->lugar . ' y está ' . $pedido->autorizacion->nombre . ' de autorización';
                event(new PedidoCreadoEvent($msg, $url, $pedido, $pedido->solicitante_id, $pedido->per_autoriza_id, false));
            }

            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->error('Log', ['ERROR del catch', $e->getMessage(), $e->getLine()]);
            throw Utils::obtenerMensajeErrorLanzable($e, 'Problema al guardar el Pedido');

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
     * @throws Throwable|ValidationException
     */
    public function update(PedidoRequest $request, Pedido $pedido)
    {
        $ids_sucursales_telconet = Sucursal::where('lugar', 'LIKE', '%telconet%')->get('id');
        $url = '/pedidos';
        try {
            // Adaptacion de foreign keys
            DB::beginTransaction();
            $datos = $request->validated();

            if ($datos['evidencia1'] && Utils::esBase64($datos['evidencia1'])) $datos['evidencia1'] = (new GuardarImagenIndividual($datos['evidencia1'], RutasStorage::PEDIDOS, $pedido->evidencia1))->execute();
            else unset($datos['evidencia1']);

            if ($datos['evidencia2'] && Utils::esBase64($datos['evidencia2'])) $datos['evidencia2'] = (new GuardarImagenIndividual($datos['evidencia2'], RutasStorage::PEDIDOS, $pedido->evidencia2))->execute();
            else unset($datos['evidencia2']);

            // Respuesta
            $pedido->update($datos);
            $modelo = new PedidoResource($pedido->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

            //modifica los datos del listado, en caso de requerirse
            $pedido->detalles()->detach();
            foreach ($request->listadoProductos as $listado) {
                $pedido->detalles()->attach($listado['id'], ['cantidad' => $listado['cantidad'], 'solicitante_id' => array_key_exists('solicitante_id', $listado) ? $listado['solicitante_id'] : $listado['solicitante']]);
                // $pedido->detalles()->attach($listado['id'], ['cantidad' => $listado['cantidad'], 'solicitante_id' => $listado['solicitante_id']]);
            }
            DB::commit();

            if ($pedido->autorizacion->nombre == Autorizacion::APROBADO) {
                //Metodo para verificar si el detalle existe en alguna bodega de propiedad del cliente de la bodega
                Inventario::verificarExistenciasDetalles($pedido);
            }


            if ($pedido->autorizacion->nombre === Autorizacion::APROBADO) {
                $pedido->latestNotificacion()->update(['leida' => true]);
                $msg = 'Hay un pedido recién autorizado en la sucursal ' . $pedido->sucursal->lugar . ' pendiente de despacho';
                $es_pedido_telconet = collect($ids_sucursales_telconet)->contains(function ($item) use ($pedido) {
                    return $item->id == $pedido->sucursal_id;
                });
                if ($es_pedido_telconet) event(new PedidoAutorizadoEvent($msg, User::BODEGA_TELCONET, $url, $pedido, true));
                else event(new PedidoAutorizadoEvent($msg, User::ROL_BODEGA, $url, $pedido, true));
            }

            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw Utils::obtenerMensajeErrorLanzable($e, 'Problema al actualizar el Pedido');
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
        $modelo = new PedidoResource($pedido);

        return response()->json(compact('modelo'));
    }

    /**
     * La función "corregirPedido" toma una solicitud y un pedido, actualiza la cantidad de productos
     * del pedido, guarda los cambios y devuelve el pedido modificado como respuesta JSON.
     *
     * @param Request $request El parámetro `request` es una instancia de la clase Request, que
     * representa una solicitud HTTP. Contiene información sobre la solicitud, como el método de
     * solicitud, los encabezados y los datos de entrada.
     * @param Pedido $pedido El parámetro "pedido" es una instancia del modelo "Pedido". Representa un
     * pedido específico en el sistema.
     *
     * @return JsonResponse respuesta JSON con el pedido modificado como objeto PedidoResource.
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
        return response()->json(compact('modelo'));
    }

    /**
     * La función `eliminarDetallePedido` elimina un artículo específico de un pedido y devuelve un
     * mensaje de éxito.
     *
     * @param Request $request El parámetro `request` es una instancia de la clase Request, que se
     * utiliza para recuperar los datos enviados en la solicitud HTTP. Contiene información como el
     * método de solicitud, los encabezados y cualquier dato enviado en el cuerpo de la solicitud. En
     * este caso, se utiliza para recuperar los valores del 'pedido'
     *
     * @return JsonResponse respuesta JSON que contiene el mensaje "El elemento ha sido eliminado con éxito".
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
            //SE GENERA Y RETORNA EL PDF
            return $pdf->output();
        } catch (Exception $ex) {
            Log::channel('testing')->error('Log', ['ERROR', $ex->getMessage(), $ex->getLine()]);
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
        $pedido->estado_id = EstadosTransacciones::ANULADA;
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

    /**
     * @throws ValidationException
     */
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
                case 'pdf':
                    try {
                        $vista = 'pedidos.pedidos';
                        $reporte = $registros;
                        $pdf = Pdf::loadView($vista, compact(['reporte', 'configuracion']));
                        $pdf->setPaper('A4', 'landscape');
                        $pdf->render();
                        return $pdf->stream();
                    } catch (Exception $ex) {
                        Log::channel('testing')->error('Log', ['ERROR', $ex->getMessage(), $ex->getLine()]);
                        throw ValidationException::withMessages([
                            'Error al generar reporte' => [$ex->getMessage()],
                        ]);
                    }
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

    /**
     * @throws Exception
     */
    public function encabezado()
    {
        $pdf = Pdf::loadView('pedidos.encabezado_pie_numeracion');
        $pdf->setPaper('A4');
        $pdf->render();
        // $pdf->output();
        // $pdf->stream();

        return $pdf->stream();
        // return view('pedidos.encabezado_pie_numeracion');
    }

    /**
     * @throws Exception
     */
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
        // $results = $producto->audits()->with('user')->get(); //obtiene el usuario que hizo el evento
        $results = $producto->audits()->latest()->first()->getModified(); //obtiene las propiedades modificadas del registro afectado
        return response()->json(compact('results'));
    }
}
