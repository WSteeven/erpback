<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClienteRequest extends FormRequest
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
            'empresa_id' => 'exists:empresas,id|required|unique:clientes,empresa_id',
            'parroquia_id' => 'required|exists:parroquias,id',
            'requiere_bodega' => 'boolean',
            'estado'=>'boolean'
        ];
        if(in_array($this->method(), ['PUT', 'PATCH'])){
            $cliente = $this->route()->parameter('cliente');

            $rules['empresa_id']=['exists:empresas,id|required',Rule::unique('empresas')->ignore($cliente)];
        }
        return $rules;
    }
    public function attributes()
    {
        return [
            'empresa_id'=>'empresa_id',
            'parroquia_id'=>'parroquia_id'
        ];
    }
}
