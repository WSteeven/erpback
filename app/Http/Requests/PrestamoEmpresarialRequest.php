<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PrestamoEmpresarialRequest extends FormRequest
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
            'fecha' => 'required|date_format:Y-m-d',
            'monto' => 'required|numeric',
            'utilidad' => 'required|date_format:Y',
            'valor_utilidad' => 'required|numeric',
            'id_forma_pago' => 'required|numeric',
            'plazo' => 'required|string',
            'estado' => 'required|string',
        ];
    }
    protected function prepareForValidation()
    {
        $this->merge([
            'id_forma_pago' => $this->forma_pago,
            'estado' => 'ACTIVO'
        ]);
    }
}
