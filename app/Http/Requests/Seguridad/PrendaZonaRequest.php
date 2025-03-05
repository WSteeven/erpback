<?php

namespace App\Http\Requests\Seguridad;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PrendaZonaRequest extends FormRequest
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
            'zona_id' => [
                'required',
                'numeric',
                'integer',
                'exists:seg_zonas,id',
            ],
            'tiene_restricciones' => 'boolean',
            'detalles_productos.*.id' => 'required|numeric|integer|exists:detalles_productos,id',
            'detalles_productos.*.descripcion' => 'required|string',
            'detalles_productos.*.categoria' => 'required|string',
            'detalles_productos.*.serial' => 'nullable|string',
            'detalles_productos.*.producto' => 'required|string',
        ];

        // Aplica la regla de unicidad solo en creación (POST)
        if ($this->isMethod('post')) {
            $rules['zona_id'][] = Rule::unique('seg_prendas_zonas', 'zona_id');
        }

        return $rules;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'zona_id' => $this['zona'],
        ]);
    }

    public function messages()
    {
        return [
            'zona_id.unique' => 'Ya existe asignación de prendas para la zona seleccionada, edítela o seleccione una diferente.'
        ];
    }
}
