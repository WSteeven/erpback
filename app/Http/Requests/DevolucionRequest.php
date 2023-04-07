<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DevolucionRequest extends FormRequest
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
        $rules =  [
            'justificacion'=>'required|string',
            'solicitante'=>'required|exists:empleados,id',
            'tarea'=>'sometimes|nullable|exists:tareas,id',
            'canton'=>'sometimes|nullable|exists:cantones,id',
            'stock_personal'=>'boolean',
            'listadoProductos.*.cantidad'=>'required',
            'listadoProductos.*.descripcion'=>'required',
        ];

        return $rules;
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
            'listadoProductos.*.descripcion'=>'El campo descripción debe contener detalles y estado del producto a devolver',
        ];
    }
    protected function prepareForValidation()
    {
        $this->merge([
            'solicitante'=>auth()->user()->empleado->id
        ]);
    }
}
