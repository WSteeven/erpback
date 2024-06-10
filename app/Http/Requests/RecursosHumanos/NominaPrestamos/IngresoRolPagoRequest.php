<?php

namespace App\Http\Requests\RecursosHumanos\NominaPrestamos;

use Illuminate\Foundation\Http\FormRequest;

class IngresoRolPagoRequest extends FormRequest
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
                'id_rol_pago' => 'required',
                'concepto' => 'required',
                'monto' => 'required',
            ];

    }
}
