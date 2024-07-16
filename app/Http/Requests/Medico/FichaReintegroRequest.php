<?php

namespace App\Http\Requests\Medico;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class FichaReintegroRequest extends FormRequest
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
            'fecha_ultimo_dia_laboral' => 'required|string',
            'fecha_reingreso' => 'required|string',
            'causa_salida' => 'required|string',
            'motivo_consulta' => 'nullable|string',
            'enfermedad_actual' => 'nullable|string',
            'observacion_examen_fisico_regional' => 'nullable|string',
            'registro_empleado_examen_id' => 'required|exists:med_registros_empleados_examenes,id',
            'cargo_id' => 'required|exists:cargos,id',
            // Constante vital
            'constante_vital.presion_arterial' => 'required|string',
            'constante_vital.temperatura' => 'required|numeric',
            'constante_vital.frecuencia_cardiaca' => 'required|numeric',
            'constante_vital.saturacion_oxigeno' => 'required|numeric',
            'constante_vital.frecuencia_respiratoria' => 'required|numeric',
            'constante_vital.peso' => 'required|numeric',
            'constante_vital.talla' => 'required|numeric',
            'constante_vital.indice_masa_corporal' => 'required|numeric',
            'constante_vital.perimetro_abdominal' => 'required|numeric',
            // Examenes fisicos regionales
            'examenesFisicosRegionales' => 'sometimes|nullable|array',
            'examenesFisicosRegionales.*.categoria_examen_fisico_id' => 'required|exists:med_categorias_examenes_fisicos,id',
            'examenesFisicosRegionales.*.observacion' => 'required|string',
            // Aptitud medica
            'aptitud_medica.tipo_aptitud_id' => 'required|numeric|integer',
            'aptitud_medica.observacion' => 'nullable|string',
            'aptitud_medica.limitacion' => 'nullable|string',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'registro_empleado_examen_id' => $this->registro_empleado_examen,
            'profesional_salud_id' => Auth::user()->empleado->id,
            'cargo_id' => $this->cargo,
        ]);
    }
}
