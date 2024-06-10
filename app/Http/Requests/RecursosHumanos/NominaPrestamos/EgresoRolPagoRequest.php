<?php

namespace App\Http\Requests\RecursosHumanos\NominaPrestamos;

use Illuminate\Foundation\Http\FormRequest;

class EgresoRolPagoRequest extends FormRequest
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
            'descuento_id' => 'required',
            'empleado_id' => 'required',
            'monto' => 'required',
            'tipo' => 'required',
            'id_rol_pago' => 'required',
        ];
    }

    protected function prepareForValidation()
    {
        $empleado_id = $this->empleado;
        $descuento_id = $this->id_descuento;
        $this->merge([
            'empleado_id' => $empleado_id,
            'descuento_id' => $descuento_id,
        ]);
    }

}
