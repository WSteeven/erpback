<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmpleadoRequest extends FormRequest
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
            'identificacion'=>'string|required|min:10|max:13',
            'nombres'=>'required|string',
            'apellidos'=>'string',
            'telefono'=>'required|min:7|max:13',
            'fecha_nacimiento'=>'required|date',
            'jefe_id'=>'required|exists:empleados,id',
            'usuario_id'=>'required|exists:users,id',
            'sucursal_id'=>'required|exists:sucursales,id',
        ];
    }
}
