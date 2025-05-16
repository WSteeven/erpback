<?php

namespace App\Http\Controllers\Vehiculos;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vehiculos\RegistroIncidenteRequest;
use App\Http\Resources\Vehiculos\RegistroIncidenteResource;
use App\Models\Vehiculos\RegistroIncidente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Src\App\ArchivoService;
use Src\Config\RutasStorage;
use Src\Shared\Utils;

class RegistroIncidenteController extends Controller
{
    private $entidad = 'Registro de Incidente';
    private $archivoService;
    public function __construct()
    {
        $this->archivoService = new ArchivoService();
        $this->middleware('can:puede.ver.registros_incidentes')->only('index', 'show');
        $this->middleware('can:puede.crear.registros_incidentes')->only('store');
        $this->middleware('can:puede.editar.registros_incidentes')->only('update');
        $this->middleware('can:puede.eliminar.registros_incidentes')->only('destroy');
    }

    public function index()
    {
        $results = RegistroIncidente::filter()->orderBy('id', 'desc')->get();
        $results = RegistroIncidenteResource::collection($results);
        return response()->json(compact('results'));
    }

    public function store(RegistroIncidenteRequest $request)
    {
        $datos = $request->validated();
        try {
            DB::beginTransaction();
            $registro = RegistroIncidente::create($datos);
            $modelo = new RegistroIncidenteResource($registro);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            throw Utils::obtenerMensajeErrorLanzable($th);
        }
        return response()->json(compact('mensaje', 'modelo'));
    }

    public function show(RegistroIncidente $registro)
    {
        $modelo = new RegistroIncidenteResource($registro);
        return response()->json(compact('modelo'));
    }

    public function update(RegistroIncidenteRequest $request, RegistroIncidente $registro)
    {
        $datos = $request->validated();
        try {
            DB::beginTransaction();

            //Respuesta
            $registro->update($datos);
            $modelo = new RegistroIncidenteResource($registro->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw Utils::obtenerMensajeErrorLanzable($th);
        }

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Listar archivos
     */
    public function indexFiles(Request $request, RegistroIncidente $registro)
    {
        try {
            $results = $this->archivoService->listarArchivos($registro);
        } catch (\Throwable $e) {
            $mensaje = $e->getMessage();
            return response()->json(compact('mensaje'), 500);
        }
        return response()->json(compact('results'));
    }

    /**
     * Guardar archivos
     */
    public function storeFiles(Request $request, RegistroIncidente $registro)
    {
        try {
            $modelo  = $this->archivoService->guardarArchivo($registro, $request->file, RutasStorage::EVIDENCIAS_INCIDENTES_VEHICULOS->value . $registro->vehiculo->placa);
            $mensaje = 'Archivo subido correctamente';
        } catch (\Throwable $ex) {
            $mensaje = $ex->getMessage() . '. ' . $ex->getLine();
            return response()->json(compact('mensaje'), 500);
        }
        return response()->json(compact('mensaje', 'modelo'), 200);
    }
}
