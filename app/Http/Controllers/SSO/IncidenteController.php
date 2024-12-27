<?php

namespace App\Http\Controllers\SSO;

use App\Http\Controllers\Controller;
use App\Http\Requests\SSO\IncidenteRequest;
use App\Http\Requests\SSO\SeguimientoIncidenteRequest;
use App\Http\Resources\SSO\IncidenteResource;
use App\Models\SSO\Incidente;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\App\ArchivoService;
use Src\App\SSO\SeguimientoIncidenteService;
use Src\Config\Permisos;
use Src\Config\RutasStorage;
use Src\Shared\Utils;
use Throwable;

class IncidenteController extends Controller
{
    private string $entidad = 'Incidente';
    private ArchivoService $archivoService;
    private SeguimientoIncidenteService $seguimientoIncidenteService;

    public function __construct()
    {
        $this->middleware('can:' . Permisos::VER . 'incidentes')->only('index', 'show');
        $this->middleware('can:' . Permisos::CREAR . 'incidentes')->only('store');
        $this->middleware('can:' . Permisos::EDITAR . 'incidentes')->only('update');
        $this->archivoService = new ArchivoService();
        $this->seguimientoIncidenteService = new SeguimientoIncidenteService();
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $results = Incidente::ignoreRequest(['campos'])->filter()->latest()->get();
        $results = IncidenteResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param IncidenteRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(IncidenteRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $datos = $request->validated();
            $datos['detalles_productos'] = isset($datos['detalles_productos']) ? json_encode($datos['detalles_productos']) : null;
            $datos['estado'] = Incidente::CREADO;
            $datos['empleado_reporta_id'] = Auth::user()->id;
            $modelo = Incidente::create($datos);
            $modelo = new IncidenteResource($modelo->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

//            $seguimientoRequest = new SeguimientoIncidenteRequest();
//            $seguimientoRequest->merge(['incidente_id' => $modelo['id']]);
            $this->seguimientoIncidenteService->createSeguimientoIncidente([
                'incidente_id' => $modelo['id'],
            ]);
            return response()->json(compact('mensaje', 'modelo'));
        });
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(Incidente $incidente)
    {
        $modelo = new IncidenteResource($incidente);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return JsonResponse
     * @throws ValidationException
     * @throws Throwable
     */
    public function update(IncidenteRequest $request, Incidente $incidente)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $datos['detalles_productos'] = isset($datos['detalles_productos']) ? json_encode($datos['detalles_productos']) : null;
            $incidente->update($datos);
            $modelo = new IncidenteResource($incidente->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Throwable|\Exception $e) {
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
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function indexFiles(Request $request, Incidente $incidente)
    {
        try {
            $results = $this->archivoService->listarArchivos($incidente);
        } catch (Throwable $th) {
            return $th;
        }
        return response()->json(compact('results'));
    }

    /**
     * Guardar archivos
     * @throws ValidationException|Throwable
     * @throws Throwable
     */
    public function storeFiles(Request $request, Incidente $incidente)
    {
        try {
            $modelo = $this->archivoService->guardarArchivo($incidente, $request['file'], RutasStorage::INCIDENTES->value, 'INCIDENTES');
            $mensaje = 'Archivo subido correctamente';
        } catch (\Exception $ex) {
            throw Utils::obtenerMensajeErrorLanzable($ex);
        }
        return response()->json(compact('mensaje', 'modelo'));
    }
}
