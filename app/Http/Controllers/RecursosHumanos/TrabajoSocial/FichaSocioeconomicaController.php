<?php

namespace App\Http\Controllers\RecursosHumanos\TrabajoSocial;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecursosHumanos\TrabajoSocial\FichaSocioeconomicaRequest;
use App\Http\Resources\RecursosHumanos\TrabajoSocial\FichaSocioeconomicaResource;
use App\Models\Empleado;
use App\Models\RecursosHumanos\TrabajoSocial\FichaSocioeconomica;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\App\RecursosHumanos\TrabajoSocial\FichaSocioeconomicaService;
use Src\App\RecursosHumanos\TrabajoSocial\PolymorphicTrabajoSocialModelsService;
use Src\App\RegistroTendido\GuardarImagenIndividual;
use Src\Config\RutasStorage;
use Src\Shared\Utils;
use Throwable;

class FichaSocioeconomicaController extends Controller
{
    private string $entidad = 'Ficha Socioeconomica';
    private FichaSocioeconomicaService $service;
    private PolymorphicTrabajoSocialModelsService $polymorphicTrabajoSocialService;

    public function __construct()
    {
        $this->service = new FichaSocioeconomicaService();
        $this->polymorphicTrabajoSocialService = new PolymorphicTrabajoSocialModelsService();
        $this->middleware('can:puede.ver.fichas_socioeconomicas')->only('index', 'show');
        $this->middleware('can:puede.crear.fichas_socioeconomicas')->only('store');
        $this->middleware('can:puede.editar.fichas_socioeconomicas')->only('update');
        $this->middleware('can:puede.eliminar.fichas_socioeconomicas')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
//        $results = FichaSocioeconomica::filter()->get();
        $ids_empleados = Empleado::filter()->pluck('id');
        $results = FichaSocioeconomica::whereIn('empleado_id', $ids_empleados)->get();
        $results = FichaSocioeconomicaResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param FichaSocioeconomicaRequest $request
     * @return JsonResponse
     * @throws ValidationException|Throwable
     */
    public function store(FichaSocioeconomicaRequest $request)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $empleado = Empleado::find($datos['empleado_id']);
            if ($datos['imagen_rutagrama']) $datos['imagen_rutagrama'] = (new GuardarImagenIndividual($datos['imagen_rutagrama'], RutasStorage::RUTAGRAMAS, $empleado->identificacion . '_' . Carbon::now()->getTimestamp()))->execute();
            $ficha = FichaSocioeconomica::create($datos);
            //conyuge
            if ($request->tiene_conyuge) $this->service->actualizarConyuge($ficha, $datos['conyuge']);

            //hijos
            if ($request->tiene_hijos) $this->service->actualizarHijos($ficha, $datos['hijos']);

            //experiencia previa
            if ($ficha->tiene_experiencia_previa) $this->service->actualizarExperienciaPrevia($ficha, $datos['experiencia_previa']);

            //vivienda es requerido
            $this->polymorphicTrabajoSocialService->actualizarViviendaPolimorfica($ficha, $datos['vivienda']);

            //situacion socioeconomica es requerido
            $this->service->actualizarSituacionSocioeconomica($ficha, $datos['situacion_socioeconomica']);

            // composicion_familiar es requerido
            $this->polymorphicTrabajoSocialService->actualizarComposicionFamiliarPolimorfica($ficha, $datos['composicion_familiar']);

            //salud es requerido
            $this->polymorphicTrabajoSocialService->actualizarSaludPolimorfica($ficha, $datos['salud']);

            $modelo = new FichaSocioeconomicaResource($ficha);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->error('Log', ['TH', Utils::obtenerMensajeError($e, 'store')]);
            throw Utils::obtenerMensajeErrorLanzable($e);
        }
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Display the specified resource.
     *
     * @param FichaSocioeconomica $ficha
     * @return JsonResponse
     */
    public function show(FichaSocioeconomica $ficha)
    {
        $modelo = new FichaSocioeconomicaResource($ficha);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param FichaSocioeconomicaRequest $request
     * @param FichaSocioeconomica $ficha
     * @return JsonResponse
     * @throws Throwable|ValidationException
     */
    public function update(FichaSocioeconomicaRequest $request, FichaSocioeconomica $ficha)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $empleado = Empleado::find($datos['empleado_id']);
            if ($datos['imagen_rutagrama'] && Utils::esBase64($datos['imagen_rutagrama'])) {
                $datos['imagen_rutagrama'] = (new GuardarImagenIndividual($datos['imagen_rutagrama'], RutasStorage::RUTAGRAMAS, $empleado->identificacion . '_' . Carbon::now()->getTimestamp()))->execute();
            } else {
                unset($datos['imagen_rutagrama']);
            }
            $ficha->update($datos);

            //conyuge
            $this->service->actualizarConyuge($ficha, $datos['conyuge']);

            //hijos
            $this->service->actualizarHijos($ficha, $datos['hijos']);

            //experiencia previa
            $this->service->actualizarExperienciaPrevia($ficha, $datos['experiencia_previa']);

            //vivienda es requerido
            $this->polymorphicTrabajoSocialService->actualizarViviendaPolimorfica($ficha, $datos['vivienda']);

            //situacion socioeconomica es requerido
            $this->service->actualizarSituacionSocioeconomica($ficha, $datos['situacion_socioeconomica']);

            // composicion_familiar es requerido
            $this->polymorphicTrabajoSocialService->actualizarComposicionFamiliarPolimorfica($ficha, $datos['composicion_familiar']);

            //salud es requerido
            $this->polymorphicTrabajoSocialService->actualizarSaludPolimorfica($ficha, $datos['salud']);


            $modelo = new FichaSocioeconomicaResource($ficha->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
        } catch (Throwable|Exception $e) {
            DB::rollBack();
            Log::channel('testing')->error('Log', ['TH', Utils::obtenerMensajeError($e, 'update')]);
            throw Utils::obtenerMensajeErrorLanzable($e);
        }
        return response()->json(compact('modelo', 'mensaje'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return Response
     * @throws ValidationException
     */
    public function destroy()
    {
        throw ValidationException::withMessages(['error' => Utils::metodoNoDesarrollado()]);
    }

    public function empleadoTieneFichaSocioeconomica(Empleado $empleado)
    {
        $result = $empleado->fichaSocioeconomica()->exists();
        return response()->json(compact('result'));
    }

    /**
     * @throws ValidationException
     */
    public function ultimaFichaEmpleado(Empleado $empleado)
    {
        if ($empleado->fichaSocioeconomica()->exists()) {
            $ficha = $empleado->fichaSocioeconomica()->first();
            if (!is_null($ficha)) {
                return $this->show($ficha);
            }
//            $modelo = new FichaSocioeconomicaResource($ficha);
//            return response()->json(compact('modelo'));
        } else throw ValidationException::withMessages(['NotFound' => 'El empleado aún no tiene una ficha socioeconomica registrada']);
    }
}
