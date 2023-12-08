<?php

namespace App\Http\Controllers\Tareas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tareas\EtapaRequest;
use App\Http\Resources\Tareas\EtapaResource;
use App\Models\Sucursal;
use App\Models\Tareas\Etapa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Src\Shared\Utils;

class EtapaController extends Controller
{
    private $entidad = 'Etapa';

    /**
     * Listar
     */
    public function index(Request $request)
    {
        $results  = Etapa::ignoreRequest(['campos'])->filter()->get();
        $results = EtapaResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Guardar
     */
    public function store(EtapaRequest $request)
    {   try {
        DB::beginTransaction();
            // Adaptacion de foreign keys
            $datos = $request->validated();
            $datos['proyecto_id'] = $request->safe()->only(['proyecto'])['proyecto'];
            $datos['responsable_id'] = $request->safe()->only(['responsable'])['responsable'];

            // Respuesta
            $modelo = Etapa::create($datos);
            if($modelo) Sucursal::crearSucursalProyectoEtapa($modelo);
            $modelo = new EtapaResource($modelo);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Consultar
     */
    public function show(Etapa $etapa)
    {
        $modelo = new EtapaResource($etapa);
        return response()->json(compact('modelo'));
    }
    /**
     * Actualizar
     */
    public function update(EtapaRequest $request, Etapa $etapa)
    {
        $nombre_anterior = $etapa->nombre;
        // Adaptacion de foreign keys
        $datos = $request->validated();
        $datos['proyecto_id'] = $request->safe()->only(['proyecto'])['proyecto'];
        $datos['responsable_id'] = $request->safe()->only(['responsable'])['responsable'];

        // Respuesta
        $etapa->update($datos);
        $modelo = new EtapaResource($etapa);
        // if($modelo) Sucursal::ModificarSucursalProyectoEtapa($etapa, $nombre_anterior);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Eliminar
     */
    public function destroy(Etapa $etapa)
    {
        $etapa->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }

    /**
     * Desactivar
     */
     public function desactivar(Request $request, Etapa $etapa){
        $request->validate(['motivo'=>['string']]);
        $etapa->activo  = !$etapa->activo;
        $etapa->motivo = $request->motivo;
        $etapa->save();

        $modelo = new EtapaResource($etapa->refresh());
        return response()->json(compact('modelo'));
    }
}
