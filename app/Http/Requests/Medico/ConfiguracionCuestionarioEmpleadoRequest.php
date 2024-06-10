<?php

namespace App\Http\Requests\Medico;

use Carbon\Carbon;
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
        $this->merge([
            'fecha_hora_inicio' => Carbon::parse($this->fecha_hora_inicio)->format('Y-m-d H:i:s'),
            'fecha_hora_fin' => Carbon::parse($this->fecha_hora_fin)->format('Y-m-d H:i:s'),
        ]);
    }
}
