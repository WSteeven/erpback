<?php

namespace App\Http\Requests\RecursosHumanos\Alimentacion;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class AlimentacionRequest extends FormRequest
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
            'empleado_id' => 'nullable',
            'valor_asignado' => 'nullable',
            'fecha_corte' => 'nullable',
        ];
    }
    protected function prepareForValidation()
    {
        if ($this->fecha_corte !== null) {
            $fecha_corte = Carbon::parse($this->fecha_corte);
            $this->merge([
                'fecha_corte' => $fecha_corte->format('Y-m-d'),
            ]);
        }
        $this->merge([
            'empleado_id' => $this->empleado
        ]);
    }
}
