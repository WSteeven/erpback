<?php

namespace App\Http\Requests\SSO;

use App\Models\SSO\Incidente;
use App\Models\SSO\SolicitudDescuento;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Log;

class SolicitudDescuentoRequest extends FormRequest
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
            'estado' => ['nullable', Rule::in([SolicitudDescuento::CREADO, SolicitudDescuento::PRECIOS_ESTABLECIDOS, SolicitudDescuento::DESCONTADO])],
            'empleado_involucrado_id' => 'nullable|numeric|integer|exists:empleados,id',
            'empleado_solicitante_id' => 'required|numeric|integer|exists:empleados,id',
            'detalles_productos' => 'required|array',
            'detalles_productos.*.id' => 'required|numeric|integer',
            'detalles_productos.*.cantidad' => 'required|numeric|integer',
            'detalles_productos.*.precio_unitario' => 'nullable|numeric|numeric',
            'detalles_productos.*.producto' => 'required|string',
            'detalles_productos.*.descripcion' => 'required|string',
            'cliente_id' => 'nullable|numeric|integer|exists:clientes,id',
            'incidente_id' => 'nullable|numeric|integer|exists:sso_incidentes,id',
        ];

        // Para PATCH, solo validar los campos que se envían en la solicitud
        if ($this->isMethod('patch')) {
            $rules = collect($rules)->only(array_keys($this->all()))->toArray(); // Esta regla está bien para pach, verificado el 14/8/2024
        }


        return $rules;
    }

    protected function prepareForValidation()
    {
        if ($this->isMethod('post')) {
            $this->merge([
                'empleado_solicitante_id' => Auth::user()->empleado->id,
                'empleado_involucrado_id' => $this['empleado_involucrado'],
                'incidente_id' => $this['incidente'],
                'cliente_id' => $this['cliente'],
                'estado' => SolicitudDescuento::CREADO,
            ]);
        }
    }
}
