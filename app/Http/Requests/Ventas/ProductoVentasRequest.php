<?php

namespace App\Http\Requests\Ventas;

use Illuminate\Foundation\Http\FormRequest;

class ProductoVentasRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'bundle_id'=> 'required',
            'precio'=> 'required|decimal',
            'plan_id'=> 'required|integer',
        ];
    }
    protected function prepareForValidation()
    {
        $this->merge([
            'plan_id'=> $this->plan
        ]);
    }
}
