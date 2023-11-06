<?php

namespace App\Http\Requests\Ventas;

use Illuminate\Foundation\Http\FormRequest;

class BonoTrimestralCumplimientoRequest extends FormRequest
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
            'vendedor_id'=> 'required|integer',
            'cant_ventas'=> 'required|integer',
            'trimestre' => 'required',
            'valor' => 'required'
        ];
    }
    protected function prepareForValidation()
    {
        $this->merge([
            'vendedor_id' => $this->vendedor,
        ]);
    }
}
