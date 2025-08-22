<?php

namespace App\Http\Requests\Ventas;

use Illuminate\Foundation\Http\FormRequest;

class BaseComisionRequest extends FormRequest
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
            'modalidad_id' => 'required|exists:ventas_modalidades,id',
            'presupuesto_ventas' => 'required|numeric',
            'bono_comision_semanal' => 'required|numeric',
            'comisiones' => 'required|array',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'modalidad_id' => $this->modalidad
        ]);
    }
}
