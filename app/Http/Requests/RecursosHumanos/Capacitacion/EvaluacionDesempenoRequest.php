<?php

namespace App\Http\Requests\RecursosHumanos\Capacitacion;

use App\Models\RecursosHumanos\Capacitacion\EvaluacionDesempeno;
use Illuminate\Foundation\Http\FormRequest;

class EvaluacionDesempenoRequest extends FormRequest
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
            'evaluado_id' => 'required|exists:empleados,id',
            'evaluador_id' => 'required|exists:empleados,id',
            'calificacion' => 'required|numeric',
            'formulario_id' => 'required|exists:rrhh_cap_formularios,id',
            'respuestas' => 'required|array',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $controller_method = $this->route()->getActionMethod();
            if ($controller_method == 'store') {
                if (EvaluacionDesempeno::where('evaluado_id', $this->evaluado_id)->exists())
                    $validator->errors()->add('evaluado_id', 'Este empleado ya ha sido evaluado previamente, no puede registrarse nuevamente la evaluación para el mismo empleado.');
            }
        });
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'evaluado_id' => $this->evaluado,
            'evaluador_id' => $this->evaluador,
            'formulario_id' => $this->formulario,
        ]);
    }
}
