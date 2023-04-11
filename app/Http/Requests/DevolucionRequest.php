<?php

namespace App\Http\Requests;

use App\Models\MaterialEmpleadoTarea;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

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
            'justificacion' => 'required|string',
            'solicitante' => 'required|exists:empleados,id',
            'tarea' => 'sometimes|nullable|exists:tareas,id',
            'canton' => 'sometimes|nullable|exists:cantones,id',
            'stock_personal' => 'boolean',
            'listadoProductos.*.cantidad' => 'required',
            'listadoProductos.*.descripcion' => 'required',
        ];

        return $rules;
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
            'listadoProductos.*.descripcion' => 'El campo descripción debe contener detalles y estado del producto a devolver',
        ];
    }
    protected function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->tarea) {
                foreach ($this->listadoProductos as $listado) {
                    $material = MaterialEmpleadoTarea::where('tarea_id', $this->tarea)
                        ->where('empleado_id', auth()->user()->empleado->id)
                        ->where('detalle_producto_id', $listado['id'])->first();
                    if ($material) {
                        if ($listado['cantidad'] > $material->cantidad_stock) {
                            $validator->errors()->add('listadoProductos.*.cantidad', 'La cantidad para el item ' . $listado['descripcion'] . ' no debe ser superior a la existente en el stock');
                        }
                    }
                }
            }
        });
    }
    protected function prepareForValidation() //esto se ejecuta antes de validar las rules
    {
        $this->merge([
            'solicitante' => auth()->user()->empleado->id
        ]);
    }
}
