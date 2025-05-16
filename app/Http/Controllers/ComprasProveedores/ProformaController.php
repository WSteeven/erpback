<?php

namespace App\Http\Controllers\ComprasProveedores;

use App\Events\ComprasProveedores\ProformaActualizadaEvent;
use App\Events\ComprasProveedores\ProformaCreadaEvent;
use App\Events\ComprasProveedores\ProformaModificadaEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\ComprasProveedores\ProformaRequest;
use App\Http\Resources\ClienteResource;
use App\Http\Resources\ComprasProveedores\ProformaResource;
use App\Models\Autorizacion;
use App\Models\Cliente;
use App\Models\ComprasProveedores\Proforma;
use App\Models\ConfiguracionGeneral;
use App\Models\Empleado;
use App\Models\EstadoTransaccion;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\App\ComprasProveedores\ProformaService;
use Src\Config\Autorizaciones;
use Src\Config\EstadosTransacciones;
use Src\Config\PaisesOperaciones;
use Src\Shared\Utils;
use Throwable;

class ProformaController extends Controller
{
    private string $entidad = 'Proforma';
    private ProformaService $servicio;

    public function __construct()
    {
        $this->servicio = new ProformaService();
        $this->middleware('can:puede.ver.proformas')->only('index', 'show');
        $this->middleware('can:puede.crear.proformas')->only('store');
        $this->middleware('can:puede.editar.proformas')->only('update');
        $this->middleware('can:puede.eliminar.proformas')->only('destroy');
    }

    /**
     * Listar
     */
    public function index(Request $request)
    {
        if (auth()->user()->hasRole([User::ROL_ADMINISTRADOR, User::ROL_COMPRAS])) {
            $results = $this->servicio->filtrarProformasAdministrador($request);
        } else if (auth()->user()->hasRole([User::ROL_JEFE_TECNICO])) {
            $results = $this->servicio->filtrarProformasJefeTecnico($request);
        } else {
            $results = $this->servicio->filtrarProformasEmpleado($request);
        }
        $results = $results->sortByDesc('id');
        $results = ProformaResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Guardar
     * @throws ValidationException|Throwable
     */
    public function store(ProformaRequest $request)
    {
        $autorizacion_pendiente = Autorizacion::where('nombre', Autorizacion::PENDIENTE)->first();
        $estado_pendiente = EstadoTransaccion::where('nombre', EstadoTransaccion::PENDIENTE)->first();
        try {
            DB::beginTransaction();
            //Adaptacion de foreign keys
            $datos = $request->validated();

            //Creación de la orden de compra
            $proforma = Proforma::create($datos);
            // Guardar los detalles de la orden de compra
            Proforma::guardarDetalles($proforma, $request->listadoProductos);

            //Respuesta
            $modelo = new ProformaResource($proforma);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');


            DB::commit();

            // aqui se debe lanzar la notificacion en caso de que la proforma sea autorizacion pendiente
            if ($proforma->estado_id === $estado_pendiente->id && $proforma->autorizacion_id === $autorizacion_pendiente->id) {
                event(new ProformaCreadaEvent($proforma, true)); // crea el evento de la proforma al autorizador
            }

            return response()->json(compact('mensaje', 'modelo'));
        } catch (Throwable|Exception $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['ERROR store de ordenes de compras:', $e->getMessage(), $e->getLine()]);
            throw Utils::obtenerMensajeErrorLanzable($e);
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
     * @throws ValidationException|Throwable
     */
    public function update(ProformaRequest $request, Proforma $proforma)
    {
        try {
            DB::beginTransaction();
            //Adaptacion de foreign keys
            $datos = $request->validated();

            //Actualización de la proforma
            $proforma->update($datos);
            // Sincronizar los detalles de la orden de compra
            Proforma::guardarDetalles($proforma, $request->listadoProductos);

            //Respuesta
            $modelo = new ProformaResource($proforma->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');


            /* En caso que se cancela/anula una proforma, se actualiza su estado a anulado. */
            if ($proforma->autorizacion_id == Autorizaciones::CANCELADO) {
                $proforma->estado_id = EstadosTransacciones::ANULADA;
                $proforma->save();
            }
            DB::commit();

            //si la persona que modifica la proforma es el mismo solicitante se envia la notificacion al autorizador
            //caso contrario se envia al solicitante si de aprobo o anulo su proforma
            if (auth()->user()->empleado->id == $proforma->solicitante_id) {
                event(new ProformaModificadaEvent($proforma, true));
            } else {
                // aqui se debe lanzar la notificacion en caso de que la proforma no sea autorizacion pendiente
                if ($proforma->autorizacion_id !== Autorizaciones::PENDIENTE) {
                    $proforma->latestNotificacion()->update(['leida' => true]); //marcando como leída la notificacion en caso de que esté vigente
                    event(new ProformaActualizadaEvent($proforma, true)); // crea el evento de la orden de compra actualizada al solicitante
                }
            }

            return response()->json(compact('mensaje', 'modelo'));
        } catch (Throwable|Exception $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['ERROR update de proformas:', $e->getMessage(), $e->getLine()]);
            throw Utils::obtenerMensajeErrorLanzable($e);
        }
    }

    /**
     * Consultar datos sin el método show
     */
    public function showPreview(Proforma $proforma)
    {
        $modelo = new ProformaResource($proforma);
        return response()->json(compact('modelo'));
    }


    /**
     * Anular una orden de compra
     */
    public function anular(Request $request, Proforma $proforma)
    {
        $autorizacion = Autorizacion::where('nombre', Autorizacion::CANCELADO)->first();
        $estado = EstadoTransaccion::where('nombre', EstadoTransaccion::ANULADA)->first();
        $request->validate(['motivo' => ['required', 'string']]);
        $proforma->causa_anulacion = $request['motivo'];
        $proforma->autorizacion_id = $autorizacion->id;
        $proforma->estado_id = $estado->id;
        $proforma->latestNotificacion()->update(['leida' => true]); //marcando como leída la notificacion en caso de que esté vigente
        $proforma->save();

        $modelo = new ProformaResource($proforma->refresh());
        return response()->json(compact('modelo'));
    }

    /**
     * Imprimir una proforma
     */
    public function imprimir(Proforma $proforma)
    {
        $pais = env('COUNTRY');
        $texto_iva = match ($pais) {
            PaisesOperaciones::PERU => 'IGV',
            default => 'IVA',
        };
        $configuracion = ConfiguracionGeneral::first();
        $cliente = new ClienteResource(Cliente::find($proforma->cliente_id));
        $empleado_solicita = Empleado::find($proforma->solicitante_id);
        $proforma = new ProformaResource($proforma);
        try {
            $proforma = $proforma->resolve();
            $cliente = $cliente->resolve();
            $valor = Utils::obtenerValorMonetarioTexto($proforma['sum_total']);
//            Log::channel('testing')->info('Log', ['Elementos a imprimir', ['proforma' => $proforma, 'cliente' => $cliente, 'empleado_solicita' => $empleado_solicita]]);
            $pdf = Pdf::loadView('compras_proveedores.proforma', compact(['proforma', 'cliente', 'empleado_solicita', 'valor', 'configuracion', 'texto_iva']));
            $pdf->setPaper('A4');
            $pdf->setOption(['isRemoteEnabled' => true]);
            $pdf->render();
            // return $pdf->download();
            return $pdf->output();
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['ERROR', $e->getMessage(), $e->getLine()]);
            return response()->json('Ha ocurrido un error al intentar imprimir la orden de compra' . $e->getMessage() . ' ' . $e->getLine(), 422);
        }
    }
}
