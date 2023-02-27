<?php

namespace Src\App;

use App\Http\Resources\EmpleadoResource;
use App\Models\Empleado;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class EmpleadoService
{
    public function __construct()
    {
    }

    public function obtenerEmpleadosPorRol(string $rol)
    {
        $users_ids = User::select('id')->role($rol)->get()->map(fn ($id) => $id->id)->toArray();
        $empleados = Empleado::ignoreRequest(['rol'])->filter()->where('estado', true)->get();
        $results = $empleados->filter(fn ($empleado) => in_array($empleado->usuario_id, $users_ids))->flatten();
        EmpleadoResource::collection($results);
        return $results;
    }

    public function obtenerPaginacion($offset)
    {
        $results = Empleado::where('id', '<>', 1)->where('estado', true)->simplePaginate($offset);
        EmpleadoResource::collection($results);
        return $results;
    }

    public function obtenerPaginacionTodos($offset)
    {
        $results = Empleado::where('id', '<>', 1)->simplePaginate($offset);
        EmpleadoResource::collection($results);
        return $results;
    }

    public function obtenerTodos()
    {
        $results = Empleado::ignoreRequest(['rol'])->filter()->where('id', '<>', 1)->where('estado', true)->get();
        return EmpleadoResource::collection($results);
    }

    public function obtenerTodosCiertasColumnas($campos)
    {
        // Log::channel('testing')->info('Log', ['Campos #2: ', $campos]);
        $results = Empleado::ignoreRequest(['campos'])->filter()->where('id', '<>', 1)->get($campos);
        // $results = Empleado::ignoreRequest(['campos'])->filter()->where('id', '<>', 1)->get($campos);
        // return EmpleadoResource::collection($results);
        return $results;
    }

    public function obtenerTodosSinEstado()
    {
        $results = Empleado::ignoreRequest(['rol', 'campos'])->filter()->where('id', '<>', 1)->get();
        return EmpleadoResource::collection($results);
    }

    /**
     * Listar a los tecnicos filtrados por id de grupo
     */
    public function obtenerTecnicosPorGrupo(int $grupo)
    {
        return EmpleadoResource::collection(Empleado::where('grupo_id', $grupo)->where('estado', true)->get());
    }

    public function search(string $search)
    {
        return EmpleadoResource::collection(Empleado::search($search)->where('estado', true)->get());
    }
}
