<?php

namespace App\Http\Controllers\SSO;

use App\Http\Controllers\Controller;
use App\Http\Requests\SSO\AccidenteRequest;
use App\Http\Resources\SSO\AccidenteResource;
use App\Http\Resources\SSO\IncidenteResource;
use App\Models\SSO\Accidente;
use App\Models\SSO\Incidente;
use App\Models\SSO\SeguimientoAccidente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\App\ArchivoService;
use Src\Config\Permisos;
use Src\Config\RutasStorage;
use Src\Shared\Utils;
use Throwable;

class AccidenteController extends Controller
{
    private string $entidad = 'Accidente';
    private ArchivoService $archivoService;

    public function __construct()
    {
        $this->middleware('can:' . Permisos::VER . 'incidentes')->only('index', 'show');
        $this->middleware('can:' . Permisos::CREAR . 'incidentes')->only('store');
        $this->middleware('can:' . Permisos::EDITAR . 'incidentes')->only('update');
        $this->archivoService = new ArchivoService();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $results = Accidente::ignoreRequest(['campos'])->filter()->latest()->get();
        $results = AccidenteResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException
     * @throws \Throwable
     */
    public function store(AccidenteRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $datos = $request->validated();
            $datos['empleados_involucrados'] = isset($datos['empleados_involucrados']) ? json_encode($datos['empleados_involucrados']) : null;
            $accidente = Accidente::create($datos);
            $modelo = new AccidenteResource($accidente);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

            SeguimientoAccidente::create([
                'accidente_id' => $modelo['id'],
            ]);
            return response()->json(compact('mensaje', 'modelo'));
        });
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Accidente $accidente)
    {
        $modelo = new AccidenteResource($accidente);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(AccidenteRequest $request, Accidente $accidente)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $datos['empleados_involucrados'] = isset($datos['empleados_involucrados']) ? json_encode($datos['empleados_involucrados']) : null;
            $accidente->update($datos);
            $modelo = new AccidenteResource($accidente->refresh());
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

    public function indexFiles(Request $request, Accidente $accidente)
    {
        try {
            $results = $this->archivoService->listarArchivos($accidente);
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
    public function storeFiles(Request $request, Accidente $accidente)
    {
        try {
            $modelo = $this->archivoService->guardarArchivo($accidente, $request['file'], RutasStorage::ACCIDENTES->value, 'ACCIDENTES');
            $mensaje = 'Archivo subido correctamente';
        } catch (\Exception $ex) {
            throw Utils::obtenerMensajeErrorLanzable($ex);
        }
        return response()->json(compact('mensaje', 'modelo'));
    }
}
