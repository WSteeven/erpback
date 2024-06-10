<?php

namespace App\Http\Controllers\Vehiculos;

use App\Events\Vehiculos\NotificarAsignacionVehiculoEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Vehiculos\AsignacionVehiculoRequest;
use App\Http\Resources\Vehiculos\AsignacionVehiculoResource;
use App\Http\Resources\Vehiculos\VehiculoResource;
use App\Models\ConfiguracionGeneral;
use App\Models\Empleado;
use App\Models\User;
use App\Models\Vehiculos\AsignacionVehiculo;
use App\Models\Vehiculos\Vehiculo;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\App\ArchivoService;
use Src\App\Vehiculos\VehiculoService;
use Src\Config\RutasStorage;
use Src\Shared\Utils;

class AsignacionVehiculoController extends Controller
{
    private $entidad = 'Asignación';
    private $archivoService;
    private $vehiculoService;

    public function __construct()
    {
        $this->archivoService = new ArchivoService();
        $this->vehiculoService = new VehiculoService();
        $this->middleware('can:puede.ver.asignaciones_vehiculos')->only('index', 'show');
        $this->middleware('can:puede.crear.asignaciones_vehiculos')->only('store');
        $this->middleware('can:puede.editar.asignaciones_vehiculos')->only('update');
        $this->middleware('can:puede.eliminar.asignaciones_vehiculos')->only('destroy');
    }

    public function index()
    {
        // Log::channel('testing')->info('Log', ['listado', request()->all()]);
        // if (auth()->user()->hasRole([User::ROL_ADMINISTRADOR, User::ROL_ADMINISTRADOR_VEHICULOS])) {
        //     $results = AsignacionVehiculo::ignoreRequest(['entrega_id', 'responsable_id'])->filter()->orderBy('id', 'desc')->get();
        // } else
        if (request()->filtro) {
            $results = AsignacionVehiculo::ignoreRequest(['filtro'])->filter()->orderBy('id', 'desc')->get();
        } else {
            if (auth()->user()->hasRole([User::ROL_ADMINISTRADOR_VEHICULOS])) {
                $results = AsignacionVehiculo::ignoreRequest(['entrega_id', 'responsable_id'])->filter()->orderBy('id', 'desc')->get();
            } else {
                $results = AsignacionVehiculo::where(function ($query) {
                    $query->where('entrega_id', auth()->user()->empleado->id)
                        ->orWhere('responsable_id', auth()->user()->empleado->id);
                })->ignoreRequest(['entrega_id', 'responsable_id'])->filter()->orderBy('id', 'desc')->get();
            }
        }
        $results = AsignacionVehiculoResource::collection($results);
        return response()->json(compact('results'));
    }

    public function store(AsignacionVehiculoRequest $request)
    {
        $datos = $request->validated();
        try {
            $vehiculoDisponible = $this->vehiculoService->verificarDisponibilidadVehiculo($datos['vehiculo_id']);
            if (!$vehiculoDisponible) throw new Exception('El vehículo seleccionado ya está asignado/transferido a un chofer y aún no ha sido devuelto, por favor devuelve el vehículo para poder asignarlo nuevamente.');

            DB::beginTransaction();
            $asignacion = AsignacionVehiculo::create($datos);
            //Lanzar el evento de la notificación
            event(new NotificarAsignacionVehiculoEvent($asignacion));
            $modelo = new AsignacionVehiculoResource($asignacion);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw ValidationException::withMessages(['error' => Utils::obtenerMensajeError($th, 'No se puedo guardar: ')]);
        }
        return response()->json(compact('mensaje', 'modelo'));
    }

    public function show(AsignacionVehiculo $asignacion)
    {
        $modelo = new AsignacionVehiculoResource($asignacion);
        return response()->json(compact('modelo'));
    }

    public function update(AsignacionVehiculoRequest $request, AsignacionVehiculo $asignacion)
    {
        $datos = $request->validated();
        try {
            DB::beginTransaction();

            //Respuesta
            $asignacion->update($datos);
            $modelo = new AsignacionVehiculoResource($asignacion->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            //Marcar como leída la notificación anterior y lanzar el evento de la notificación
            $asignacion->latestNotificacion()->update(['leida' => true]);
            if ($asignacion->estado != 'ANULADO')
                event(new NotificarAsignacionVehiculoEvent($asignacion));

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw ValidationException::withMessages(['error' => Utils::obtenerMensajeError($th, 'No se puedo actualizar: ')]);
        }

        return response()->json(compact('mensaje', 'modelo'));
    }

    public function destroy(AsignacionVehiculo $asignacion)
    {
        $asignacion->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }

    public function actaEntrega(AsignacionVehiculo $asignacion)
    {
        $configuracion = ConfiguracionGeneral::first();
        $resource = new AsignacionVehiculoResource($asignacion);
        $vehiculo = new VehiculoResource(Vehiculo::find($asignacion->vehiculo_id));
        $fecha_entrega = new DateTime($asignacion->fecha_entrega);
        try {

            $pdf = Pdf::loadView('vehiculos.actas.acta_responsabilidad', [
                'configuracion' => $configuracion,
                'asignacion' => $resource->resolve(),
                'vehiculo' => $vehiculo->resolve(),
                'mes' => Utils::$meses[$fecha_entrega->format('F')],
                'entrega' => Empleado::find($asignacion->entrega_id),
                'responsable' => Empleado::find($asignacion->responsable_id),
            ]);
            $pdf->setPaper('A4', 'portrait');
            $pdf->render();
            return $pdf->output();
        } catch (\Throwable $th) {
            throw ValidationException::withMessages(['error' => Utils::obtenerMensajeError($th, 'No se puede imprimir el pdf: ')]);
        }
    }

    public function devolverVehiculo(Request $request, AsignacionVehiculo $asignacion)
    {
        $request->validate(['observacion' => ['string', 'required']]);
        try {
            //code...
            if ($asignacion->devuelto) throw new Exception('El vehículo ya ha sido devuelto previamente. Por favor verifica e intenta nuevamente');
            DB::beginTransaction();
            $asignacion->observaciones_devolucion = $request->observacion;
            $asignacion->fecha_devolucion = Carbon::now();
            $asignacion->devuelve_id = auth()->user()->empleado->id;
            $asignacion->devuelto = !$asignacion->devuelto;
            $asignacion->save();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw Utils::obtenerMensajeErrorLanzable($th);
        }
        $modelo = new AsignacionVehiculoResource($asignacion->refresh());
        $mensaje = 'Vehículo devuelto correctamente';
        return response()->json(compact('modelo', 'mensaje'));
    }

    /**
     * Listar archivos
     */
    public function indexFiles(Request $request, AsignacionVehiculo $asignacion)
    {
        try {
            $results = $this->archivoService->listarArchivos($asignacion);

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
    public function storeFiles(Request $request, AsignacionVehiculo $asignacion)
    {
        try {
            $modelo = $this->archivoService->guardarArchivo($asignacion, $request->file, RutasStorage::EVIDENCIAS_VEHICULOS_ASIGNADOS->value . $asignacion->vehiculo->placa);
            $mensaje = 'Archivo subido correctamente';
            return response()->json(compact('mensaje', 'modelo'));
        } catch (\Throwable $th) {
            $mensaje = $th->getMessage() . '. ' . $th->getLine();
            Log::channel('testing')->info('Log', ['Error en el storeFiles de AsignacionVehiculoController', $th->getMessage(), $th->getCode(), $th->getLine()]);
            return response()->json(compact('mensaje'), 500);
        }
    }
}
