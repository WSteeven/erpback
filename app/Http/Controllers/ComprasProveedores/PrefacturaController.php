<?php

namespace App\Http\Controllers\ComprasProveedores;

use App\Exports\ComprasProveedores\PrefacturaExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\ComprasProveedores\PrefacturaRequest;
use App\Http\Resources\ClienteResource;
use App\Http\Resources\ComprasProveedores\PrefacturaResource;
use App\Models\Cliente;
use App\Models\ComprasProveedores\Prefactura;
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
use Maatwebsite\Excel\Facades\Excel;
use Src\App\ComprasProveedores\PrefacturaService;
use Src\Config\PaisesOperaciones;
use Src\Shared\Utils;
use Throwable;

class PrefacturaController extends Controller
{
    private string $entidad = 'Prefactura';
    private PrefacturaService $servicio;
    public function __construct()
    {
        $this->servicio = new PrefacturaService();
        $this->middleware('can:puede.ver.prefacturas')->only('index', 'show');
        $this->middleware('can:puede.crear.prefacturas')->only('store');
        $this->middleware('can:puede.editar.prefacturas')->only('update');
        $this->middleware('can:puede.eliminar.prefacturas')->only('destroy');
    }
    /**
     * Listar
     */
    public function index()
    {
        if (auth()->user()->hasRole([User::ROL_ADMINISTRADOR, User::ROL_COMPRAS])) {
            $results = Prefactura::ignoreRequest(['solicitante_id', 'autorizador_id'])->filter()->orderBy('updated_at', 'desc')->get();
        } else {
            $results = Prefactura::filtrarPrefacturasEmpleado();
        }
        $results = PrefacturaResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Guardar
     * @throws ValidationException|Throwable
     */
    public function store(PrefacturaRequest $request)
    {
        $estado_completado = EstadoTransaccion::where('nombre', EstadoTransaccion::COMPLETA)->first();
        try {
            DB::beginTransaction();
            //Adaptacion de foreign keys
            $datos = $request->validated();

            //Creación de la orden de compra
            $prefactura = Prefactura::create($datos);
            // Guardar los detalles de la orden de compra
            Prefactura::guardarDetalles($prefactura, $request->listadoProductos);

            if ($prefactura->proforma_id) {
                $proforma = Proforma::find($prefactura->proforma_id);
                if ($proforma) {
                    $proforma->estado_id = $estado_completado->id;
                    $proforma->save();
                }
            }

            //Respuesta
            $modelo = new PrefacturaResource($prefactura);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');


            DB::commit();

            // aqui se debe lanzar la notificacion en caso de que la prefactura sea autorizacion pendiente
            // if ($prefactura->estado_id === $estado_pendiente->id && $prefactura->autorizacion_id === $autorizacion_pendiente->id) {
            //     event(new PrefacturaCreadaEvent($prefactura, true));// crea el evento de la prefactura al autorizador
            // }

            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['ERROR store de ordenes de compras:', $e->getMessage(), $e->getLine()]);
            throw ValidationException::withMessages(['error' => $e->getMessage() . ', ' . $e->getLine()]);
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
     * @throws ValidationException|Throwable
     */
    public function update(PrefacturaRequest $request, Prefactura $prefactura)
    {
//        $estado_completo = EstadoTransaccion::where('nombre', EstadoTransaccion::COMPLETA)->first();
        try {
            DB::beginTransaction();
            //Adaptacion de foreign keys
            $datos = $request->validated();

            //Creación de la prefactura
            $prefactura->update($datos);
            // Sincronizar los detalles de la orden de compra
            Prefactura::guardarDetalles($prefactura, $request->listadoProductos);

            //Respuesta
            $modelo = new PrefacturaResource($prefactura);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

            DB::commit();

            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['ERROR update de prefacturas:', $e->getMessage(), $e->getLine()]);
            throw Utils::obtenerMensajeErrorLanzable($e);
        }
    }



    /**
     * Anular una orden de compra
     */
    public function anular(Request $request, Prefactura $prefactura)
    {
        Log::channel('testing')->info('Log', ['Datos para anuylar:', $request->all()]);
        $estado = EstadoTransaccion::where('nombre', EstadoTransaccion::ANULADA)->first();
        $request->validate(['motivo' => ['required', 'string']]);
        $prefactura->causa_anulacion = $request['motivo'];
        $prefactura->estado_id = $estado->id;
        // $prefactura->latestNotificacion()->update(['leida'=>true]);//marcando como leída la notificacion en caso de que esté vigente
        $prefactura->save();

        $modelo = new PrefacturaResource($prefactura->refresh());
        return response()->json(compact('modelo'));
    }

    /**
     * Imprimir una orden de compra
     */
    public function imprimir(Prefactura $prefactura)
    {
        $pais = env('COUNTRY');
        $texto_iva = match ($pais) {
            PaisesOperaciones::PERU => 'IGV',
            default => 'IVA',
        };

        $configuracion = ConfiguracionGeneral::first();
        $cliente = new ClienteResource(Cliente::find($prefactura->cliente_id));
        $empleado_solicita = Empleado::find($prefactura->solicitante_id);
        $prefactura = new PrefacturaResource($prefactura);
        try {
            $prefactura = $prefactura->resolve();
            $cliente = $cliente->resolve();
            $valor = Utils::obtenerValorMonetarioTexto($prefactura['sum_total']);
            Log::channel('testing')->info('Log', ['Elementos a imprimir', ['prefactura' => $prefactura, 'cliente' => $cliente, 'empleado_solicita' => $empleado_solicita]]);
            $pdf = Pdf::loadView('compras_proveedores.prefactura', compact(['prefactura', 'cliente', 'empleado_solicita', 'valor', 'configuracion', 'texto_iva']));
            $pdf->setPaper('A4');
            $pdf->setOption(['isRemoteEnabled' => true]);
            $pdf->render();

            return $pdf->output();
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['ERROR', $e->getMessage(), $e->getLine()]);
            return response()->json('Ha ocurrido un error al intentar imprimir la prefactura' . $e->getMessage() . ' ' . $e->getLine(), 422);
        }
    }

