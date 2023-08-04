<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

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
        return [
            'identificacion' => 'required',
            'parentezco' => 'required',
            'nombres' => 'required',
            'apellidos' => 'required',
            'empleado_id' => 'required|exists:empleados,id',

        ];
    }
    protected function prepareForValidation()
    {
        $empleado_id = $this->empleado ?? Auth::user()->empleado->id;
        $this->merge([
            'empleado_id' => $empleado_id,
        ]);
    }
}
