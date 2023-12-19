<?php

namespace App\Http\Requests\Ventas;

use Illuminate\Foundation\Http\FormRequest;

class VendedorRequest extends FormRequest
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
            'codigo_vendedor'=> 'required',
            'empleado_id'=> 'required|integer',
            'modalidad_id'=> 'required|integer',
            'tipo_vendedor'=> 'required',
            'jefe_inmediato' => 'required|integer',
        ];
    }
    protected function prepareForValidation()
    {
        $this->merge([
            'empleado_id'=> $this->empleado,
            'modalidad_id'=> $this->modalidad
        ]);
    }
}
