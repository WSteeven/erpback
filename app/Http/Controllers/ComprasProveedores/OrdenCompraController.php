<?php

namespace App\Http\Controllers\ComprasProveedores;

use App\Events\ComprasProveedores\NotificarOrdenCompraCompras;
use App\Events\ComprasProveedores\NotificarOrdenCompraPagadaCompras;
use App\Events\ComprasProveedores\NotificarOrdenCompraPagadaUsuario;
use App\Events\ComprasProveedores\NotificarOrdenCompraRealizada;
use App\Events\ComprasProveedores\OrdenCompraActualizadaEvent;
use App\Events\ComprasProveedores\OrdenCompraCreadaEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\ComprasProveedores\OrdenCompraRequest;
use App\Http\Resources\ComprasProveedores\OrdenCompraResource;
use App\Mail\ComprasProveedores\EnviarMailOrdenCompraProveedor;
use App\Models\Autorizacion;
use App\Models\ComprasProveedores\OrdenCompra;
use App\Models\ComprasProveedores\PreordenCompra;
use App\Models\CorreoEnviado;
use App\Models\EstadoTransaccion;
use App\Models\User;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Src\App\ArchivoService;
use Src\App\ComprasProveedores\OrdenCompraService;
use Src\Config\Autorizaciones;
use Src\Config\EstadosTransacciones;
use Src\Config\RutasStorage;
use Src\Shared\Utils;
use Throwable;

class OrdenCompraController extends Controller
{
    private $entidad = 'Orden de compra';
    private OrdenCompraService $servicio;
    private $archivoService;

    public function __construct()
    {
        $this->servicio = new OrdenCompraService();
        $this->archivoService = new ArchivoService();
        $this->middleware('can:puede.ver.ordenes_compras')->only('index', 'show');
        $this->middleware('can:puede.crear.ordenes_compras')->only('store');
        $this->middleware('can:puede.editar.ordenes_compras')->only('update');
        $this->middleware('can:puede.eliminar.ordenes_compras')->only('destroy');
    }

