<?php

namespace App\Http\Requests\FondosRotativos\Gasto;

use Illuminate\Foundation\Http\FormRequest;

class AutorizadorDirectoRequest extends FormRequest
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
            'empleado_id'=> 'required|exists:empleados,id',
            'autorizador_id'=> 'required|exists:empleados,id',
            'observacion'=>'nullable|string',
            'activo'=>'boolean',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'empleado_id'=> $this->empleado,
            'autorizador_id'=> $this->autorizador,
        ]);
    }
}
