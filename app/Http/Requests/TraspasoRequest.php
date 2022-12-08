<?php

namespace App\Http\Requests;

use App\Models\EstadoTransaccion;
use Illuminate\Foundation\Http\FormRequest;

class TraspasoRequest extends FormRequest
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
            'justificacion' => 'string|sometimes|nullable',
            'devuelta' => 'sometimes|boolean',
            'solicitante' => 'required|exists:empleados,id',
            'desde_cliente' => 'required|exists:clientes,id',
            'hasta_cliente' => 'required|exists:clientes,id',
            'tarea' => 'sometimes|nullable|exists:tareas,id',
            'sucursal' => 'required|exists:sucursales,id',
            'estado' => 'sometimes|nullable|exists:estados_transacciones_bodega,id',
            'listadoProductos.*.cantidades' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'listadoProductos.*.cantidades' => 'listado',
        ];
    }
    public function messages()
    {
        return [
            'listadoProductos.*.cantidades' => 'Debes seleccionar una cantidad para el producto del :attribute',
        ];
    }
    /* protected function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->desde_cliente === $this->hasta_cliente) {
                $validator->errors()->add('hasta_cliente', 'No se puede hacer traspaso al mismo cliente.');
            }
        });
    } */


    protected function prepareForValidation()
    {
        $this->merge([
            'solicitante' => auth()->user()->empleado->id,
            'devuelta' => false,
            'estado' => 1
        ]);
    }
}
