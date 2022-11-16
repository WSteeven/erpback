<?php

namespace Src\App;

use App\Http\Resources\EmpleadoResource;
use App\Models\Empleado;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class EmpleadoService
{
    public function __construct()
    {
    }

    public function obtenerEmpleadosPorRol(string $rol)
    {
        $users_ids = User::select('id')->role($rol)->get()->map(fn ($id) => $id->id)->toArray();
        $empleados = Empleado::ignoreRequest(['rol'])->filter()->get();
        $results = $empleados->filter(fn ($empleado) => in_array($empleado->usuario_id, $users_ids))->flatten();
        return EmpleadoResource::collection($results);
    }

    public function obtenerPaginacion($offset)
    {
        $results = Empleado::where('id', '<>', 1)->simplePaginate($offset);
        return EmpleadoResource::collection($results);
    }

    public function obtenerTodos()
    {
        $results = Empleado::ignoreRequest(['rol'])->filter()->where('id', '<>', 1)->get();
        return EmpleadoResource::collection($results);
    }

    /**
     * Listar a los tecnicos filtrados por id de grupo
     */
    public function obtenerTecnicosPorGrupo(int $grupo)
    {
        return EmpleadoResource::collection(Empleado::where('grupo_id', $grupo)->get());
    }

    public function search(string $search)
    {
        return Empleado::search($search)->get();
    }
}
