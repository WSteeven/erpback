<?php

namespace App\Http\Requests\ComprasProveedores;

use Illuminate\Foundation\Http\FormRequest;

class GeneradorCashRequest extends FormRequest
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
            'titulo' => 'required|string',
            'pagos.*.id' => 'nullable|numeric|integer',
            'pagos.*.tipo' => 'required|in:PA,CO',
            'pagos.*.num_cuenta_empresa' => 'required|string',
            'pagos' => 'required',
            'pagos.*.num_comprobante' => 'nullable|string',
            'pagos.*.moneda' => 'required|string',
            'pagos.*.valor' => 'required|string',
            'pagos.*.forma_pago' => 'required|in:CTA,CHQ,EFE',
            'pagos.*.referencia' => 'required|string',
            'pagos.*.referencia_adicional' => 'nullable|string',
            'pagos.*.beneficiario_id' => 'required|numeric|integer|exists:cmp_beneficiarios,id',
            'pagos.*.cuenta_banco_id' => 'required|numeric|integer|exists:cmp_cuentas_bancarias,id',
        ];
    }

    public function messages()
    {
        return [
            'pagos.required' => 'Es necesario que registre al menos un pago.',
            'pagos.*.cuenta_banco_id.exists' => 'La cuenta bancaria seleccionada en la fila :index no existe.',
        ];
    }
}
