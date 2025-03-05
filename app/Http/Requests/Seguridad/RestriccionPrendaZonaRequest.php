<?php

namespace App\Http\Requests\Seguridad;

use Illuminate\Foundation\Http\FormRequest;

class RestriccionPrendaZonaRequest extends FormRequest
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
            'listado.*.detalle_producto_id' => 'required|numeric|integer|exists:detalles_productos,id',
            'listado.*.miembro_zona_id' => 'required|numeric|integer|exists:seg_miembros_zonas,id',
        ];
    }
}
