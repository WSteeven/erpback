<?php

namespace App\Http\Controllers;

use App\Http\Requests\TipoTrabajoRequest;
use App\Http\Resources\TipoTrabajoResource;
use App\Models\TipoTrabajo;
use Exception;
use Src\App\ArchivoService;
use Src\Config\RutasStorage;
use Src\Shared\Utils;

class TipoTrabajoController extends Controller
{
    private string $entidad = 'Tipo de trabajo';

    /**
     * Listar
     */
    public function index()
    {
        $campos = request('campos') ? explode(',', request('campos')) : '*';

        $results = $campos
            ? TipoTrabajo::ignoreRequest(['campos'])->filter()->get($campos)
            : TipoTrabajo::filter()->get();

        $results = TipoTrabajoResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Guardar
     */
    public function store(TipoTrabajoRequest $request)
    {
        // Adaptacion de foreign keys
        $datos = $request->validated();
        if ($request->hasFile('url_plantilla')) {
            $ruta_relativa = ArchivoService::guardarArchivoSingle($request->file('url_plantilla'), RutasStorage::PLANTILLAS_TIPOS_TRABAJOS->value);
        }

        $datos['url_plantilla'] = $ruta_relativa;


        // Respuesta
        $modelo = TipoTrabajo::create($datos);
        $modelo = new TipoTrabajoResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Consultar
     */
    public function show(TipoTrabajo $tipo_trabajo)
    {
        $modelo = new TipoTrabajoResource($tipo_trabajo);
        return response()->json(compact('modelo'));
    }

    /**
     * Actualizar
     */
    public function update(TipoTrabajoRequest $request, TipoTrabajo $tipo_trabajo)
    {
        // Adaptacion de foreign keys
        $datos = $request->validated();
        if ($request->hasFile('url_plantilla')) {
            $ruta_relativa = ArchivoService::guardarArchivoSingle($request->file('url_plantilla'), RutasStorage::PLANTILLAS_TIPOS_TRABAJOS->value, null, $tipo_trabajo->url_plantilla);
            $datos['url_plantilla'] = $ruta_relativa;
        }

        // Respuesta
        $tipo_trabajo->update($datos);
        $modelo = new TipoTrabajoResource($tipo_trabajo->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('modelo', 'mensaje'));
    }

    /**
     * Eliminar
     */
    public function destroy(TipoTrabajo $tipo_trabajo)
    {
        $tipo_trabajo->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
