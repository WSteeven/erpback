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
            'empresa' =>     'exists:empresas,id|required|unique:clientes,empresa_id,NULL,id,parroquia_id,' . $this->parroquia_id,
            'parroquia' =>   'exists:parroquias,id|required|unique:clientes,empresa_id',//NULL, empresa_id' . $this->empresa_id,
            'requiere_bodega' => 'boolean',
            'requiere_fr' => 'boolean',
            'estado' => 'boolean',
            'logo_url' => 'nullable|string',
        ];
        /* if(in_array($this->method(), ['PUT', 'PATCH'])){
            $cliente = $this->route()->parameter('cliente');

            $rules['empresa_id']=['exists:empresas,id|required',Rule::unique('empresas')->ignore($cliente)];
        } */
        return $rules;
    }
    /* public function messages()
    {
        return [
            'empresa_id.unique' => 'Ya se encuentra un cliente registrado con el valor de :attribute',
            'parroquia_id.unique' => 'Ya se encuentra un cliente registrado en la parroquia con el valor de :attribute'
        ];
    } */
}
