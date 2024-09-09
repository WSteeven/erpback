<?php

namespace App\Http\Requests\RecursosHumanos\NominaPrestamos;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class PrestamoEmpresarialRequest extends FormRequest
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
            'fecha' => 'required|date_format:Y-m-d',
            'solicitante' => 'required|numeric',
            'monto' => 'required|numeric',
            'periodo_id' => 'nullable|exists:periodos,id',
            'valor_utilidad' => 'nullable|numeric',
            'plazo' => 'required|string',
            'estado' => 'required|string',
        ];
    }
    protected function prepareForValidation()
    {
        $this->merge([
            'estado' => 'ACTIVO',
            'periodo_id'=>$this->periodo
        ]);
    }
}
