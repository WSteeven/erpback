<?php

namespace App\Http\Requests\FondosRotativos\Saldo;

use Illuminate\Foundation\Http\FormRequest;

class ValorAcreditarRequest extends FormRequest
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
            'empleado_id' => 'required|exists:empleados,id',
            'acreditacion_semana_id' => 'required|exists:fr_acreditacion_semana,id',
            'monto_generado' => 'required',
            'monto_modificado' => 'required',
        ];
    }
    protected function prepareForValidation()
    {
        $this->merge([
            'empleado_id' =>$this->empleado,
            'acreditacion_semana_id' => $this->acreditacion_semana,
        ]);
    }
}
