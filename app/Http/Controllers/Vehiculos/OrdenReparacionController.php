<?php

namespace App\Http\Controllers\Vehiculos;

use App\Events\Vehiculos\NotificarOrdenInternaActualizada;
use App\Events\Vehiculos\NotificarOrdenInternaAlAdminVehiculos;
use App\Http\Controllers\Controller;
use App\Http\Requests\Vehiculos\OrdenReparacionRequest;
use App\Http\Resources\Vehiculos\OrdenReparacionResource;
use App\Models\Autorizacion;
use App\Models\ConfiguracionGeneral;
use App\Models\Vehiculos\OrdenReparacion;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\App\ArchivoService;
use Src\Config\RutasStorage;
use Src\Shared\Utils;
use Throwable;

class OrdenReparacionController extends Controller
{
    private string $entidad = 'Orden de Reparación';
    private ArchivoService $archivoService;

    public function __construct()
    {
        $this->archivoService = new ArchivoService();
        $this->middleware('can:puede.ver.ordenes_reparaciones')->only('index', 'show');
        $this->middleware('can:puede.crear.ordenes_reparaciones')->only('store');
        $this->middleware('can:puede.editar.ordenes_reparaciones')->only('update');
        $this->middleware('can:puede.eliminar.ordenes_reparaciones')->only('destroy');
    }

    public function index()
    {
        $campos = request('campos') ? explode(',', request('campos')) : '*';
//        if (auth()->user()->hasRole([User::ROL_ADMINISTRADOR, User::ROL_ADMINISTRADOR_VEHICULOS]))
        $results = OrdenReparacion::filter()->orderBy('id', 'desc')->get($campos);
//        else {
//            $results = OrdenReparacion::where(function ($q) {
//                $q->where('solicitante_id', auth()->user()->empleado->id)
//                    ->orWhere('autorizador_id', auth()->user()->empleado->id);
//            })->filter()->orderBy('id', 'desc')->get($campos);
//        }
        $results = OrdenReparacionResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * @throws ValidationException
     */
    public function store(OrdenReparacionRequest $request)
    {
        $datos = $request->validated();
        try {
            DB::beginTransaction();
            $orden = OrdenReparacion::create($datos);
            event(new NotificarOrdenInternaAlAdminVehiculos($orden));
            $modelo = new OrdenReparacionResource($orden);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            throw Utils::obtenerMensajeErrorLanzable($th);
        }

        return response()->json(compact('mensaje', 'modelo'));
    }

    public function show(OrdenReparacion $orden)
    {
        $modelo = new OrdenReparacionResource($orden);
        return response()->json(compact('modelo'));
    }

    /**
     * @throws ValidationException
     */
    public function update(OrdenReparacionRequest $request, OrdenReparacion $orden)
    {
        $datos = $request->validated();
        try {
            DB::beginTransaction();
            $orden->update($datos);
            $orden->latestNotificacion()->update(['leida' => true]);
            event(new NotificarOrdenInternaActualizada($orden));
            $modelo = new OrdenReparacionResource($orden->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            throw Utils::obtenerMensajeErrorLanzable($th);
        }
        return response()->json(compact('mensaje', 'modelo'));
    }

    public function registrarValorReparacion(Request $request, OrdenReparacion $orden)
    {
        $request->validate(['valor_reparacion' => 'required', 'numeric']);
        $orden->valor_reparacion = $request->valor_reparacion;
        $orden->save();
        $modelo = new OrdenReparacionResource($orden->refresh());
        $mensaje = 'Valor de reparación actualizado correctamente';
        return response()->json(compact('modelo', 'mensaje'));

    }

    public function reporte(Request $request)
    {
        $configuracion = ConfiguracionGeneral::first();
        $results = $this->obtenerReportes($request);

//        switch ($request->accion) {
//            case 'excel':
//            case 'pdf':
//            default:
//                $results;
//        }
        return response()->json(compact('results'));


    }

    /**
     * Listar archivos
     */
    public function indexFiles(OrdenReparacion $orden)
    {
        try {
            $results = $this->archivoService->listarArchivos($orden);

            return response()->json(compact('results'));
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
            return response()->json(compact('mensaje'), 500);
        }
    }

    /**
     * Guardar archivos
     */
    public function storeFiles(Request $request, OrdenReparacion $orden)
    {
        try {
            $modelo = $this->archivoService->guardarArchivo($orden, $request->file, RutasStorage::EVIDENCIAS_ORDENES_REPARACIONES->value . $orden->vehiculo->placa);
            $mensaje = 'Archivo subido correctamente';
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Throwable $th) {
            $mensaje = $th->getMessage() . '. ' . $th->getLine();
            Log::channel('testing')->info('Log', ['Error en el storeFiles de NovedadOrdenCompraController', $th->getMessage(), $th->getCode(), $th->getLine()]);
            return response()->json(compact('mensaje'), 500);
        }
    }

    /**
     * En este reporte se obtiene lo siguiente:
     * Cuanto se gastó en mantenimiento de vehículos y
     * cuantos mantenimientos se hicieron durante un período.
     * @param Request $request
     * @return array
     */
    private function obtenerReportes(Request $request)
    {
        $fecha_inicio = Carbon::parse($request->fecha_inicio)->startOfDay();
        $fecha_fin = Carbon::parse($request->fecha_fin)->endOfDay();

        $results = OrdenReparacion::whereBetween('created_at', [$fecha_inicio, $fecha_fin])
//                    ->where('autorizacion_id', Autorizacion::APROBADO_ID)
            ->get();
        $autorizadas = $results->where('autorizacion_id', Autorizacion::APROBADO_ID);
        $pendientes = $results->where('autorizacion_id', Autorizacion::PENDIENTE_ID);
        $canceladas = $results->where('autorizacion_id', Autorizacion::CANCELADO_ID);
//        $valor_gastado = $results->sum('valor_reparacion'); // no se usa porque esto calcula de todas
        $valor_gastado = round($autorizadas->sum('valor_reparacion'),2);
        $results = OrdenReparacionResource::collection($results);
        return compact('autorizadas', 'pendientes', 'canceladas', 'valor_gastado', 'results');
    }
}
