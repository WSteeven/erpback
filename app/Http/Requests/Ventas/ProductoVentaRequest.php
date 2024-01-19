<?php

namespace App\Http\Requests\Ventas;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductoVentaRequest extends FormRequest
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
        $rules = [
            'bundle_id'=> 'required|unique:ventas_productos_ventas,bundle_id',
            'precio'=> 'required',
            'plan_id'=> 'required|integer',
            'activo'=> 'boolean',
        ];


        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $producto = $this->route()->parameter('producto');

            $rules['bundle_id'] = ['required',  Rule::unique('ventas_productos_ventas')->ignore($producto)];
        }

        return $rules;
    }
    protected function prepareForValidation()
    {
        $this->merge([
            'bundle_id' => $this->bundle,
            'plan_id'=> $this->plan
        ]);
    }
}
