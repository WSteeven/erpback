<?php

namespace App\Http\Requests\RecursosHumanos\NominaPrestamos;

use App\Models\RecursosHumanos\NominaPrestamos\PrestamoEmpresarial;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

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
            'fecha_inicio_cobro' => 'required|string',
            'solicitante' => 'required|numeric',
            'monto' => 'required|numeric',
            'periodo_id' => 'nullable|exists:periodos,id',
            'valor_utilidad' => 'nullable|numeric',
            'plazo' => 'required',
            'estado' => 'required|string',
            'id_solicitud_prestamo_empresarial' => 'sometimes|nullable|integer',
            'plazos' => 'required|array',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'estado' => $this->estado ?? PrestamoEmpresarial::ACTIVO,
            'periodo_id' => $this->periodo,
            'fecha_inicio_cobro' => Carbon::parse($this->fecha_inicio_cobro)->endOfMonth()->format('Y-m-d'),
        ]);
    }
}