    /**
     * Listar
     */
    public function index(Request $request)
    {
        // Log::channel('testing')->info('Log', ['Es empleado:', $request->all()]);
        if (auth()->user()->hasRole([User::ROL_ADMINISTRADOR, User::ROL_COMPRAS, User::ROL_CONTABILIDAD])) {
            $results = OrdenCompra::ignoreRequest(['solicitante_id', 'autorizador_id'])->filter()->orderBy('id', 'desc')->get();
        } else {
            $results = OrdenCompra::filtrarOrdenesEmpleado($request);
            // Log::channel('testing')->info('Log', ['Esta en el else:']);
            // $results = OrdenCompra::filter()->get();
        }
        $results = OrdenCompraResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Guardar
     */
    public function store(OrdenCompraRequest $request)
    {
        $autorizacion_pendiente = Autorizacion::where('nombre', Autorizacion::PENDIENTE)->first();
        $estado_pendiente = EstadoTransaccion::where('nombre', EstadoTransaccion::PENDIENTE)->first();

        try {
            DB::beginTransaction();
            //Adaptacion de foreign keys
            $datos = $request->validated();

            //Creación de la orden de compra
            $orden  = $this->servicio->crearOrdenCompra($datos, $request->listadoProductos);
            // $orden = OrdenCompra::create($datos);
            // Guardar los detalles de la orden de compra
            // OrdenCompra::guardarDetalles($orden, $request->listadoProductos, 'crear');


            //Respuesta
            $modelo = new OrdenCompraResource($orden);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');


            DB::commit();

            // aqui se debe lanzar la notificacion en caso de que la orden de compra sea autorizacion pendiente
            if ($orden->estado_id === $estado_pendiente->id && $orden->autorizacion_id === $autorizacion_pendiente->id) {
                event(new OrdenCompraCreadaEvent($orden, true)); // crea el evento de la orden de compra al autorizador
            }

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
    public function show(OrdenCompra $orden)
    {
        $modelo = new OrdenCompraResource($orden);
        return response()->json(compact('modelo'));
    }

    /**
     * Actualizar
     */
    public function update(OrdenCompraRequest $request, OrdenCompra $orden)
    {
        $autorizacion_anterior = $orden->autorizacion_id;
        $autorizacion_aprobada = Autorizacion::where('nombre', Autorizacion::APROBADO)->first();
        $estado_completo = EstadoTransaccion::where('nombre', EstadoTransaccion::COMPLETA)->first();
        try {
            DB::beginTransaction();
            //Adaptacion de foreign keys
            $datos = $request->validated();
            $datos['solicitante_id'] = $request->safe()->only(['solicitante'])['solicitante'];
            $datos['proveedor_id'] = $request->safe()->only(['proveedor'])['proveedor'];
            $datos['autorizador_id'] = $request->safe()->only(['autorizador'])['autorizador'];
            $datos['autorizacion_id'] = $request->safe()->only(['autorizacion'])['autorizacion'];
            $datos['estado_id'] = $request->safe()->only(['estado'])['estado'];
            if ($request->preorden) $datos['preorden_id'] = $request->safe()->only(['preorden'])['preorden'];
            if ($request->pedido) $datos['pedido_id'] = $request->safe()->only(['pedido'])['pedido'];
            if ($request->tarea) $datos['tarea_id'] = $request->safe()->only(['tarea'])['tarea'];

            // Log::channel('testing')->info('Log', ['Datos sin validar:', $request->all()]);
            // Log::channel('testing')->info('Log', ['Datos validados:', $datos]);
            // if()if (count($request->categorias) == 0) {
            //     unset($datos['categorias']);
            // } else {
            //     $datos['categorias'] = implode(',', $request->categorias);
            // }
            //Creación de la orden de compra
            $orden->update($datos);
            // Sincronizar los detalles de la orden de compra
            OrdenCompra::guardarDetalles($orden, $request->listadoProductos, 'actualizar');

            //Respuesta
            $modelo = new OrdenCompraResource($orden);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');


            DB::commit();

            // aqui se debe lanzar la notificacion en caso de que la orden de compra sea autorizacion pendiente
            // if ($orden->estado_id === $estado_completo->id && $orden->autorizacion_id === $autorizacion_aprobada->id) {
            $orden->latestNotificacion()->update(['leida' => true]); //marcando como leída la notificacion en caso de que esté vigente
            if ($orden->autorizacion_id != $autorizacion_anterior)
                event(new OrdenCompraActualizadaEvent($orden, true)); // crea el evento de la orden de compra actualizada al solicitante
            // }
            if ($orden->autorizacion_id == Autorizaciones::APROBADO) {
                if (!auth()->user()->hasRole(User::ROL_COMPRAS))
                    event(new NotificarOrdenCompraCompras($orden, User::ROL_COMPRAS));
            }

            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['ERROR store de ordenes de compras:', $e->getMessage(), $e->getLine()]);
            return response()->json(['ERROR' => $e->getMessage() . ', ' . $e->getLine()], 422);
        }
    }

    /**
     * Anular una orden de compra
     */
    public function anular(Request $request, OrdenCompra $orden)
    {
        // Log::channel('testing')->info('Log', ['Datos para anuylar:', $request->all()]);
        // $autorizacion = Autorizacion::where('nombre', Autorizacion::CANCELADO)->first();
        $estado = EstadoTransaccion::where('nombre', EstadoTransaccion::ANULADA)->first();
        $request->validate(['motivo' => ['required', 'string']]);
        $orden->causa_anulacion = $request['motivo'];
        // $orden->autorizacion_id = $autorizacion->id;
        $orden->estado_id = $estado->id;
        if ($orden->preorden_id) {
            $preorden = PreordenCompra::find($orden->preorden_id);
            $preorden->estado = EstadoTransaccion::PENDIENTE;
            $preorden->save();
        }
        $orden->latestNotificacion()->update(['leida' => true]); //marcando como leída la notificacion en caso de que esté vigente
        $orden->save();

        event(new OrdenCompraActualizadaEvent($orden, true));

        $modelo = new OrdenCompraResource($orden->refresh());
        return response()->json(compact('modelo'));
    }

    public function realizada(Request $request, OrdenCompra $orden)
    {
        // Log::channel('testing')->info('Log', ['Datos para marcar como realizada la orden de compra:', $request->all()]);
        $orden->realizada = true;
        $request->validate(['observacion_realizada' => ['string', 'nullable']]);
        $orden->observacion_realizada = $request->observacion_realizada;
        $orden->save();
        $orden->latestNotificacion()->update(['leida' => true]);
        event(new NotificarOrdenCompraRealizada($orden, User::ROL_CONTABILIDAD));
        $modelo = new OrdenCompraResource($orden->refresh());
        return response()->json(compact('modelo'));
    }
    public function pagada(OrdenCompra $orden)
    {
        $orden->pagada = true;
        $orden->save();
        $orden->latestNotificacion()->update(['leida' => true]);
        event(new NotificarOrdenCompraPagadaCompras($orden, User::ROL_COMPRAS));
        event(new NotificarOrdenCompraPagadaUsuario($orden));
        $modelo = new OrdenCompraResource($orden->refresh());
        return response()->json(compact('modelo'));
    }

    /**
     * Imprimir una orden de compra
     */
    public function imprimir(OrdenCompra $orden)
    {
        $orden_compra = $orden;
        try {
           return $this->servicio->generarPdf($orden, true, true);
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['ERROR en el try-catch global del metodo imprimir de OrdenCompraController', $e->getMessage(), $e->getLine()]);
            throw ValidationException::withMessages(
                ['error' => $e->getMessage(),
                'Por si acaso'=>'Error duplicado',
            ]);
            $mensaje = $e->getMessage() . '. ' . $e->getLine();
            return response()->json(compact('mensaje'));
        }
    }

    /**
     * Enviar mail al proveedor
     */
    public function sendMail(OrdenCompra $orden)
    {
        // Log::channel('testing')->info('Log', ['Enviar mail, orden de compra recibida', $orden]);
        try {
            if ($orden->proveedor->correo) {
                Mail::to($orden->proveedor->correo)->cc(['contabilidad_compras@jpconstrucred.com', auth()->user()])->send(new EnviarMailOrdenCompraProveedor($orden));
                CorreoEnviado::crearCorreoEnviado($orden->solicitante->user->email, $orden->proveedor->correo, 'Orden de Compra JP CONSTRUCRED C. LTDA.', $orden);
                $mensaje = 'Email enviado correctamente al provedor';
                $status = 200;
            } elseif($orden->proveedor->empresa->correo){
                Mail::to($orden->proveedor->empresa->correo)->cc(['contabilidad_compras@jpconstrucred.com', auth()->user()])->send(new EnviarMailOrdenCompraProveedor($orden));
                CorreoEnviado::crearCorreoEnviado($orden->solicitante->user->email, $orden->proveedor->empresa->correo, 'Orden de Compra JP CONSTRUCRED C. LTDA.', $orden);
                $mensaje = 'Email enviado correctamente al provedor';
                $status = 200;
            } else {
                $mensaje = 'El proveedor no tiene un correo asociado para enviar el email';
                $status = 422;
            }
            return response()->json(compact('mensaje'), $status);
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['Error OrdenCompraProveedorController sendMail', $e->getMessage(), $e->getLine()]);
            $mensaje = $e->getMessage() . '. ' . $e->getLine();
            return response()->json(compact('mensaje'), 500);
        }
    }

