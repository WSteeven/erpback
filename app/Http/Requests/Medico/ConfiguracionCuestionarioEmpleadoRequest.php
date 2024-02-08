<?php

namespace App\Http\Requests\Medico;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class ConfiguracionCuestionarioEmpleadoRequest extends FormRequest
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
            'fecha_hora_inicio' => 'required|string',
            'fecha_hora_fin' => 'required|string',
        ];
    }
    protected function prepareForValidation()
    {
        Log::channel('testing')->info('log',[[$this->fecha_hora_inicio]]);
    }
}
