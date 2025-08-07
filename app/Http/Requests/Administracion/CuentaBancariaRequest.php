<?php

namespace App\Http\Requests\Administracion;

use Illuminate\Foundation\Http\FormRequest;

class CuentaBancariaRequest extends FormRequest
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
            'es_principal'=> 'boolean',
            'banco_id'=> 'required|exists:bancos,id',
            'tipo_cuenta'=> 'required|string|in:CTE,AHO,PLA,INV',
            'numero_cuenta'=> 'required|string',
            'observacion'=> 'nullable|string',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'banco_id' => $this->banco,
        ]);
    }
}
