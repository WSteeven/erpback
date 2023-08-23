<?php

namespace App\Http\Requests\RecursosHumanos\NominaPrestamos;

use Illuminate\Foundation\Http\FormRequest;

class RolPagoMesRequest extends FormRequest
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
            'mes' => 'required',
            'nombre' => 'required | unique:rol_pago_mes,nombre',
            'es_quincena' => 'nullable'
        ];
    }
    public function messages(): array
{
    return [
        'required | unique:rol_pago_mes,nombre' => 'Rol de Pagos ya existe porfavor ingrese uno diferente',
    ];
}
}