    /**
     * Reportes
     * @throws ValidationException
     */
    public function reportes(Request $request)
    {
        try {
//            $vista = 'compras_proveedores.proveedores.proveedores';
            $request['empresa.razon_social'] = $request->razon_social;
            $results = $this->servicio->filtrarPrefacturas($request);
            switch ($request->accion) {
                case 'excel':
                    return Excel::download(new PrefacturaExport(collect($results)), 'reporte_prefacturas.xlsx');
                    // case 'pdf':
                    //     try {
                    //         $reporte = $registros;
                    //         $peticion = $request->all();
                    //         $pdf = Pdf::loadView($vista, compact(['reporte', 'peticion', 'configuracion']));
                    //         $pdf->setPaper('A4', 'landscape');
                    //         $pdf->render();
                    //         return $pdf->stream();
                    //     } catch (Throwable $ex) {
                    //         throw $ex->getMessage() . '. ' . $ex->getLine();
                    //     }
                    //     break;
                default:
                    // Log::channel('testing')->info('Log', ['ProveedorController->reportes->default', '¿Todo bien en casa?']);
            }
        } catch (Exception $ex) {
            throw ValidationException::withMessages([
                'Error al generar reporte' => [$ex->getLine() . '. ' . $ex->getMessage()],
            ]);
        }
        $results = PrefacturaResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Dashboard de prefacturas
     */
    public function dashboard(Request $request)
    {
        Log::channel('testing')->info('Log', ['Entro en dashboard', $request->all()]);

        $results = $this->servicio->obtenerDashboard($request);

        return response()->json(compact('results'));
    }
}
