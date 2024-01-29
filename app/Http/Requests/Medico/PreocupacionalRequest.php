<?php

namespace App\Http\Requests\Medico;

use Illuminate\Foundation\Http\FormRequest;

class PreocupacionalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'ciu' => 'required|string',
            'esatblecimiento_salud'=> 'required|string',
            'numero_historia_clinica'=> 'required|string',
            'numero_archivo'=> 'required|string',
            'puesto_trabajo'=> 'required|string',
            'religion_id'=> 'required|exists:med_religiones,id',
            'orientacion_sexual_id'=> 'required|exists:med_orientaciones_sexuales,id',
            'identidad_genero_id'=> 'required|exists:med_identidades_generos,id',
            'actividades_relevantes_puesto_trabajo_ocupar'=> 'required|string',
            'motivo_consulta'=> 'required|string',
            'empleado_id'=> 'required|exists:empleados,id',
            'actividad_fisica'=> 'required|string',
            'enfermedad_actual'=> 'required|string',
            'recomendaciones_tratamiento'=> 'required|string',
            'descripcion_examen_fisico_regional'=> 'required|string',
            'descripcion_revision_organos_sistemas'=> 'required|string',
        ];
    }
    protected function prepareForValidation()
    {
            $this->merge([
                'religion_id' => $this->religion,
                'orientacion_sexual_id' => $this->orientacion_sexual,
                'identidad_genero_id' => $this->identidad_genero,
                'empleado_id'=>$this->empleado

            ]);
    }
}
