<?php

namespace App\Http\Requests\Medico;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class CitaMedicaRequest extends FormRequest
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
            'sintomas' => 'required|string',
            'razon' => 'required|string',
            'observacion' => 'required|string',
            'fecha_hora_cita' => 'required|date_format:Y-m-d H:i:s',
            'estado_cita_medica_id' => 'required|exists:med_estados_citas_medicas,id',
            'configuracion_examen_categoria_id' => 'required|exists:empleados,id',
        ];
    }
    protected function prepareForValidation()
    {
            $this->merge([
                'fecha_hora_cita' => Carbon::parse($this->fecha_hora_cita)->format('Y-m-d H:i:s'),
                'estado_cita_medica_id' => $this->estado_cita_medica,
                'configuracion_examen_categoria_id' => $this->configuracion_examen_categoria
            ]);
    }
}
