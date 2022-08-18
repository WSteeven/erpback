<?php

namespace App\Http\Requests;

use App\Models\Empresa;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class EmpresaRequest extends FormRequest
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
            'identificacion'=>'string|min:10|max:13',
            'tipo_contribuyente'=>Rule::in([Empresa::NATURAL, Empresa::JURIDICA]),
            'razon_social'=>'string|required',
            'nombre_comercial'=>'string|nullable',
            'correo'=>'email|nullable',
            'direccion'=>'string|nullable',
        ];
    }
}
