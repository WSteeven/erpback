<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmpleadoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $controller_method = $request->route()->getActionMethod();
        $modelo = [
            'id' => $this->id,
            'identificacion' => $this->identificacion,
            'nombres' => $this->nombres,
            'apellidos' => $this->apellidos,
            'telefono' => $this->telefono,
            'fecha_nacimiento' => $this->fecha_nacimiento,
            'email' => $this->user ? $this->user->email : '',
            // 'password'=>bcrypt($this->user->password),
            'usuario' => $this->user->name,
            'jefe' => $this->jefe ? $this->jefe->nombres . ' ' . $this->jefe->apellidos : 'N/A',
            'canton' => $this->canton ? $this->canton->canton : 'NO TIENE',
            'estado' => $this->estado, //?Empleado::ACTIVO:Empleado::INACTIVO,
            'cargo' => $this->cargo?->nombre,
            'departamento' => $this->departamento?->nombre,
            'grupo' => $this->grupo?->nombre,
            'grupo_id' => $this->grupo?->nombre,
            'roles' => implode(', ', $this->user->getRoleNames()->filter(fn ($rol) => $rol !== 'EMPLEADO')->toArray()),
            // 'roles' => $this->user->getRoleNames()->filter(fn ($rol) => $rol !== 'EMPLEADO')->toArray(),
            'permisos' => $this->user->getAllPermissions(),
            'cargo' => $this->cargo?->nombre,
            'firma_url' => $this->firma_url ? url($this->firma_url) : null,
            'foto_url' => $this->foto_url ? url($this->foto_url) : null,
            // 'es_responsable_grupo' => $this->es_responsable_grupo,
            // 'es_lider' => $this->esTecnicoLider(),
            'convencional' => $this->convencional ? $this->convencional : null,
            'telefono_empresa' => $this->telefono_empresa ? $this->telefono_empresa : null,
            'extension' => $this->extension ? $this->extension : null,
            'coordenadas' => $this->coordenadas ? $this->coordenadas : null,
            'casa_propia' => $this->casa_propia,
            'vive_con_discapacitados' => $this->vive_con_discapacitados,
            'responsable_discapacitados' => $this->responsable_discapacitados,
            //nuevos campos
            'correo_personal' => $this->correo_personal,
            'tipo_sangre' => $this->tipo_sangre,
            'direccion' => $this->direccion,
            'supa' => $this->supa,
            'salario' => $this->salario,
            'num_cuenta' => $this->num_cuenta_bancaria,
            'banco' => $this->banco,
            'banco_info' => $this->banco_info ? $this->banco_info->nombre : null,
            'tiene_discapacidad' => $this->tiene_discapacidad,
            'fecha_ingreso' => $this->fecha_ingreso,
            'fecha_salida' => $this->fecha_salida,
            'talla_zapato' => $this->talla_zapato,
            'talla_camisa' => $this->talla_camisa,
            'talla_guantes' => $this->talla_guantes,
            'nivel_academico' => $this->nivel_academico,
            'estado_civil' => $this->estado_civil_id,
            'estado_civil_info' => $this->estadoCivil  ? $this->estadoCivil->nombre : null,
            'area' =>  $this->area_id,
            'area_info' =>  $this->area ? $this->area->nombre : null,
            'tipo_contrato' => $this->tipo_contrato_id,
            'tipo_contrato_info' => $this->tipoContrato ? $this->tipoContrato->nombre : null,
            'observacion' => $this->observacion,
            'genero' => $this->genero

        ];

        if ($controller_method == 'show') {
            $modelo['jefe'] = $this->jefe_id;
            $modelo['usuario'] = $this->user->name;
            $modelo['canton'] = $this->canton_id;
            $modelo['roles'] = $this->user->getRoleNames();
            $modelo['grupo'] = $this->grupo_id;
            $modelo['cargo'] = $this->cargo_id;
            $modelo['departamento'] = $this->departamento_id;
        }

        return $modelo;
    }
}
