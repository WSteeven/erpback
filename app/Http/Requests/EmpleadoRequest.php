<?php

namespace App\Http\Requests;

use App\Models\Empleado;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Src\Shared\ValidarIdentificacion;

class EmpleadoRequest extends FormRequest
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
            'identificacion' => 'string|required|unique:empleados,identificacion|min:10|max:13',
            'nombres' => 'required|string',
            'apellidos' => 'string',
            'telefono' => 'required|min:7|max:13',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'usuario' => 'required|string',
            'fecha_nacimiento' => 'required|date',
            'jefe' => 'required|exists:empleados,id',
            'canton' => 'sometimes|nullable|exists:cantones,id',
            'roles' => 'required|exists:roles,name',
            'estado' => 'sometimes|boolean',
            'cargo' => 'required|exists:cargos,id',
            'grupo' => 'nullable|exists:grupos,id',
            // 'es_responsable_grupo' => 'nullable|boolean',
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $user = User::find($this->route()->parameter('empleado.usuario_id'));

            $rules['identificacion'] = [Rule::unique('empleados')->ignore($user)];
            $rules['email'] = [Rule::unique('users')->ignore($user)];
            $rules['password'] = 'nullable';
        }

        return $rules;
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $validador = new ValidarIdentificacion();
            if (!$validador->validarCedula($this->identificacion)) {
                $validator->errors()->add('identificacion', 'La identificación no pudo ser validada, verifica que sea una cédula válida');
            }
            // if(substr_count($this->identificacion, '9')<9){
            // }
        });
    }
}
