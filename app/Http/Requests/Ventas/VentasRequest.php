<?php

namespace App\Http\Requests\Ventas;

use Illuminate\Foundation\Http\FormRequest;

class VentasRequest extends FormRequest
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

            'orden_id' => 'required',
            'orden_interna' => 'required',
            'vendedor_id' => 'required',
            'producto_id' => 'required',
            'fecha_activ' => 'required',
            'estado_activ' => 'required',
            'forma_pago' => 'required',
            'comision_id' => 'required',
            'chargeback' => 'required',
            'comision_vendedor' => 'required|decimal',

        ];
    }
    protected function prepareForValidation()
    {
        $this->merge([
            'vendedor_id' => $this->vendedor,
            'producto_id' => $this->producto,
            'comision_id' => $this->comision,
        ]);
    }
}
