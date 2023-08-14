<?php

namespace App\Http\Controllers\ComprasProveedores;

use App\Http\Controllers\Controller;
use App\Http\Requests\ComprasProveedores\ProformaRequest;
use App\Http\Resources\ClienteResource;
use App\Http\Resources\ComprasProveedores\ProformaResource;
use App\Models\Autorizacion;
use App\Models\Cliente;
use App\Models\ComprasProveedores\Proforma;
use App\Models\Empleado;
use App\Models\EstadoTransaccion;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Src\Shared\Utils;

class ProformaController extends Controller
{
    private $entidad = 'Proforma';
    public function __construct()
    {
        $this->middleware('can:puede.ver.proformas')->only('index', 'show');
        $this->middleware('can:puede.crear.proformas')->only('store');
        $this->middleware('can:puede.editar.proformas')->only('update');
        $this->middleware('can:puede.eliminar.proformas')->only('destroy');
    }
    /**
     * Listar
     */
    public function index(Request $request){
        $results = Proforma::all();
        return response()->json(compact('results'));
    }

    /**
     * Guardar
     */
    public function store(ProformaRequest $request){
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
            $proforma = Proforma::create($datos);
            // Guardar los detalles de la orden de compra
            Proforma::guardarDetalles($proforma, $request->listadoProductos);

            //Respuesta
            $modelo = new ProformaResource($proforma);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');


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
    public function show(Proforma $proforma)
    {
        $modelo = new ProformaResource($proforma);
        return response()->json(compact('modelo'));
    }

    /**
     * Actualizar
     */
    public function update(ProformaRequest $request, Proforma $proforma)
    {
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

            Log::channel('testing')->info('Log', ['Datos validados:', $datos]);

            //Creación de la proforma
            $proforma->update($datos);
            // Sincronizar los detalles de la orden de compra
            Proforma::guardarDetalles($proforma, $request->listadoProductos);

            //Respuesta
            $modelo = new ProformaResource($proforma);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');


            DB::commit();

            // // aqui se debe lanzar la notificacion en caso de que la orden de compra sea autorizacion pendiente
            // if ($orden->estado_id === $estado_completo->id && $orden->autorizacion_id === $autorizacion_aprobada->id) {

            //     event(new OrdenCompraActualizadaEvent($orden, true));// crea el evento de la orden de compra actualizada al solicitante
            // }

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
    public function anular(Request $request, Proforma $proforma)
    {
        Log::channel('testing')->info('Log', ['Datos para anuylar:', $request->all()]);
        $autorizacion = Autorizacion::where('nombre', Autorizacion::CANCELADO)->first();
        $estado = EstadoTransaccion::where('nombre', EstadoTransaccion::ANULADA)->first();
        $request->validate(['motivo' => ['required', 'string']]);
        $proforma->causa_anulacion = $request['motivo'];
        $proforma->autorizacion_id = $autorizacion->id;
        $proforma->estado_id = $estado->id;
        $proforma->save();

        $modelo = new ProformaResource($proforma->refresh());
        return response()->json(compact('modelo'));
    }

    /**
     * Imprimir una orden de compra
     */
    public function imprimir(Proforma $proforma)
    {
        $cliente = new ClienteResource(Cliente::find($proforma->cliente_id));
        $empleado_solicita = Empleado::find($proforma->solicitante_id);
        $proforma = new ProformaResource($proforma);
        try {
            $proforma = $proforma->resolve();
            $cliente = $cliente->resolve();
            Log::channel('testing')->info('Log', ['Elementos a imprimir', ['proforma' => $proforma, 'cliente' => $cliente, 'empleado_solicita' => $empleado_solicita]]);
            $pdf = Pdf::loadView('compras_proveedores.orden_compra', compact(['proforma', 'cliente', 'empleado_solicita']));
            $pdf->setPaper('A4', 'portrait');
            $pdf->setOption(['isRemoteEnabled' => true]);
            $pdf->render();
            $file = $pdf->output();

            // return $pdf->download();
            return $file;
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['ERROR', $e->getMessage(), $e->getLine()]);
            return response()->json('Ha ocurrido un error al intentar imprimir la orden de compra' . $e->getMessage() . ' ' . $e->getLine(), 422);
        }
    }


}
