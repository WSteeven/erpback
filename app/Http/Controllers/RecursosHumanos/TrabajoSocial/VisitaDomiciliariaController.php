<?php

namespace App\Http\Controllers\RecursosHumanos\TrabajoSocial;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecursosHumanos\TrabajoSocial\VisitaDomiciliariaRequest;
use App\Http\Resources\RecursosHumanos\TrabajoSocial\VisitaDomiciliariaResource;
use App\Models\ConfiguracionGeneral;
use App\Models\Departamento;
use App\Models\Empleado;
use App\Models\RecursosHumanos\TrabajoSocial\FichaSocioeconomica;
use App\Models\RecursosHumanos\TrabajoSocial\VisitaDomiciliaria;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\App\RecursosHumanos\TrabajoSocial\PolymorphicTrabajoSocialModelsService;
use Src\App\RecursosHumanos\TrabajoSocial\VisitaDomiciliariaService;
use Src\App\RegistroTendido\GuardarImagenIndividual;
use Src\Config\RutasStorage;
use Src\Shared\Utils;
use Throwable;

class VisitaDomiciliariaController extends Controller
{
    private string $entidad = 'Visita Domiciliaria';

    private VisitaDomiciliariaService $service;
    private PolymorphicTrabajoSocialModelsService $polymorphicTrabajoSocialService;

    public function __construct()
    {
        $this->service = new VisitaDomiciliariaService();
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
        $ids_empleados = Empleado::filter()->pluck('id');
        $results = VisitaDomiciliaria::whereIn('empleado_id', $ids_empleados)->orderBy('updated_at', 'desc')->get();
        $results = VisitaDomiciliariaResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param VisitaDomiciliariaRequest $request
     * @return JsonResponse
     * @throws Throwable
     * @throws ValidationException
     */
    public function store(VisitaDomiciliariaRequest $request)
    {
//        Log::channel('testing')->info('Log', ['request', $request->all()]);
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $empleado = Empleado::find($datos['empleado_id']);
            if ($datos['imagen_genograma'])
                $datos['imagen_genograma'] = (new GuardarImagenIndividual($datos['imagen_genograma'], RutasStorage::GENOGRAMAS, null,$empleado->identificacion . '_' . Carbon::now()->getTimestamp()))->execute();
            if ($datos['imagen_visita_domiciliaria'])
                $datos['imagen_visita_domiciliaria'] = (new GuardarImagenIndividual($datos['imagen_visita_domiciliaria'], RutasStorage::VISITAS_DOMICILIARIAS,null, $empleado->identificacion . '_' . Carbon::now()->getTimestamp()))->execute();

            $visita = VisitaDomiciliaria::create($datos);

            $this->service->actualizarEconomiaFamiliar($visita, $datos['economia_familiar']);
            $this->polymorphicTrabajoSocialService->actualizarViviendaPolimorfica($visita, $datos['vivienda']);
            $this->polymorphicTrabajoSocialService->actualizarComposicionFamiliarPolimorfica($visita, $datos['composicion_familiar']);
            $this->polymorphicTrabajoSocialService->actualizarSaludPolimorfica($visita, $datos['salud']);

            $modelo = new VisitaDomiciliariaResource($visita);
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
     * @param VisitaDomiciliaria $visita
     * @return JsonResponse
     */
    public function show(VisitaDomiciliaria $visita)
    {
        $modelo = new VisitaDomiciliariaResource($visita);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param VisitaDomiciliariaRequest $request
     * @param VisitaDomiciliaria $visita
     * @return JsonResponse
     * @throws ValidationException|Throwable
     */
    public function update(VisitaDomiciliariaRequest $request, VisitaDomiciliaria $visita)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $empleado = Empleado::find($visita->empleado_id);
            if ($datos['imagen_genograma'] && Utils::esBase64($datos['imagen_genograma']))
                $datos['imagen_genograma'] = (new GuardarImagenIndividual($datos['imagen_genograma'], RutasStorage::GENOGRAMAS, $visita->imagen_genograma, $empleado->identificacion . '_' . Carbon::now()->getTimestamp()))->execute();
            else
                unset($datos['imagen_genograma']);

            if ($datos['imagen_visita_domiciliaria'] && Utils::esBase64($datos['imagen_visita_domiciliaria']))
                $datos['imagen_visita_domiciliaria'] = (new GuardarImagenIndividual($datos['imagen_visita_domiciliaria'], RutasStorage::VISITAS_DOMICILIARIAS, $visita->imagen_visita_domiciliaria, $empleado->identificacion . '_' . Carbon::now()->getTimestamp()))->execute();
            else
                unset($datos['imagen_visita_domiciliaria']);

            $visita->update($datos);

            $this->service->actualizarEconomiaFamiliar($visita, $datos['economia_familiar']);
            $this->polymorphicTrabajoSocialService->actualizarViviendaPolimorfica($visita, $datos['vivienda']);
            $this->polymorphicTrabajoSocialService->actualizarComposicionFamiliarPolimorfica($visita, $datos['composicion_familiar']);
            $this->polymorphicTrabajoSocialService->actualizarSaludPolimorfica($visita, $datos['salud']);

            $modelo = new VisitaDomiciliariaResource($visita->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
        } catch (Exception $e) {
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

    public function empleadoTieneVisitaDomiciliaria(Empleado $empleado)
    {
        $result = $empleado->visitasDomiciliarias()->exists();
        return response()->json(compact('result'));
    }

    /**
     * @throws ValidationException
     */
    public function ultimaVisitaDomiciliariaEmpleado(Empleado $empleado)
    {
        if ($empleado->visitasDomiciliarias()->exists()) {
            $visita = $empleado->visitasDomiciliarias()->latest()->first();
            if ($visita) {
                return $this->show($visita);
            }
        } else throw ValidationException::withMessages(['NotFound' => 'El empleado aún no tiene una ficha socioeconomica registrada']);
    }

    /**
     * @throws ValidationException
     */
    public function imprimir(VisitaDomiciliaria $visita)
    {
        $configuracion = ConfiguracionGeneral::first();
        try {
            $pdf = Pdf::loadView('trabajo_social.visita_domiciliaria', [
                'configuracion' => $configuracion,
                'visita' => $visita,
                'departamento_rrhh' => Departamento::where('nombre', Departamento::DEPARTAMENTO_RRHH)->first(),
                'departamento_trabajo_social' => Departamento::where('nombre', Departamento::DEPARTAMENTO_TRABAJO_SOCIAL)->first(),
            ]);
            $pdf->render();
            return $pdf->output();
        }catch (Exception $e) {
            throw Utils::obtenerMensajeErrorLanzable($e, 'No se puede imprimir el pdf: ');
        }
    }
}
