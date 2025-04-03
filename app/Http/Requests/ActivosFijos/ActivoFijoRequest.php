<?php

namespace App\Http\Requests\ActivosFijos;

use Illuminate\Foundation\Http\FormRequest;

class ActivoFijoRequest extends FormRequest
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
            'codigo_personalizado' => 'nullable|string',
            'codigo_sistema_anterior' => 'nullable|string',
            'detalle_producto_id' => 'nullable|numeric|integer|exists:detalles_productos,id',
            'cliente_id' => 'nullable|numeric|integer|exists:clientes,id',
        ];

        if ($this->isMethod('patch')) {
            $rules = collect($rules)->only(array_keys($this->all()))->toArray(); // Esta regla está bien para pach, verificado el 14/8/2024
        }

        return $rules;
    }
}
