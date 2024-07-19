<?php

namespace App\Http\Controllers\RecursosHumanos\SeleccionContratacion;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecursosHumanos\SeleccionContratacion\VacanteRequest;
use App\Http\Resources\RecursosHumanos\SeleccionContratacion\VacanteResource;
use App\Models\RecursosHumanos\SeleccionContratacion\SolicitudPersonal;
use App\Models\RecursosHumanos\SeleccionContratacion\Vacante;
use DB;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Src\App\RecursosHumanos\SeleccionContratacion\PolymorphicSeleccionContratacionModelsService;
use Src\App\RegistroTendido\GuardarImagenIndividual;
use Src\Config\RutasStorage;
use Src\Shared\Utils;
use Throwable;

class VacanteController extends Controller
{
    private string $entidad = 'Vacante';
    private PolymorphicSeleccionContratacionModelsService $polymorficSeleccionContratacionService;

    public function __construct()
    {
        $this->polymorficSeleccionContratacionService = new PolymorphicSeleccionContratacionModelsService();
//        $this->middleware('can:puede.ver.rrhh_vacantes')->only('index', 'show');
        $this->middleware('can:puede.crear.rrhh_vacantes')->only('store');
        $this->middleware('can:puede.editar.rrhh_vacantes')->only('update');
        $this->middleware('can:puede.eliminar.rrhh_vacantes')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $results = Vacante::ignoreRequest(['campos'])->filter()->orderBy('id', 'desc')->get();
        $results = VacanteResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param VacanteRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function store(VacanteRequest $request)
    {
        Log::channel('testing')->info('Log', ['request en Vacante->store', $request->all()]);
        $datos = $request->validated();
        if ($datos['imagen_referencia'])
            $datos['imagen_referencia'] = (new GuardarImagenIndividual($datos['imagen_referencia'], RutasStorage::VACANTES_TRABAJO))->execute();
        if ($datos['imagen_publicidad'])
            $datos['imagen_publicidad'] = (new GuardarImagenIndividual($datos['imagen_publicidad'], RutasStorage::VACANTES_TRABAJO))->execute();

        try {
            if (!is_null($datos['solicitud_id'])){
                $solicitud = SolicitudPersonal::where('id', $datos['solicitud_id'])->where('publicada', false)->first();
                if(!$solicitud) throw new Exception('No se puede crear la vacante, la solicitud proporcinada ya ha sido publicada');
            }
            DB::beginTransaction();
            $vacante = Vacante::create($datos);
            //Crear o actualizar formaciones academicas
            $this->polymorficSeleccionContratacionService->actualizarFormacionAcademicaPolimorfica($vacante, $request->formaciones_academicas);
            if ($vacante->solicitud_id) {
                SolicitudPersonal::find($vacante->solicitud_id)->update(['publicada' => true]);
            }
            $modelo = new VacanteResource($vacante);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
        } catch (Throwable $e) {
            DB::rollback();
            throw Utils::obtenerMensajeErrorLanzable($e);
        }

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Display the specified resource.
     *
     * @param Vacante $vacante
     * @return JsonResponse
     */
    public function show(Vacante $vacante)
    {
        $modelo = new VacanteResource($vacante);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param VacanteRequest $request
     * @param Vacante $vacante
     * @return JsonResponse
     */
    public function update(VacanteRequest $request, Vacante $vacante)
    {
        Log::channel('testing')->info('Log', ['request en store', $request->all()]);
        try {
            $datos = $request->validated();
            if ($datos['imagen_referencia'] && Utils::esBase64($datos['imagen_referencia']))
                $datos['imagen_referencia'] = (new GuardarImagenIndividual($datos['imagen_referencia'], RutasStorage::VACANTES_TRABAJO))->execute();
            else unset($datos['imagen_referencia']);
            if ($datos['imagen_publicidad'] && Utils::esBase64($datos['imagen_publicidad']))
                $datos['imagen_publicidad'] = (new GuardarImagenIndividual($datos['imagen_publicidad'], RutasStorage::VACANTES_TRABAJO))->execute();
            else unset($datos['imagen_publicidad']);

            $vacante->update($datos);
            $modelo = new VacanteResource($vacante->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollback();
            throw Utils::obtenerMensajeErrorLanzable($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Vacante $vacante
     * @return void
     */
    public function destroy(Vacante $vacante)
    {
        try {
            throw new Exception('Este método no está disponible, por favor comunicate con el departamento de Informática');
        } catch (Throwable $th) {
            throw Utils::obtenerMensajeErrorLanzable($th);
        }
    }
}
