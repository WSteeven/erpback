<?php

namespace App\Http\Requests\RecursosHumanos\NominaPrestamos;

use Illuminate\Foundation\Http\FormRequest;

class PlanVacacionRequest extends FormRequest
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
            'periodo_id'=>'required|exists:periodos,id',
            'empleado_id'=>'required|exists:empleados,id',
            'rangos'=>'required|integer|between:1,2',
            'fecha_inicio'=>'required_if:rangos,1|nullable',
            'fecha_fin'=>'required_if:rangos,1|nullable',
            'fecha_inicio_primer_rango'=>'required_if:rangos,2|nullable',
            'fecha_fin_primer_rango'=>'required_if:rangos,2|nullable',
            'fecha_inicio_segundo_rango'=>'required_if:rangos,2|nullable',
            'fecha_fin_segundo_rango'=>'required_if:rangos,2|nullable',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'periodo_id' => $this->periodo,
            'empleado_id' => $this->empleado,
        ]);
        if ($this->rangos == 1) {
            $this->merge([
                'fecha_inicio_primer_rango' => null,
                'fecha_fin_primer_rango' => null,
                'fecha_inicio_segundo_rango' => null,
                'fecha_fin_segundo_rango' => null,
            ]);
        }else{
            $this->merge([
                'fecha_inicio' =>null,
                'fecha_fin'=>null,
            ]);
        }
    }
}
