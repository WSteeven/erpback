<?php

namespace App\Http\Controllers\ComprasProveedores;

use App\Events\ComprasProveedores\PrefacturaActualizadaEvent;
use App\Events\ComprasProveedores\PrefacturaCreadaEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\ComprasProveedores\PrefacturaRequest;
use App\Http\Resources\ClienteResource;
use App\Http\Resources\ComprasProveedores\PrefacturaResource;
use App\Models\Autorizacion;
use App\Models\Cliente;
use App\Models\ComprasProveedores\Prefactura;
use App\Models\Empleado;
use App\Models\EstadoTransaccion;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Src\Shared\Utils;

class PrefacturaController extends Controller
{
    private $entidad = 'Prefactura';
    public function __construct()
    {
        $this->middleware('can:puede.ver.prefacturas')->only('index', 'show');
        $this->middleware('can:puede.crear.prefacturas')->only('store');
        $this->middleware('can:puede.editar.prefacturas')->only('update');
        $this->middleware('can:puede.eliminar.prefacturas')->only('destroy');
    }
    /**
     * Listar
     */
    public function index(Request $request)
    {
        if (auth()->user()->hasRole([User::ROL_ADMINISTRADOR, User::ROL_COMPRAS])) {
            $results = Prefactura::ignoreRequest(['solicitante_id', 'autorizador_id'])->filter()->get();
        } else {
            $results = Prefactura::filtrarPrefacturasEmpleado($request);
        }
        $results = PrefacturaResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Guardar
     */
    public function store(PrefacturaRequest $request)
    {
        $autorizacion_pendiente = Autorizacion::where('nombre', Autorizacion::PENDIENTE)->first();
        $estado_pendiente = EstadoTransaccion::where('nombre', EstadoTransaccion::PENDIENTE)->first();
        try {
            DB::beginTransaction();
            //Adaptacion de foreign keys
            $datos = $request->validated();
            $datos['solicitante_id'] = $request->safe()->only(['solicitante'])['solicitante'];
            $datos['cliente_id'] = $request->safe()->only(['cliente'])['cliente'];
            $datos['autorizador_id'] = $request->safe()->only(['autorizador'])['autorizador'];
            $datos['autorizacion_id'] = $request->safe()->only(['autorizacion'])['autorizacion'];
            $datos['estado_id'] = $request->safe()->only(['estado'])['estado'];
            if ($request->preorden) $datos['preorden_id'] = $request->safe()->only(['preorden'])['preorden'];
            if ($request->pedido) $datos['pedido_id'] = $request->safe()->only(['pedido'])['pedido'];

            Log::channel('testing')->info('Log', ['Datos validados:', $datos]);

            //Creación de la orden de compra
            $prefactura = Prefactura::create($datos);
            // Guardar los detalles de la orden de compra
            Prefactura::guardarDetalles($prefactura, $request->listadoProductos);

            //Respuesta
            $modelo = new PrefacturaResource($prefactura);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');


            DB::commit();

            // aqui se debe lanzar la notificacion en caso de que la prefactura sea autorizacion pendiente
            if ($prefactura->estado_id === $estado_pendiente->id && $prefactura->autorizacion_id === $autorizacion_pendiente->id) {
                event(new PrefacturaCreadaEvent($prefactura, true));// crea el evento de la prefactura al autorizador
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
    public function show(Prefactura $prefactura)
    {
        $modelo = new PrefacturaResource($prefactura);
        return response()->json(compact('modelo'));
    }

    /**
     * Actualizar
     */
    public function update(PrefacturaRequest $request, Prefactura $prefactura)
    {
        $autorizacion_aprobada = Autorizacion::where('nombre', Autorizacion::APROBADO)->first();
        $estado_completo = EstadoTransaccion::where('nombre', EstadoTransaccion::COMPLETA)->first();
        try {
            DB::beginTransaction();
            //Adaptacion de foreign keys
            $datos = $request->validated();
            $datos['solicitante_id'] = $request->safe()->only(['solicitante'])['solicitante'];
            $datos['cliente_id'] = $request->safe()->only(['cliente'])['cliente'];
            $datos['autorizador_id'] = $request->safe()->only(['autorizador'])['autorizador'];
            $datos['autorizacion_id'] = $request->safe()->only(['autorizacion'])['autorizacion'];
            $datos['estado_id'] = $request->safe()->only(['estado'])['estado'];
            if ($request->preorden) $datos['preorden_id'] = $request->safe()->only(['preorden'])['preorden'];
            if ($request->pedido) $datos['pedido_id'] = $request->safe()->only(['pedido'])['pedido'];

            Log::channel('testing')->info('Log', ['Datos validados:', $datos]);

            //Creación de la prefactura
            $prefactura->update($datos);
            // Sincronizar los detalles de la orden de compra
            Prefactura::guardarDetalles($prefactura, $request->listadoProductos);

            //Respuesta
            $modelo = new PrefacturaResource($prefactura);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');


            DB::commit();

            // // aqui se debe lanzar la notificacion en caso de que la prefactura sea autorizacion pendiente
            if ($prefactura->estado_id === $estado_completo->id && $prefactura->autorizacion_id === $autorizacion_aprobada->id) {
                $prefactura->latestNotificacion()->update(['leida'=>true]);//marcando como leída la notificacion en caso de que esté vigente
                event(new PrefacturaActualizadaEvent($prefactura, true));// crea el evento de la orden de compra actualizada al solicitante
            }

            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['ERROR update de prefacturas:', $e->getMessage(), $e->getLine()]);
            return response()->json(['ERROR' => $e->getMessage() . ', ' . $e->getLine()], 422);
        }
    }



    /**
     * Anular una orden de compra
     */
    public function anular(Request $request, Prefactura $prefactura)
    {
        Log::channel('testing')->info('Log', ['Datos para anuylar:', $request->all()]);
        $autorizacion = Autorizacion::where('nombre', Autorizacion::CANCELADO)->first();
        $estado = EstadoTransaccion::where('nombre', EstadoTransaccion::ANULADA)->first();
        $request->validate(['motivo' => ['required', 'string']]);
        $prefactura->causa_anulacion = $request['motivo'];
        $prefactura->autorizacion_id = $autorizacion->id;
        $prefactura->estado_id = $estado->id;
        $prefactura->latestNotificacion()->update(['leida'=>true]);//marcando como leída la notificacion en caso de que esté vigente
        $prefactura->save();

        $modelo = new PrefacturaResource($prefactura->refresh());
        return response()->json(compact('modelo'));
    }

    /**
     * Imprimir una orden de compra
     */
    public function imprimir(Prefactura $prefactura)
    {
        $cliente = new ClienteResource(Cliente::find($prefactura->cliente_id));
        $empleado_solicita = Empleado::find($prefactura->solicitante_id);
        $prefactura = new PrefacturaResource($prefactura);
        try {
            $prefactura = $prefactura->resolve();
            $cliente = $cliente->resolve();
            $valor = Utils::obtenerValorMonetarioTexto($prefactura['sum_total']);
            Log::channel('testing')->info('Log', ['Elementos a imprimir', ['prefactura' => $prefactura, 'cliente' => $cliente, 'empleado_solicita' => $empleado_solicita]]);
            $pdf = Pdf::loadView('compras_proveedores.prefactura', compact(['prefactura', 'cliente', 'empleado_solicita', 'valor']));
            $pdf->setPaper('A4', 'portrait');
            $pdf->setOption(['isRemoteEnabled' => true]);
            $pdf->render();
            $file = $pdf->output();

            // return $pdf->download();
            return $file;
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['ERROR', $e->getMessage(), $e->getLine()]);
            return response()->json('Ha ocurrido un error al intentar imprimir la prefactura' . $e->getMessage() . ' ' . $e->getLine(), 422);
        }
    }
}
