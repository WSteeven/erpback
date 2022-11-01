<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PrestamoTemporalRequest extends FormRequest
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
            'fecha_salida'=>'required|string',
            'fecha_devolucion'=>'nullable|string',
            'observacion'=>'nullable|string|sometimes',
            'solicitante'=>'required|exists:empleados,id',
            'per_entrega'=>'required|exists:empleados,id',
            'per_recibe'=>'nullable|sometimes|exists:empleados,id',
            'listadoProductos.*.cantidad'=>'required'
        ];
    }

    public function attributes()
    {
        return [
            'listadoProductos.*.cantidad'=>'listado',
        ];
    }
    public function messages()
    {
        return [
            'listadoProductos.*.cantidad'=>'Debes seleccionar una cantidad para el producto del :attribute',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'per_entrega'=>auth()->user()->empleado->id
        ]);
    }
}
