<?php

namespace App\Http\Requests\SSO;

use App\Models\SSO\Incidente;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

/**
 * @property mixed $inspeccion
 * @property mixed $detalles_productos
 * @property mixed|string $estado
 * @property mixed $empleado_reporta
 */
class IncidenteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *SUBTAREA
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
            'coordenadas' => 'required|string',
            'tipo_incidente' => ['required', Rule::in([Incidente::ES_REPORTE_INCIDENTE, Incidente::ES_CAMBIO_EPP])],
            'estado' => ['required', Rule::in([Incidente::CREADO, Incidente::EN_CURSO, Incidente::FINALIZADO])],
            'empleado_reporta_id' => 'nullable|numeric|integer|exists:empleados,id',
            'empleado_involucrado_id' => 'required|numeric|integer|exists:empleados,id',
            'inspeccion_id' => 'nullable|numeric|integer|exists:sso_inspecciones,id',
            'detalles_productos.*.id' => 'required|numeric|integer',
            'detalles_productos.*.cantidad' => 'required|numeric|integer',
            'detalles_productos.*.motivo_cambio' => 'required|string',
            'detalles_productos.*.producto' => 'required|string',
            'detalles_productos.*.descripcion' => 'required|string',
            'cliente_id' => 'nullable|numeric|integer|exists:clientes,id',
        ];

        if ($this->isMethod('patch')) {
            $rules = collect($rules)->only(array_keys($this->all()))->toArray(); // Esta regla está bien para pach, verificado el 14/8/2024
        }

        return $rules;
    }

    protected function prepareForValidation()
    {
        if ($this->isMethod('post')) {
            $this->merge([
                'estado' => Incidente::CREADO,
                'empleado_reporta_id' => Auth::user()->empleado->id,
            ]);
        }

        $this->merge([
            'inspeccion_id' => $this->inspeccion,
            'empleado_involucrado_id' => $this['empleado_involucrado'],
            'cliente_id' => $this['cliente'],
        ]);
    }

    public function messages()
    {
        return [
            'detalles_productos.*.motivo_cambio.required' => 'Debe los motivos por los cuales desea cambiar los artículos.',
        ];
    }
}
