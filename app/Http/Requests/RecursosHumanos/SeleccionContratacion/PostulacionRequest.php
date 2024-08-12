<?php

namespace App\Http\Requests\RecursosHumanos\SeleccionContratacion;

use Illuminate\Foundation\Http\FormRequest;
use Src\Shared\Utils;

class PostulacionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'vacante_id' => 'required|exists:rrhh_contratacion_vacantes,id',
            'postulante' => 'required|numeric',
            'tipo_postulante' => 'required|string',
            'nombres' => 'required|string',
            'apellidos' => 'required|string',
            'identificacion' => 'required|string',
            'tipo_identificacion' => 'required|string',
            'telefono' => 'required|string',
            'correo_personal' => 'required|string',
            'genero' => 'required|string',
            'identidad_genero_id' => 'required|exists:med_identidades_generos,id',
            'pais_id' => 'required|exists:paises,id',
            'direccion' => 'required|string',
            'mi_experiencia' => 'required|string',
            'pais_residencia_id' => 'required|exists:paises,id',
            'fecha_nacimiento' => 'required|string',
            'tengo_documentos_regla' => 'boolean',
            'tengo_formacion_academica_requerida' => 'boolean',
            'tengo_conocimientos_requeridos' => 'boolean',
            'tengo_experiencia_requerida' => 'boolean',
            'tengo_disponibilidad_viajar' => 'boolean',
            'tengo_licencia_conducir' => 'boolean',
            'tipo_licencia' => 'sometimes|nullable|string',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'tipo_licencia' => Utils::convertArrayToString($this->tipo_licencia, ','),
            'vacante_id' => $this->vacante,
            'pais_id' => $this->pais,
            'identidad_genero_id' => $this->identidad_genero,
            'pais_residencia_id' => $this->pais_residencia,
        ]);
    }
}
