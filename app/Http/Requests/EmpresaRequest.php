<?php

namespace App\Http\Requests;

use App\Models\Empresa;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;
use Src\Shared\ValidarIdentificacion;

class EmpresaRequest extends FormRequest
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
        $rules =  [
            'identificacion' => 'required|min:10|max:13|unique:empresas,identificacion',
            'tipo_contribuyente' => ['required', Rule::in([Empresa::NATURAL, Empresa::PRIVADA, Empresa::PUBLICA])],
            'razon_social' => 'string|required',
            'nombre_comercial' => 'string|nullable',
            'correo' => 'email|nullable',
            'direccion' => 'string|nullable',
        ];
        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $empresa = $this->route()->parameter('empresa');

            $rules['identificacion'] = ['required', 'string', Rule::unique('empresas')->ignore($empresa)];
        }
        return $rules;
    }
    /**
     * Configure the validator instance.
     *
     * @param \Illuminate\Validation\Validator $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if(substr_count($this->identificacion,'9')<9){
                $validador = new ValidarIdentificacion();
                $existeRUC = Http::get('https://srienlinea.sri.gob.ec/sri-catastro-sujeto-servicio-internet/rest/ConsolidadoContribuyente/existePorNumeroRuc?numeroRuc='.$this->identificacion);
                if(!(($validador->validarCedula($this->identificacion))||($existeRUC->body()=='true'))){
                    $validator->errors()->add('identificacion', 'La identificación no pudo ser validada, revisa que sea una cédula/RUC válido');
                }
            }
        });
    }

    /**
     * Personalizacion de atributos y mensajes
     */
    public function attributes()
    {
        return [
            //'identificacion'=>'cedula/ruc',
            'tipo_contribuyente' => 'contribuyente'
        ];
    }
    public function messages()
    {
        return [
            'identificacion.required' => 'Debe ingresar una identificación de cedula o ruc',
            'tipo_contribuyente.in' => 'El campo :attribute solo acepta uno de los siguientes valores: NATURAL, PRIVADA, PUBLICA'
        ];
    }
}
