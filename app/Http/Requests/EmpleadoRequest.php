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
            'fecha_nacimiento' => 'required|date|date_format:Y-m-d',
            'jefe' => 'required|exists:empleados,id',
            'canton' => 'sometimes|nullable|exists:cantones,id',
            'roles' => 'required|exists:roles,name',
            'estado' => 'sometimes|boolean',
            'cargo' => 'required|exists:cargos,id',
            'departamento' => 'required|exists:departamentos,id',
            'grupo' => 'nullable|exists:grupos,id',
            'firma_url' => 'nullable|string',
            'foto_url' => 'nullable|string',
            // 'es_responsable_grupo' => 'nullable|boolean',
            'convencional' => 'nullable|string',
            'telefono_empresa' => 'nullable|string',
            'extension' => 'nullable|string',
            'coordenadas' => 'nullable|string',
            'casa_propia' => 'nullable|boolean',
            'vive_con_discapacitados' => 'nullable|boolean',
            'responsable_discapacitados' => 'nullable|boolean',
            'tipo_sangre'=>'required',
            'direccion'=>'required',
            'estado_civil_id'=>'required',
            'correo_personal'=>'required',
            'area_id'=>'required',
            'num_cuenta_bancaria'=>'required',
            'salario'=>'required',
            'fecha_ingreso'=>'required',
            'fecha_salida'=>'nullable',
            'tipo_contrato_id'=> 'required',
            'tiene_grupo'=>'required',
            'tiene_discapacidad'=>'required',
            'nivel_academico'=>'required',
            'supa' =>'nullable',
            'talla_zapato' =>'nullable',
            'talla_camisa' =>'required',
            'talla_guantes' =>'nullable',
            'talla_pantalon' =>'nullable',
            'banco' =>'required',
            'genero' =>'required',
            'esta_en_rol_pago'=>'required',
            'realiza_factura'=>'required',
        ];

        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $user = User::find($this->route()->parameter('empleado.usuario_id'));

            $rules['identificacion'] = [Rule::unique('empleados')->ignore($user->empleado)];
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
    public function prepareForValidation()
    {
        $this->merge([
            'fecha_nacimiento' => date('Y-m-d', strtotime($this->fecha_nacimiento)),
            'estado_civil_id' => $this->estado_civil,
            'area_id' => $this->area,
            'tipo_contrato_id' => $this->tipo_contrato,
            'num_cuenta_bancaria' => $this->num_cuenta
        ]);
    }
}


