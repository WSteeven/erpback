<?php

namespace App\Http\Controllers\SSO;

use App\Http\Controllers\Controller;
use App\Http\Requests\SSO\InspeccionRequest;
use App\Http\Resources\SSO\InspeccionResource;
use App\Models\SSO\Inspeccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\App\ArchivoService;
use Src\Config\Permisos;
use Src\Config\RutasStorage;
use Src\Shared\Utils;

class InspeccionController extends Controller
{
    private string $entidad = 'Inspección';
    private ArchivoService $archivoService;

    public function __construct()
    {
        $this->middleware('can:' . Permisos::VER . 'inspecciones')->only('index', 'show');
        $this->middleware('can:' . Permisos::CREAR . 'inspecciones')->only('store');
        $this->middleware('can:' . Permisos::EDITAR . 'inspecciones')->only('update');
        $this->middleware('can:' . Permisos::ELIMINAR . 'inspecciones')->only('destroy');
        $this->archivoService = new ArchivoService();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $results = Inspeccion::ignoreRequest(['campos'])->filter()->latest()->get();
        $results = InspeccionResource::collection($results);
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
    public function store(InspeccionRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $examen = Inspeccion::create($datos);
            $modelo = new InspeccionResource($examen);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (\Throwable|\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar' => [$e->getMessage()],
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Inspeccion $inspeccion)
    {
        $modelo = new InspeccionResource($inspeccion);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(InspeccionRequest $request, Inspeccion $inspeccion)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $inspeccion->update($datos);

            // if($request['estado'] == Inspeccion::EJECUTANDO) $this->ejecutar();

            $modelo = new InspeccionResource($inspeccion->refresh());
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
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function indexFiles(Request $request, Inspeccion $inspeccion)
    {
        try {
            $results = $this->archivoService->listarArchivos($inspeccion);
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
    public function storeFiles(Request $request, Inspeccion $inspeccion)
    {
        try {
            $modelo = $this->archivoService->guardarArchivo($inspeccion, $request['file'], RutasStorage::INSPECCIONES->value, 'INSPECCIONES');
            $mensaje = 'Archivo subido correctamente';
        } catch (\Exception $ex) {
            throw Utils::obtenerMensajeErrorLanzable($ex);
        }
        return response()->json(compact('mensaje', 'modelo'));
    }
}
