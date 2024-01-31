<?php

namespace App\Http\Requests\Medico;

use Carbon\Carbon;
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
            'esatblecimiento_salud' => 'required|string',
            'numero_historia_clinica' => 'required|string',
            'numero_archivo' => 'required|string',
            'puesto_trabajo' => 'required|string',
            'religion_id' => 'required|exists:med_religiones,id',
            'orientacion_sexual_id' => 'required|exists:med_orientaciones_sexuales,id',
            'identidad_genero_id' => 'required|exists:med_identidades_generos,id',
            'actividades_relevantes_puesto_trabajo_ocupar' => 'required|string',
            'motivo_consulta' => 'required|string',
            'empleado_id' => 'required|exists:empleados,id',
            'actividad_fisica' => 'required|string',
            'enfermedad_actual' => 'required|string',
            'recomendaciones_tratamiento' => 'required|string',
            'descripcion_examen_fisico_regional' => 'required|string',
            'descripcion_revision_organos_sistemas' => 'required|string',
            'antecedentes_quirorgicos' => 'required|string',
            'vida_sexual_activa' => 'required|boolean',
            'tiene_metodo_planificacion_familiar' => 'required|boolean',
            'tipo_metodo_planificacion_familiar' => 'required|string',
            'menarquia' => 'required|date_format:Y-m-d',
            'ciclos' => 'required|integer',
            'fecha_ultima_menstruacion' => 'required|date_format:Y-m-d',
            'gestas' => 'required|integer',
            'partos' => 'required|integer',
            'cesareas' => 'required|integer',
            'abortos' => 'required|integer',
            'hijos_vivos' => 'required|integer',
            'hijos_muertos' => 'required|integer',
            'calificado_iess' => 'required|boolean',
            'descripcion' => 'required|string',
            'fecha' => 'required|date_format:Y-m-d',
            'observacion' => 'required|string',
            'tipo_descripcion_antecedente_trabajo' => 'required|string',
            'presion_arterial' => 'required|string',
            'temperatura' => 'required|decimal:2',
            'frecuencia_cardiaca' => 'required|decimal:2',
            'saturacion_oxigeno' => 'required|decimal:2',
            'frecuencia_respiratoria' => 'required|decimal:2',
            'peso' => 'required|decimal:2',
            'estatura' => 'required|decimal:2',
            'talla' => 'required|decimal:2',
            'indice_masa_corporal' => 'required|decimal:2',
            'perimetro_abdominal' => 'required|decimal:2',
        ];
    }
    protected function prepareForValidation()
    {
        $this->merge([
            'religion_id' => $this->religion,
            'orientacion_sexual_id' => $this->orientacion_sexual,
            'identidad_genero_id' => $this->identidad_genero,
            'empleado_id' => $this->empleado,
            'fecha_ultima_menstruacion' => Carbon::parse($this->fecha_ultima_menstruacion)->format('Y-m-d'),
            'fecha' => Carbon::parse($this->fecha_ultima)->format('Y-m-d'),
            'menarquia' => Carbon::parse($this->menarquia)->format('Y-m-d')
        ]);
    }
}
