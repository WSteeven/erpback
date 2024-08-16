<?php

namespace App\Http\Resources\RecursosHumanos\SeleccionContratacion;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Src\Shared\Utils;

class PostulacionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        // La variable $persona hace referencia al modelo Empleado o a Postulante, y tiene el valor de sus respectivas asignaciones
        $persona = $this->user_type === User::class ? $this->user?->empleado : $this->user?->persona;

        $controller_method = $request->route()->getActionMethod();
        $modelo = [
            'id' => $this->id,
            'vacante' => new VacanteResource($this->vacante),
            'usuario' => $this->user,
            'persona' => $persona,
            'imagen_referencia' => $this->vacante ? url($this->vacante->imagen_referencia) : null,
            'nombre' => $this->vacante?->nombre,
            'user_id' => $this->user_id,
            'user_type' => $this->user_type,
            'nombres' => $persona?->nombres,
            'nombres_apellidos' => $persona?->nombres . ' ' . $persona?->apellidos,
            'apellidos' => $persona?->apellidos,
            'identificacion' => $persona->numero_documento_identificacion?? $persona->identificacion,
            'tipo_identificacion' => $persona->tipo_documento_identificacion ?? 'CEDULA',
            'telefono' => $persona->telefono,
            'correo_personal' => $persona->correo_personal,
            'genero' => $persona->genero,
            'identidad_genero' => $persona->identidad_genero_id,
            'pais' => $persona->pais_id?? $persona->canton->provincia->pais_id,
            'direccion' => $this->direccion,
            'mi_experiencia' => $this->mi_experiencia,
            'pais_residencia' => $this->pais_residencia_id,
            'fecha_nacimiento' => $persona->fecha_nacimiento,
            'tengo_documentos_regla' => $this->tengo_documentos_regla,
            'tengo_formacion_academica_requerida' => $this->tengo_formacion_academica_requerida,
            'tengo_conocimientos_requeridos' => $this->tengo_conocimientos_requeridos,
            'tengo_experiencia_requerida' => $this->tengo_experiencia_requerida,
            'tengo_disponibilidad_viajar' => $this->tengo_disponibilidad_viajar,
            'tengo_licencia_conducir' => $this->tengo_licencia_conducir,
            'tipo_licencia' => $this->tipo_licencia ? Utils::convertirStringComasArray($this->tipo_licencia) : null,
        ];

        if ($controller_method == 'show') {
            $modelo['vacante'] = $this->vacante_id;
        }



        return $modelo;
    }
}
