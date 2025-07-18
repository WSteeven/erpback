<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
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
            'tipo_sangre' => 'required',
            'direccion' => 'required',
            'estado_civil_id' => 'required',
            'correo_personal' => 'required',
            'area_id' => 'required',
            'num_cuenta_bancaria' => 'required',
            'salario' => 'required',
            'fecha_ingreso' => 'required|date|date_format:Y-m-d',
            'fecha_vinculacion' => 'nullable|date|date_format:Y-m-d',
            'fecha_salida' => 'nullable|date|date_format:Y-m-d',
            'tipo_contrato_id' => 'required',
            'tiene_grupo' => 'required',
            'tiene_discapacidad' => 'required',
            'nivel_academico' => 'required',
            'titulo' => 'required|string',
            'supa' => 'nullable',
            'talla_zapato' => 'nullable',
            'talla_camisa' => 'required',
            'talla_guantes' => 'nullable',
            'talla_pantalon' => 'nullable',
            'banco' => 'required',
            'genero' => 'required',
            'esta_en_rol_pago' => 'required',
            'acumula_fondos_reserva' => 'nullable',
            'realiza_factura' => 'required',
            'observacion' => 'nullable',
            'discapacidades.*.tipo_discapacidad' => 'required_if:tiene_discapacidad,true|exists:rrhh_tipos_discapacidades,id',
            'discapacidades.*.porcentaje' => 'required_if:tiene_discapacidad,true|numeric',
            'familiares' => 'nullable',
            // 'autoidentificacion_etnica' => 'required',
            'trabajador_sustituto' => 'required',
            'orientacion_sexual_id' => 'nullable|exists:med_orientaciones_sexuales,id',
            'identidad_genero_id' => 'nullable|exists:med_identidades_generos,id',
            'religion_id' => 'nullable|exists:med_religiones,id',
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
//            Log::channel('testing')->info('Log', ['reuqest del empleado',request()->all(), $this->method(), $this->all()]);
            if (!$validador->validarCedula($this->identificacion?:"")) {
                Log::channel('testing')->info('Log', ['Dentro del if']);
                $validator->errors()->add('identificacion', 'La identificación no pudo ser validada, verifica que sea una cédula válida');
            }
            // if(substr_count($this->identificacion, '9')<9){
            // }
        });
    }
    public function prepareForValidation()

    {
        if ($this->fecha_vinculacion != null) {
            $this->merge([
                'fecha_vinculacion' => date('Y-m-d', strtotime($this->fecha_vinculacion))
            ]);
        }

        if ($this->fecha_salida != null) {
            $this->merge([
                'fecha_salida' => date('Y-m-d', strtotime($this->fecha_salida))
            ]);
        }
        $this->merge([
            'fecha_nacimiento' => date('Y-m-d', strtotime($this->fecha_nacimiento)),
            'fecha_ingreso' => date('Y-m-d', strtotime($this->fecha_ingreso)),
            'estado_civil_id' => $this->estado_civil,
            'area_id' => $this->area,
            'tipo_contrato_id' => $this->tipo_contrato,
            'num_cuenta_bancaria' => $this->num_cuenta,
            'orientacion_sexual_id' => $this->orientacion_sexual,
            'identidad_genero_id' => $this->identidad_genero,
            'religion_id' => $this->religion,
        ]);
    }
}
