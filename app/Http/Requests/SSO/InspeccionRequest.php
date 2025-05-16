<?php

namespace App\Http\Requests\SSO;

use App\Models\SSO\Inspeccion;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

/**
 * @property mixed $responsable
 */
class InspeccionRequest extends FormRequest
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
            'titulo' => 'required|string',
            'descripcion' => 'required|string',
            'estado' => ['required', Rule::in([Inspeccion::CREADO, Inspeccion::FINALIZADO])],
            'fecha_inicio' => 'required|date|date_format:Y-m-d',
            'responsable_id' => 'required|numeric|integer|exists:empleados,id',
            'empleado_involucrado_id' => 'nullable|numeric|integer|exists:empleados,id',
            'tiene_incidencias' => 'required|boolean',
            'coordenadas' => 'nullable|string',
            'seguimiento' => 'nullable|string',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->isMethod('post')) {
            $this->merge([
                'estado' => Inspeccion::CREADO,
            ]);
        }

        $this->merge([
            'responsable_id' => Auth::user()->empleado->id,
            'empleado_involucrado_id' => $this['empleado_involucrado'],
        ]);
    }
}
