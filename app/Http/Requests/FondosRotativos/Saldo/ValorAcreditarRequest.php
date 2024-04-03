<?php

namespace App\Http\Requests\FondosRotativos\Saldo;

use App\Models\FondosRotativos\Saldo\ValorAcreditar;
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
            'motivo' => 'nullable',
            'estado' => 'required',
        ];
    }
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $valor_acreditar = ValorAcreditar::where('acreditacion_semana_id', $this->acreditacion_semana_id)->where('empleado_id', $this->empleado_id)->first();
            if ($valor_acreditar) {
                $validator->errors()->add('empleado', 'Empleado ya  esta registrado');
            }
        });
    }
    protected function prepareForValidation()
    {
        $this->merge([
            'empleado_id' => $this->empleado,
            'acreditacion_semana_id' => $this->acreditacion_semana,
        ]);
    }
}
