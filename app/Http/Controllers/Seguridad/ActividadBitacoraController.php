<?php

namespace App\Http\Controllers\Seguridad;

use App\Http\Controllers\Controller;
use App\Http\Requests\Seguridad\ActividadBitacoraRequest;
use App\Http\Resources\Seguridad\ActividadBitacoraResource;
use App\Models\Seguridad\ActividadBitacora;
use DB;
use Exception;
use Illuminate\Http\Request;
use Src\App\ArchivoService;
use Src\App\RegistroTendido\GuardarImagenIndividual;
use Src\App\Sistema\PaginationService;
use Src\Config\Permisos;
use Src\Config\RutasStorage;
use Src\Shared\Utils;

class ActividadBitacoraController extends Controller
{
    private string $entidad = 'Actividad';
    protected PaginationService $paginationService;
    private $archivoService;

    public function __construct()
    {
        $this->middleware('can:' . Permisos::VER . 'actividades_bitacoras')->only('index', 'show');
        $this->middleware('can:' . Permisos::CREAR . 'actividades_bitacoras')->only('store');
        $this->middleware('can:' . Permisos::EDITAR . 'actividades_bitacoras')->only('update');

        $this->paginationService = new PaginationService();
        $this->archivoService = new ArchivoService();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $results = ActividadBitacora::ignoreRequest(['campos'])->filter()->latest()->get();
        $results = ActividadBitacoraResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ActividadBitacoraRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $datos = $request->validated();

            if ($datos['fotografia_evidencia_1']) $datos['fotografia_evidencia_1'] = (new GuardarImagenIndividual($datos['fotografia_evidencia_1'], RutasStorage::ACTIVIDADES_BITACORAS))->execute();
            if ($datos['fotografia_evidencia_2']) $datos['fotografia_evidencia_2'] = (new GuardarImagenIndividual($datos['fotografia_evidencia_2'], RutasStorage::ACTIVIDADES_BITACORAS))->execute();
            $modelo = ActividadBitacora::create($datos);
            if ($datos['visitante']) $modelo->visitante()->create($datos['visitante']);

            $modelo = new ActividadBitacoraResource($modelo->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ActividadBitacora $actividad_bitacora)
    {
        $modelo = new ActividadBitacoraResource($actividad_bitacora);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ActividadBitacoraRequest $request, ActividadBitacora $actividad_bitacora)
    {
        return DB::transaction(function () use ($request, $actividad_bitacora) {
            $datos = $request->validated();

            if (isset($datos['fotografia_evidencia_1']) && $datos['fotografia_evidencia_1'] && Utils::esBase64($datos['fotografia_evidencia_1'])) $datos['fotografia_evidencia_1'] = (new GuardarImagenIndividual($datos['fotografia_evidencia_1'], RutasStorage::CLIENTES, $actividad_bitacora->fotografia_evidencia_1))->execute();
            else unset($datos['fotografia_evidencia_1']);

            if (isset($datos['fotografia_evidencia_2']) && $datos['fotografia_evidencia_2'] && Utils::esBase64($datos['fotografia_evidencia_2'])) $datos['fotografia_evidencia_2'] = (new GuardarImagenIndividual($datos['fotografia_evidencia_2'], RutasStorage::CLIENTES, $actividad_bitacora->fotografia_evidencia_1))->execute();
            else unset($datos['fotografia_evidencia_2']);

            $actividad_bitacora->update($datos);
            $modelo = new ActividadBitacoraResource($actividad_bitacora->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Listar archivos
     */
    public function indexFiles(Request $request, ActividadBitacora $actividad_bitacora)
    {
        try {
            $results = $this->archivoService->listarArchivos($actividad_bitacora);
        } catch (\Throwable $th) {
            return $th;
        }
        return response()->json(compact('results'));
    }

    /**
     * Guardar archivos
     */
    public function storeFiles(Request $request, ActividadBitacora $actividad_bitacora)
    {
        try {
            $modelo  = $this->archivoService->guardarArchivo($actividad_bitacora, $request->file, RutasStorage::ACTIVIDADES_BITACORAS->value . '_' . $actividad_bitacora->id);
            $mensaje = 'Archivo subido correctamente';
        } catch (Exception $ex) {
            return $ex;
        }
        return response()->json(compact('mensaje', 'modelo'), 200);
    }
}
