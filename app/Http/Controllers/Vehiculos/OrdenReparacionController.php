<?php

namespace App\Http\Controllers\Vehiculos;

use App\Events\RecursosHumanos\Vehiculos\NotificarOrdenInternaActualizada;
use App\Events\RecursosHumanos\Vehiculos\NotificarOrdenInternaAlAdminVehiculos;
use App\Http\Controllers\Controller;
use App\Http\Requests\Vehiculos\OrdenReparacionRequest;
use App\Http\Resources\Vehiculos\OrdenReparacionResource;
use App\Models\User;
use App\Models\Vehiculos\OrdenReparacion;
use Exception;
use Illuminate\Http\Request;
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
}