    /**
     * Listar archivos
     */
    public function indexFiles(Request $request, OrdenCompra $orden)
    {
        try {
            $results = $this->archivoService->listarArchivos($orden);

            return response()->json(compact('results'));
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
            return response()->json(compact('mensaje'), 500);
        }
        return response()->json(compact('results'));
    }

    /**
     * Guardar archivos
     */
    public function storeFiles(Request $request, OrdenCompra $orden)
    {
        try {
            $modelo = $this->archivoService->guardarArchivo($orden, $request->file, RutasStorage::NOVEDADES_ORDENES_COMPRAS->value);
            $mensaje = 'Archivo subido correctamente';
            return response()->json(compact('mensaje', 'modelo'));
        } catch (\Throwable $th) {
            $mensaje = $th->getMessage() . '. ' . $th->getLine();
            Log::channel('testing')->info('Log', ['Error en el storeFiles de NovedadOrdenCompraController', $th->getMessage(), $th->getCode(), $th->getLine()]);
            return response()->json(compact('mensaje'), 500);
        }
    }

    /**
     * Dashboard de ordenes de compras
     */
    public function dashboard(Request $request)
    {
        Log::channel('testing')->info('Log', ['Entro en dashboard', $request->all()]);

        $results = $this->servicio->obtenerDashboard($request);

        return response()->json(compact('results'));
    }
}
