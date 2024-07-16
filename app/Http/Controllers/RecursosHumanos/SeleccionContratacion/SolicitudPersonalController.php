<?php

namespace App\Http\Controllers\RecursosHumanos\SeleccionContratacion;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecursosHumanos\SeleccionContratacion\SolicitudPersonalRequest;
use App\Http\Resources\RecursosHumanos\SeleccionContratacion\SolicitudPersonalResource;
use App\Models\RecursosHumanos\SeleccionContratacion\SolicitudPersonal;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\App\ArchivoService;
use Src\App\RecursosHumanos\SeleccionContratacion\PolymorphicSeleccionContratacionModelsService;
use Src\Config\RutasStorage;
use Src\Shared\Utils;
use Throwable;

class SolicitudPersonalController extends Controller
{
    private string $entidad = 'Solicitud de Puesto';
    private PolymorphicSeleccionContratacionModelsService $polymorficSeleccionContratacionService;
    private ArchivoService $archivoService;

    public function __construct()
    {
        $this->archivoService = new ArchivoService();
        $this->polymorficSeleccionContratacionService = new PolymorphicSeleccionContratacionModelsService();
        $this->middleware('can:puede.ver.rrhh_solicitudes_nuevas_vacantes')->only('index', 'show');
        $this->middleware('can:puede.crear.rrhh_solicitudes_nuevas_vacantes')->only('store');
        $this->middleware('can:puede.editar.rrhh_solicitudes_nuevas_vacantes')->only('update');
        $this->middleware('can:puede.eliminar.rrhh_solicitudes_nuevas_vacantes')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $results = SolicitudPersonal::ignoreRequest(['campos'])->filter()->get();
        $results = SolicitudPersonalResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param SolicitudPersonalRequest $request
     * @return JsonResponse
     */
    public function store(SolicitudPersonalRequest $request)
    {
        try {
            DB::beginTransaction();
            $solicitud = SolicitudPersonal::create($request->validated());
            //Crear o actualizar formaciones academicas
            $this->polymorficSeleccionContratacionService->actualizarFormacionAcademicaPolimorfica($solicitud, $request->formaciones_academicas);

            $modelo = new SolicitudPersonalResource($solicitud);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $ex) {
            DB::rollBack();
            throw Utils::obtenerMensajeErrorLanzable($ex);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param SolicitudPersonal $solicitud
     * @return JsonResponse
     */
    public function show(SolicitudPersonal $solicitud)
    {
        $modelo = new SolicitudPersonalResource($solicitud);

        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param SolicitudPersonalRequest $request
     * @param SolicitudPersonal $solicitud
     * @return JsonResponse
     * @throws ValidationException
     */
    public function update(SolicitudPersonalRequest $request, SolicitudPersonal $solicitud)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $solicitud->update($datos);

            //Crear o actualizar formaciones academicas
            $this->polymorficSeleccionContratacionService->actualizarFormacionAcademicaPolimorfica($solicitud, $request->formaciones_academicas);

            $modelo = new SolicitudPersonalResource($solicitud->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
        } catch (Exception $ex) {
            DB::rollBack();
            throw Utils::obtenerMensajeErrorLanzable($ex);
        }
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param SolicitudPersonal $solicitud
     * @return JsonResponse
     */
    public function destroy(SolicitudPersonal $solicitud)
    {
        $solicitud->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }

    /**
     * Listar archivos
     */
    public function indexFiles(SolicitudPersonal $solicitud)
    {
        try {
            $results = $this->archivoService->listarArchivos($solicitud);

            return response()->json(compact('results'));
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
            return response()->json(compact('mensaje'), 500);
        }
    }

    /**
     * Guardar archivos
     */
    public function storeFiles(Request $request, SolicitudPersonal $solicitud)
    {
        try {
            $modelo = $this->archivoService->guardarArchivo($solicitud, $request->file, RutasStorage::SOLICITUD_NUEVO_EMPLEADO->value);
            $mensaje = 'Archivo subido correctamente';
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Throwable $th) {
            $mensaje = $th->getMessage() . '. ' . $th->getLine();
            Log::channel('testing')->info('Log', ['Error en el storeFiles de NovedadOrdenCompraController', $th->getMessage(), $th->getCode(), $th->getLine()]);
            return response()->json(compact('mensaje'), 500);
        }
    }

}
