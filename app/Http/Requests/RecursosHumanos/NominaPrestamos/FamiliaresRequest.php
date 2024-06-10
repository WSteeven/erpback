<?php

namespace App\Http\Requests\RecursosHumanos\NominaPrestamos;

use App\Models\RecursosHumanos\NominaPrestamos\Familiares;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Src\Shared\ValidarIdentificacion;

class FamiliaresRequest extends FormRequest
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
            'identificacion' => 'string|required|unique:familiares,identificacion|min:10|max:13',
            'parentezco' => 'required',
            'nombres' => 'required',
            'apellidos' => 'required',
            'empleado_id' => 'required|exists:empleados,id',

        ];
        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $familiares = Familiares::find($this->route()->parameter('familiare'));
            $rules['identificacion'] = [Rule::unique('familiares')->ignore($familiares)];
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
        });
    }
    protected function prepareForValidation()
    {
        $empleado_id = $this->empleado ?? Auth::user()->empleado->id;
        $this->merge([
            'empleado_id' => $empleado_id,
        ]);
    }
}
