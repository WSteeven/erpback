<?php

namespace App\Http\Controllers\SSO;

use App\Http\Controllers\Controller;
use App\Http\Requests\SSO\SolicitudDescuentoRequest;
use App\Http\Resources\SSO\IncidenteResource;
use App\Http\Resources\SSO\SolicitudDescuentoResource;
use App\Models\SSO\Incidente;
use App\Models\SSO\SeguimientoIncidente;
use App\Models\SSO\SolicitudDescuento;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Log;
use Src\App\ArchivoService;
use Src\App\SSO\SeguimientoIncidenteService;
use Src\Config\Permisos;
use Src\Config\RutasStorage;
use Src\Shared\Utils;
use Throwable;

class SolicitudDescuentoController extends Controller
{
    private string $entidad = 'Solicitud descuento';
    private ArchivoService $archivoService;

    public function __construct()
    {
        $this->middleware('can:' . Permisos::VER . 'solicitudes_descuentos')->only('index', 'show');
        $this->middleware('can:' . Permisos::CREAR . 'solicitudes_descuentos')->only('store');
        $this->middleware('can:' . Permisos::EDITAR . 'solicitudes_descuentos')->only('update');
        $this->archivoService = new ArchivoService();
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $results = SolicitudDescuento::ignoreRequest(['campos'])->filter()->latest()->get();
        $results = SolicitudDescuentoResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param SolicitudDescuentoRequest $request
     * @return Response
     * @throws Throwable
     */
    public function store(SolicitudDescuentoRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $datos = $request->validated();
            $datos['detalles_productos'] = json_encode($datos['detalles_productos']);
//            $datos['estado'] = SolicitudDescuento::CREADO;
//            $datos['empleado_solicitante_id'] = Auth::user()->id;
            $modelo = SolicitudDescuento::create($datos);
            $modelo = new SolicitudDescuentoResource($modelo->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        });
    }

    /**
     * Display the specified resource.
     *
     * @param SolicitudDescuento $solicitud_descuento
     * @return JsonResponse
     */
    public function show(SolicitudDescuento $solicitud_descuento)
    {
        $modelo = new SolicitudDescuentoResource($solicitud_descuento);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param SolicitudDescuentoRequest $request
     * @param SolicitudDescuento $solicitud_descuento
     * @return JsonResponse
     * @throws Throwable
     * @throws ValidationException
     */
    public function update(SolicitudDescuentoRequest $request, SolicitudDescuento $solicitud_descuento)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            Log::channel('testing')->info('Log', ['Datos' => $datos]);
//            $datos['detalles_productos'] = json_encode($datos['detalles_productos']);
            $solicitud_descuento->update($datos);
//            $actualizado = $tarea->update($request->except(['id']));
            $modelo = new IncidenteResource($solicitud_descuento->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (\Throwable|\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    public function indexFiles(Request $request, SolicitudDescuento $solicitud_descuento)
    {
        try {
            $archivosSolicitudDescuento = $this->archivoService->listarArchivos($solicitud_descuento);
            $archivosIncidente = $this->archivoService->listarArchivos($solicitud_descuento->incidente);
            $results = $archivosSolicitudDescuento->merge($archivosIncidente);
        } catch (\Throwable $th) {
            return $th;
        }
        return response()->json(compact('results'));
    }

    /**
     * Guardar archivos
     * @throws ValidationException|Throwable
     * @throws \Throwable
     */
    public function storeFiles(Request $request, SolicitudDescuento $solicitud_descuento)
    {
        try {
            $modelo = $this->archivoService->guardarArchivo($solicitud_descuento, $request['file'], RutasStorage::SOLICITUDES_DESCUENTOS->value, 'SOLICITUDES DESCUENTOS');
            $mensaje = 'Archivo subido correctamente';
        } catch (\Exception $ex) {
            throw Utils::obtenerMensajeErrorLanzable($ex);
        }
        return response()->json(compact('mensaje', 'modelo'));
    }
}
