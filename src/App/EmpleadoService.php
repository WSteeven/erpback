<?php

namespace Src\App;

use App\Http\Resources\EmpleadoResource;
use App\Models\Empleado;
use App\Models\User;

class EmpleadoService
{
    public function __construct()
    {
    }

    public function obtenerEmpleadosPorRol(string $rol)
    {
        $users_ids = User::select('id')->where('estado', Empleado::ACTIVO)->role($rol)->get()->map(fn ($id) => $id->id)->toArray();
        $empleados = Empleado::ignoreRequest(['rol'])->filter()->where('estado', Empleado::ACTIVO)->get();
        $results = $empleados->filter(fn ($empleado) => in_array($empleado->usuario_id, $users_ids))->flatten();
        EmpleadoResource::collection($results);
        return $results;
    }

    public function obtenerPaginacion($offset)
    {
        $results = Empleado::where('id', '<>', 1)->where('estado', Empleado::ACTIVO)->simplePaginate($offset);
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
        $results = Empleado::ignoreRequest(['rol'])->filter()->where('id', '<>', 1)->where('estado', Empleado::ACTIVO)->get();
        return EmpleadoResource::collection($results);
    }

    public function obtenerTodosSinEstado()
    {
        $results = Empleado::ignoreRequest(['rol'])->filter()->where('id', '<>', 1)->get();
        return EmpleadoResource::collection($results);
    }

    /**
     * Listar a los tecnicos filtrados por id de grupo
     */
    public function obtenerTecnicosPorGrupo(int $grupo)
    {
        return EmpleadoResource::collection(Empleado::where('grupo_id', $grupo)->where('estado', Empleado::ACTIVO)->get());
    }

    public function search(string $search)
    {
        return Empleado::search($search)->where('estado', Empleado::ACTIVO)->get();
    }
}
