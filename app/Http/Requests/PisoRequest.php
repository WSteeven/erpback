<?php

namespace App\Http\Requests;

use App\Models\Piso;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class PisoRequest extends FormRequest
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
            'fila' => 'unique:pisos,fila,NULL,id,columna,' . $this->columna,
            'columna' => 'sometimes|required|string|unique:pisos,fila'
        ];
    }
    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if(!$this->columna){
                $piso = Piso::where('fila', $this->fila)->where('columna', null)->first();
                   
                if ($piso) {
                    $validator->errors()->add('fila', 'Esta fila ya se encuentra registrada. Intenta con un valor diferente');
                }
            }
        });
    }

    public function messages()
    {
        return [
            'fila.unique' => 'Ya existe la fila y columna que intentas ingresar'
        ];
    }
}
