<?php

namespace App\Http\Requests;

use App\Models\CodigoCliente;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class CodigoClienteRequest extends FormRequest
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
            'codigo'=>'required|string|unique:codigo_cliente,codigo',
            'producto'=>'required|exists:productos,id',
            'cliente'=>'required|exists:clientes,id',
            'nombre_cliente'=>'sometimes|string|nullable',
        ];
        if(in_array($this->method(), ['PUT', 'PATCH'])){
            $codigo = $this->route()->parameter('codigo');

            $rules['codigo'] = ['required', 'string', Rule::unique('codigo_cliente')->ignore($codigo)];
        }

        return $rules;
    }

    public function withValidator($validator){
        $validator->after(function($validator){
            if(!in_array($this->method(), ['PUT', 'PATCH'])){
                $codigo_cliente = CodigoCliente::where('cliente_id',$this->cliente)->where('producto_id',$this->producto)->first();
                Log::channel('testing')->info('Log', ['codigo encontrado:', $codigo_cliente]);
                if($codigo_cliente){
                    $validator->errors()->add('cliente', 'Este cliente ya ha registrado previamente un codigo para el producto seleccionado');
                }
            }
        });
    }
}
