<?php

namespace App\Http\Resources;

use App\Models\Departamento;
use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserInfoResource extends JsonResource
{
    public static $wrap = null;

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        $empleado = $this->empleado;

        return [
            'id' => $this->empleado != null ? $this->empleado->id : 0,
            'usuario' => $this->empleado != null ? Empleado::extraerNombresApellidos($empleado) : '',
            'nombres' => $this->empleado != null ? $empleado->nombres : '',
            'apellidos' => $this->empleado != null ? $empleado->apellidos : '',
            'email' => $this->email,
            'identificacion' => $this->empleado != null ? $empleado->identificacion : '',
            'telefono' => $this->empleado != null ? $empleado->telefono : '',
            'fecha_ingreso' => $this->empleado != null ? $empleado->fecha_ingreso : '',
            'fecha_nacimiento' => $this->empleado != null ? $empleado->fecha_nacimiento : '',
            'jefe_id' => $this->empleado != null ? $empleado->jefe_id : 0,
            'jefe_inmediato' => $empleado->jefe ? Empleado::extraerNombresApellidos($empleado->jefe) : null,
            'usuario_id' => $this->id,
            'sucursal_id' => $this->empleado != null ? $empleado->sucursal_id : '',
            'grupo_id' => $this->empleado != null ? $empleado->grupo_id : 0,
            'grupo' => $this->empleado != null ? $empleado->grupo?->nombre : '',
            'roles' => $this->getRoleNames(), // ->toArray()),
            'estado' => $this->empleado != null ? $empleado->estado : false,
            'es_lider' => $this->esTecnicoLider(),
            'permisos' => $this->getAllPermissions()->pluck('name')->toArray(),
            'cargo' => $this->empleado != null ? $empleado->cargo?->nombre : '',
            'departamento' => $this->empleado ? $empleado->departamento_id : null,
            'es_responsable_departamento' => Departamento::where('responsable_id', $empleado->id)->exists(),
            'foto_url' => $empleado->foto_url ? url($empleado->foto_url) : url('/storage/sinfoto.png'),
            'nombre_canton' => $empleado->canton?->canton,
            'tipo_sangre' => $empleado->tipo_sangre,
            'area_info' => $empleado->area?->nombre,
            'nombre_cargo' => $empleado->cargo?->nombre,
            'genero' => $empleado->genero,
            'edad' => Empleado::obtenerEdad($empleado),
        ];
    }
}
