<?php

namespace App\Http\Requests\Medico;

use App\Models\Empleado;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ExamenOrganoReproductivoRequest extends FormRequest
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
            'examen' => 'required',
            'tipo' => ['required', Rule::in(Empleado::MASCULINO, Empleado::FEMENINO)], //M-F
        ];
    }
}
