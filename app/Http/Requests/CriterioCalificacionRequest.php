<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CriterioCalificacionRequest extends FormRequest
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
            'nombre' => 'required|string',
            'descripcion' => 'required|string',
            'ponderacion_referencia' => 'required|numeric',
            'departamento_id' => 'required|exists:departamentos,id',
            'oferta_id' => 'required|exists:ofertas_proveedores,id',
        ];
    }
}
