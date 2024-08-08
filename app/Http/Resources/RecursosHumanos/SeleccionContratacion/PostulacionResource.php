<?php

namespace App\Http\Resources\RecursosHumanos\SeleccionContratacion;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
        return parent::toArray($request);

        // $controller_method = $request->route()->getActionMethod();
        // $modelo = [
        //     'id' => $this->id,
        //     'vacante' => $this->vacante->nombre,
        //     'postulante' => $this->user_id,
        //     'tipo_postulante' => $this->user_type,
        //     'nombres' => $this->nombres,
        //     'apellidos' => $this->apellidos,
        //     'identificacion' => $this->identificacion,
        //     'tipo_identificacion' => $this->tipo_identificacion,
        //     'telefono' => $this->telefono,
        //     'correo_personal' => $this->correo_personal,
        //     'genero' => $this->genero,
        //     'identidad_genero' => $this->identidad_genero,
        //     'pais' => $this->pais,
        //     'direccion' => $this->direccion,
        //     'mi_experiencia' => $this->mi_experiencia,
        //     'pais_residencia' => $this->pais_residencia,
        //     'fecha_nacimiento' => $this->fecha_nacimiento,
        //     'tengo_documentos_regla' => $this->tengo_documentos_regla,
        //     'tengo_formacion_academica_requerida' => $this->tengo_formacion_academica_requerida,
        //     'tengo_conocimientos_requeridos' => $this->tengo_conocimientos_requeridos,
        //     'tengo_experiencia_requerida' => $this->tengo_experiencia_requerida,
        //     'tengo_disponibilidad_viajar' => $this->tengo_disponibilidad_viajar,
        //     'tengo_licencia_conducir' => $this->tengo_licencia_conducir,
        //     'tipo_licencia' => $this->tipo_licencia,
        // ];

        // if ($controller_method == 'show') {
        //     $modelo['vacante'] = $this->vacante_id;
        // }



        // return $modelo;
    }
}
