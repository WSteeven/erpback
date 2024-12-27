<?php

namespace App\Http\Requests\SSO;

use App\Models\SSO\Accidente;
use Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AccidenteRequest extends FormRequest
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
            'titulo' => 'required|string',
            'descripcion' => 'required|string',
            'medidas_preventivas' => 'nullable|string',
            'empleados_involucrados' => 'required|array',
            'fecha_hora_ocurrencia' => 'required|date|date_format:Y-m-d H:i:s',
            'coordenadas' => 'required|string',
            'consecuencias' => 'required|string',
            'lugar_accidente' => 'required|string',
            'estado' => ['required', Rule::in([Accidente::CREADO, Accidente::FINALIZADO])],
            'empleado_reporta_id' => 'nullable|numeric|integer|exists:empleados,id',
        ];

        // Solo aplicar la regla 'required' si el método es POST
        if ($this->isMethod('post')) {
            $rules['empleado_reporta_id'] = 'required|numeric|integer|exists:empleados,id';
        }

        return $rules;
    }

    protected function prepareForValidation()
    {
        if ($this->isMethod('post')) {
            $this->merge([
                'estado' => Accidente::CREADO,
                'empleado_reporta_id' => Auth::user()->empleado->id,
            ]);
        }
    }
}
