<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'identificacion' => 'required|string|min:10|max:13',
            'telefono' => 'required|string|min:7|max:13',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'fecha_nacimiento' => 'required',
            'jefe_id' => 'sometimes|exists:users,id',
            'sucursal_id' => 'required|exists:sucursales,id',
            'roles'=>'required|exists:roles,id'
        ];

        if(in_array($this->method(), ['PUT', 'PATCH'])){
            $user = User::find($this->route()->parameter('empleado.usuario_id'));

            $rules['email']=[Rule::unique('users')->ignore($user)];
        }

        return $rules;
    }
}
