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
    protected function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (!in_array($this->method(), ['PUT', 'PATCH'])) {
                foreach ($this->listadoProductos as $listado) {
                    if ($listado['cantidades'] > $listado['cantidad']) {
                        $validator->errors()->add('listadoProductos.*.cantidades', 'La cantidad del item ' . $listado['producto'] . ' no puede ser mayor a la existente en el inventario.');
                        $validator->errors()->add('listadoProductos.*.cantidades', 'En inventario:' . $listado['cantidad']);
                    }
                }
            }
            /* if ($this->desde_cliente === $this->hasta_cliente) {
                $validator->errors()->add('hasta_cliente', 'No se puede hacer traspaso al mismo cliente.');
            } */
        });
    }


    protected function prepareForValidation()
    {
        $this->merge([
            'solicitante' => auth()->user()->empleado->id,
            'devuelta' => false,
            'estado' => 1
        ]);

        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            // $completa = false;
            foreach ($this->listadoProductos as $listado) {
                $completa = $listado['cantidades'] == $listado['devolver'] ? true : false;
            }
            if ($completa) {
                $this->merge([
                    'estado' => 2,
                    'devuelta' => true,
                ]);
            } else {
                $this->merge([
                    'estado' => 3,
                ]);
            }
        }
    }
}
