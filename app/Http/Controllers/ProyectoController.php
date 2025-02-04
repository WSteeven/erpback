<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProyectoRequest;
use App\Http\Resources\ProyectoResource;
use App\Models\Empleado;
use App\Models\Proyecto;
use App\Models\Subtarea;
use App\Models\Tarea;
use App\Models\Tareas\Etapa;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Src\Shared\Utils;

class ProyectoController extends Controller
{
    private $entidad = 'Proyecto';

    public function listar()
    {
        /* Este código se utiliza para manejar un caso específico en el que es necesario
        recuperar los proyectos asociados con un empleado específico. */
        if (request('empleado_id')) {

            return $this->obtenerProyectosEmpleado(request('empleado_id'));
        }

        if (auth()->user()->hasRole([User::ROL_JEFE_TECNICO]))
            return Proyecto::ignoreRequest(['campos', 'coordinador_id'])->filter()->orderBy('id', 'desc')->get();
        return Proyecto::ignoreRequest(['campos'])->filter()->orderBy('id', 'desc')->get();
    }

    public function index()
    {
        $results = $this->listar();
        // if (!request('campos')) $results = ProyectoResource::collection($results);
        $results = ProyectoResource::collection($results);
        return response()->json(compact('results'));
    }


    public function store(ProyectoRequest $request)
    {
        // Adaptacion de foreign keys
        $datos = $request->validated();
        $datos['cliente_id'] = $request->safe()->only(['cliente'])['cliente'];
        $datos['canton_id'] = $request->safe()->only(['canton'])['canton'];

        // $esCoordinador = Auth::user()->hasRole(User::ROL_COORDINADOR);
        $esJefeTecnico = Auth::user()->hasRole(User::ROL_JEFE_TECNICO);
        $datos['coordinador_id'] = $esJefeTecnico ? $request->safe()->only(['coordinador'])['coordinador'] :  Auth::user()->empleado->id;
        $datos['fiscalizador_id'] = $request->safe()->only(['coordinador'])['coordinador'];

        // Respuesta
        $modelo = Proyecto::create($datos);
        $modelo = new ProyectoResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
    }


    public function show(Proyecto $proyecto)
    {
        $modelo = new ProyectoResource($proyecto);
        return response()->json(compact('modelo'));
    }


    public function update(ProyectoRequest $request, Proyecto $proyecto)
    {
        // Adaptacion de foreign keys
        $datos = $request->validated();
        $datos['cliente_id'] = $request->safe()->only(['cliente'])['cliente'];
        $datos['coordinador_id'] = $request->safe()->only(['coordinador'])['coordinador'];
        $datos['fiscalizador_id'] = $request->safe()->only(['fiscalizador'])['fiscalizador'];
        $datos['canton_id'] = $request->safe()->only(['canton'])['canton'];

        // Respuesta
        $proyecto->update($datos);

        // if ($request->isMethod('patch')) {
            if ($request['finalizado']) {
                $request['fecha_hora_finalizado'] = Carbon::now();
            }

            $proyecto->update($request->except(['id']));
        // }

        $modelo = new ProyectoResource($proyecto->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('modelo', 'mensaje'));
    }


    public function destroy(Proyecto $proyecto)
    {
        $proyecto->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }

    /**
     * La función obtiene proyectos asociados con un empleado específico y los devuelve como una
     * respuesta JSON.
     *
     * @param Request request El parámetro  es una instancia de la clase Request, que se
     * utiliza para recuperar datos de la solicitud HTTP. En este caso, se utiliza para recuperar el
     * valor del parámetro 'empleado_id' de la solicitud.
     *
     * @return una respuesta JSON que contiene los resultados de la consulta. Los resultados se
     * obtienen filtrando y ordenando una colección de proyectos según el ID del empleado proporcionado
     * en la solicitud. Luego, los resultados se transforman en una colección de objetos
     * ProyectoResource antes de ser devueltos.
     */
    public function obtenerProyectosEmpleado(int $empleado_id)
    {
        $empleado = Empleado::find($empleado_id);
        $grupo_id = $empleado->grupo_id;
        if ($grupo_id) {
            $tareas_ids_subtareas = Subtarea::where(function ($q) use ($empleado_id, $grupo_id) {
                $q->where('empleado_id', $empleado_id)->orwhere('grupo_id', $grupo_id)->orWhere('empleados_designados', 'LIKE', '%' . $empleado_id . '%');
            })->groupBy('tarea_id')->pluck('tarea_id');
        } else {
//            Log::channel('testing')->info('Log', ['d' => $empleado_id]);
            $tareas_ids_subtareas = Subtarea::where('empleado_id', $empleado_id)
            ->orWhereRaw("JSON_CONTAINS(empleados_designados, '\"$empleado_id\"')")
            ->disponible()->get('tarea_id');
//            Log::channel('testing')->info('Log', ['d' => 'No tiene grupo']);
        }

        $ids_etapas = Tarea::whereIn('id', $tareas_ids_subtareas)->where('finalizado', false)->get('etapa_id');
//        Log::channel('testing')->info('Log', compact('tareas_ids_subtareas'));
//        Log::channel('testing')->info('Log', compact('ids_etapas'));
        $ids_proyectos_tareas = Tarea::whereIn('id', $tareas_ids_subtareas)->where('finalizado', false)->get('proyecto_id');
        $ids_proyectos = Etapa::where(function ($query) use ($ids_etapas, $empleado_id) {
            $query->whereIn('id', $ids_etapas)->orWhere('responsable_id', $empleado_id);
        })->where('activo', true)->get('proyecto_id');
        $proyectos = Proyecto::whereIn('id', $ids_proyectos)->orWhereIn('id', $ids_proyectos_tareas)->ignoreRequest(['empleado_id', 'campos'])->filter()->orderBy('id', 'desc')->get();
        return $proyectos;
    }
    public function obtenerProyectosEmpleadoOld(int $empleado_id)
    {
        $empleado = Empleado::find($empleado_id);
        $grupo_id = $empleado->grupo_id;
        if ($grupo_id) {
            $tareas_ids_subtareas = Subtarea::where(function ($q) use ($empleado_id, $grupo_id) {
                $q->where('empleado_id', $empleado_id)->orwhere('grupo_id', $grupo_id)->orWhere('empleados_designados', 'LIKE', '%' . $empleado_id . '%');
            })->groupBy('tarea_id')->pluck('tarea_id');
        } else {
            $tareas_ids_subtareas = Subtarea::where('empleado_id', $empleado_id)->orWhere('empleados_designados', 'LIKE', '%' . $empleado_id . '%')->disponible()->get('tarea_id');
        }
        $ids_etapas = Tarea::whereIn('id', $tareas_ids_subtareas)->where('finalizado', false)->get('etapa_id');
        $ids_proyectos_tareas = Tarea::whereIn('id', $tareas_ids_subtareas)->where('finalizado', false)->get('proyecto_id');
        $ids_proyectos = Etapa::where(function ($query) use ($ids_etapas, $empleado_id) {
            $query->whereIn('id', $ids_etapas)->orWhere('responsable_id', $empleado_id);
        })->where('activo', true)->get('proyecto_id');
        $proyectos = Proyecto::whereIn('id', $ids_proyectos)->orWhereIn('id', $ids_proyectos_tareas)->ignoreRequest(['empleado_id', 'campos'])->filter()->orderBy('id', 'desc')->get();
        return $proyectos;
    }
}
