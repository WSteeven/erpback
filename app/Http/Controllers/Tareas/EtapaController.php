<?php

namespace App\Http\Controllers\Tareas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tareas\EtapaRequest;
use App\Http\Resources\Tareas\EtapaResource;
use App\Models\Subtarea;
use App\Models\Sucursal;
use App\Models\Tarea;
use App\Models\Tareas\Etapa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Src\Shared\Utils;

class EtapaController extends Controller
{
    private $entidad = 'Etapa';

    /**
     * Listar
     */
    public function index(Request $request)
    {
        /* Si etapas_empleado está presente obtiene todas las etapas del `empleado_id` dado. */
        if ($request['etapas_empleado']) $results = $this->obtenerEtapasAsignadasEmpleado($request['empleado_id']);
        else {
            $mostrar_todas_etapas = Auth::user()->hasRole(User::ROL_JEFE_TECNICO) || Auth::user()->hasRole(User::ROL_SUPERVISOR_TECNICO) || Auth::user()->hasRole(User::ROL_ADMINISTRADOR);
            $campos = $mostrar_todas_etapas ? ['campos', 'responsable_id'] : ['campos'];
            $results  = Etapa::ignoreRequest($campos)->filter()->get();
        }
        $results = EtapaResource::collection($results);
        return response()->json(compact('results'));
    }

    public function obtenerEtapasAsignadasEmpleado(int $empleado_id)
    {
        $tareas_ids = Subtarea::where('empleado_id', $empleado_id)->groupBy('tarea_id')->pluck('tarea_id');
        $etapas_ids = Tarea::whereIn('id', $tareas_ids)->where('finalizado', 0)->ignoreRequest(['activas_empleado', 'empleado_id', 'campos'])->pluck('etapa_id');
        return Etapa::whereIn('id', $etapas_ids)->get();
    }

    /**
     * Guardar
     */
    public function store(EtapaRequest $request)
    {
        try {
            DB::beginTransaction();
            // Adaptacion de foreign keys
            $datos = $request->validated();
            $datos['proyecto_id'] = $request->safe()->only(['proyecto'])['proyecto'];
            $datos['responsable_id'] = $request->safe()->only(['responsable'])['responsable'];

            // Respuesta
            $modelo = Etapa::create($datos);
            if ($modelo) Sucursal::crearSucursalProyectoEtapa($modelo);
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
        if ($modelo) Sucursal::ModificarSucursalProyectoEtapa($etapa, $nombre_anterior);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Eliminar
     */
    // public function destroy(Etapa $etapa)
    // {
    //     $etapa->delete();
    //     $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
    //     return response()->json(compact('mensaje'));
    // }

    /**
     * Desactivar
     */
    public function desactivar(Request $request, Etapa $etapa)
    {
        $request->validate(['motivo' => ['string']]);
        $etapa->activo  = !$etapa->activo;
        $etapa->motivo = $request->motivo;
        $etapa->save();

        $modelo = new EtapaResource($etapa->refresh());
        return response()->json(compact('modelo'));
    }

    public function obtenerEtapasEmpleado(Request $request){
        $campos = $request->campos?explode(',', request('campos')):'*';
        $tareas_ids_subtareas = Subtarea::where('empleado_id', $request->empleado_id)->get('tarea_id');
        $ids_etapas = Tarea::whereIn('id', $tareas_ids_subtareas)->get('etapa_id');
        $etapas= Etapa::whereIn('id', $ids_etapas)->ignoreRequest(['empleado_id', 'campos'])->filter()->orderBy('id','desc')->get($campos);

        $results = EtapaResource::collection($etapas);
        return response()->json(compact('results'));
    }
}
