<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PedidoRequest extends FormRequest
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
            'justificacion' => 'required|string',
            'fecha_limite' => 'required|string',
            'observacion_aut' => 'required|string',
            'observacion_est' => 'required|string',
            'solicitante' => 'required|numeric|exists:empleados,id',
            'autorizacion' => 'required|numeric|exists:autorizaciones,id',
            'per_autoriza' => 'required|numeric|exists:empleados,id',
            'tarea' => 'required|numeric|exists:tareas,id',
            'sucursal' => 'required|numeric|exists:sucursales,id',
            'estado' => 'required|numeric|exists:estados_transacciones_bodega,id',
            'listadoProductos.*.cantidad' => 'required',
        ];
    }
    public function attributes()
    {
        return [
            'listadoProductos.*.cantidad' => 'listado',
        ];
    }
    public function messages()
    {
        return [
            'listadoProductos.*.cantidad' => 'Debes seleccionar una cantidad para el producto del :attribute',
        ];
    }
    protected function prepareForValidation()
    {
        $this->merge([
            'solicitante' => auth()->user()->empleado->id
        ]);
    }
}
