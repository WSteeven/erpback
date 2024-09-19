<?php /** @noinspection PhpDynamicAsStaticMethodCallInspection */

namespace App\Http\Controllers\Vehiculos;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vehiculos\BitacoraVehicularRequest;
use App\Http\Resources\Vehiculos\BitacoraVehicularResource;
use App\Models\Empleado;
use App\Models\User;
use App\Models\Vehiculos\BitacoraVehicular;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use OwenIt\Auditing\Models\Audit;
use Src\App\RegistroTendido\GuardarImagenIndividual;
use Src\App\Vehiculos\BitacoraVehicularService;
use Src\Config\RutasStorage;
use Src\Shared\Utils;
use Throwable;

class BitacoraVehicularController extends Controller
{
    private string $entidad = 'Bitacora Vehicular';
//    private ActividadRealizadaService $actividadService;
    private BitacoraVehicularService $service;

    public function __construct()
    {
//        $this->actividadService = new ActividadRealizadaService();
        $this->service = new BitacoraVehicularService();
        $this->middleware('can:puede.ver.bitacoras_vehiculos')->only('index', 'show');
        $this->middleware('can:puede.crear.bitacoras_vehiculos')->only('store');
        $this->middleware('can:puede.editar.bitacoras_vehiculos')->only('update');
        $this->middleware('can:puede.eliminar.bitacoras_vehiculos')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        if (request()->filtrar) {
            $results = BitacoraVehicular::ignoreRequest(['filtrar'])->filter()->orderBy('id', 'desc')->get();
        } else {

            if (auth()->user()->hasRole(User::ROL_ADMINISTRADOR_VEHICULOS))
                $results = BitacoraVehicular::ignoreRequest(['chofer_id'])->filter()->orderBy('updated_at', 'desc')->get();
            else {
//                 $results = BitacoraVehicular::where('chofer_id', auth()->user()->empleado->id)->get();
//                $results = BitacoraVehicular::filter()->orderBy('updated_at', 'desc')->get();
                //obtenemos los ids de los registros segun la persona que los modifica
                $ids_auditorias = Audit::where('auditable_type', BitacoraVehicular::class)->where('user_id', auth()->user()->id)
                    ->distinct('auditable_id') // Asegúrate de que el campo 'auditable_id' sea único
                    ->pluck('auditable_id');
                // buscamos las bitacoras coincidentes con aquellos registros para obtener solo las que han sido modificadas por el usuario logueado
                $results = BitacoraVehicular::ignoreRequest(['chofer_id'])
                    ->where('chofer_id', auth()->user()->empleado->id)
                    ->orWhere(function ($query) use ($ids_auditorias) {
                        $query->whereIn('id', $ids_auditorias);
                    })
                    ->filter()
                    ->orderBy('updated_at', 'desc')
                    ->get();
            }
        }
        $results = BitacoraVehicularResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param BitacoraVehicularRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(BitacoraVehicularRequest $request)
    {   //Adaptación de foreign keys
        $datos = $request->validated();
        $datos['tareas'] = Utils::convertArrayToString($request->tareas);
        $datos['tickets'] = Utils::convertArrayToString($request->tickets);

        //imagen del inicio de jornada
        if ($datos['imagen_inicial']) {
            $datos['imagen_inicial'] = (new GuardarImagenIndividual($datos['imagen_inicial'], RutasStorage::FOTOGRAFIAS_DIARIAS_VEHICULOS))->execute();
        }
        //Respuesta
        try {
            $chofer = Empleado::find($request->chofer_id);
            $bitacora_activa = BitacoraVehicular::where('chofer_id', $chofer->id)->where('firmada', false)->get();
            if (count($bitacora_activa) > 0) throw new Exception('Ya tienes una bitacora activa, por favor finalizala para poder crear otra');
            BitacoraVehicular::create($datos);
            $this->service->notificarDiferenciasKmBitacoras($chofer->ultimaBitacora);
            $this->service->actualizarDatosRelacionadosBitacora($chofer->ultimaBitacora, $request);
            // $bitacora = BitacoraVehicular::create($datos);
            Log::channel('testing')->info('Log', ['BitacoraVehicularRecienCreada', $chofer->ultimaBitacora]);
            $modelo = new BitacoraVehicularResource($chofer->ultimaBitacora);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store', 'F');
        } catch (Exception $ex) {
            Log::channel('testing')->info('Log', ['Ha ocurrido un error al guardar la bitacora', $ex->getMessage(), $ex->getLine()]);
            throw Utils::obtenerMensajeErrorLanzable($ex);
        }
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Display the specified resource.
     *
     * @param BitacoraVehicular $bitacora
     * @return JsonResponse
     */
    public function show(BitacoraVehicular $bitacora)
    {
        $modelo = new BitacoraVehicularResource($bitacora);
        return response()->json(compact('modelo'));
    }

    public function ultima()
    {
        $modelo = [];
        if (request()->filtrar) {
            $bitacora = BitacoraVehicular::ignoreRequest(['filtrar'])->filter()->orderBy('id', 'desc')->first();

            if ($bitacora)
                $modelo = new BitacoraVehicularResource($bitacora);
        }
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param BitacoraVehicularRequest $request
     * @param BitacoraVehicular $bitacora
     * @return JsonResponse
     * @throws ValidationException|Throwable
     */
    public function update(BitacoraVehicularRequest $request, BitacoraVehicular $bitacora)
    {
        try {
            Log::channel('testing')->info('Log', ['request', $request->all()]);
            //Validacion de datos
            $datos = $request->validated();
            $datos['tareas'] = Utils::convertArrayToString($request->tareas);
            $datos['tickets'] = Utils::convertArrayToString($request->tickets);

            if ($datos['imagen_inicial'] && Utils::esBase64($datos['imagen_inicial'])) {
                $datos['imagen_inicial'] = (new GuardarImagenIndividual($datos['imagen_inicial'], RutasStorage::FOTOGRAFIAS_DIARIAS_VEHICULOS))->execute();
            } else {
                unset($datos['imagen_inicial']);
            }

            DB::beginTransaction();
            $bitacora->update($datos);
            $this->service->actualizarDatosRelacionadosBitacora($bitacora, $request);
            // Log::channel('testing')->info('Log', ['BitacoraVehicularRecienActualizada', $bitacora]);
            if ($bitacora->firmada) {
                $bitacora->fecha_finalizacion = Carbon::now();
                $bitacora->save();
            }
            $modelo = new BitacoraVehicularResource($bitacora->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
        } catch (Throwable $th) {
            Log::channel('testing')->info('Log', ['error', $th->getLine(), $th->getMessage()]);
            DB::rollBack();
            throw Utils::obtenerMensajeErrorLanzable($th);
        }
        return response()->json(compact('modelo', 'mensaje'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param BitacoraVehicular $bitacora
     * @return JsonResponse
     * @throws ValidationException
     */
    public function destroy(BitacoraVehicular $bitacora)
    {
        if (!$bitacora->firmada) {
            $bitacora->delete();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
            return response()->json(compact('mensaje'));
        } else {
            throw ValidationException::withMessages([
                'firmada' => ['No se puede eliminar una bitacora firmada!']
            ]);
        }
    }

    /**
     * @throws ValidationException
     */
//    public function indexActividades(BitacoraVehicular $bitacora)
//    {
//        try {
//            $results = $this->actividadService->index($bitacora);
//            return response()->json(compact('results'));
//        } catch (Throwable $th) {
//            throw Utils::obtenerMensajeErrorLanzable($th);
//        }
//    }


    /**
     * @throws ValidationException
     */
    public function firmar(BitacoraVehicular $bitacora)
    {
        try {
            if (is_null($bitacora->km_final) || $bitacora->km_final < $bitacora->km_inicial)
                throw new Exception("Debes ingresar un kilometraje final que sea superior al kilometraje inicial. Por favor corrige y vuelve a intentarlo");
            if (!$bitacora->firmada) {
                $bitacora->firmada = true;
                $bitacora->fecha_finalizacion = Carbon::now();
                $bitacora->save();
                $this->service->notificarNovedadesVehiculo($bitacora);
                $mensaje = 'Bitacora firmada correctamente';
            } else {
                throw new Exception('No se puede finalizar la bitacora, ya ha sido finalizada previamente');
            }
        } catch (Throwable $th) {
            throw Utils::obtenerMensajeErrorLanzable($th);
        }
        return response()->json(compact('mensaje'));
    }

    /**
     * @throws ValidationException
     */
    public function imprimir(BitacoraVehicular $bitacora)
    {
        try {
            return $this->service->generarPdf($bitacora);
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['ERROR en el try-catch global del metodo imprimir de BitacoraVehicularService', $e->getMessage(), $e->getLine()]);
            throw Utils::obtenerMensajeErrorLanzable($e);
        }
    }
}
