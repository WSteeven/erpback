<?php

namespace App\Http\Requests;

use App\Models\EstadoTransaccion;
use App\Models\MaterialEmpleadoTarea;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'estado_bodega' => ['sometimes', Rule::in([EstadoTransaccion::PENDIENTE, EstadoTransaccion::ANULADA, EstadoTransaccion::COMPLETA, EstadoTransaccion::PARCIAL, null])],
            'stock_personal' => 'boolean',
            'observacion_aut' => 'nullable|string',
            'autorizacion' => 'required|numeric|exists:autorizaciones,id',
            'per_autoriza' => 'required|numeric|exists:empleados,id',
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
            'estado_bodega' => EstadoTransaccion::PENDIENTE
        ]);

        if (is_null($this->per_autoriza) || $this->per_autoriza === '') {
            $this->merge(['per_autoriza' => auth()->user()->empleado->jefe_id]);
        }
        if (is_null($this->autorizacion) || $this->autorizacion === '') {
            $this->merge(['autorizacion' => 1]);
        }
        if (is_null($this->solicitante) || $this->solicitante === '') {
            $this->merge(['solicitante' => auth()->user()->empleado->id]);
        }
        if (auth()->user()->hasRole([User::ROL_COORDINADOR, User::ROL_COORDINADOR_BACKUP, User::ROL_JEFE_TECNICO, User::ROL_ADMINISTRATIVO]) && $this->tarea) {
            $this->merge([
                'autorizacion' => 2,
                'per_autoriza' => auth()->user()->empleado->id,
            ]);
        }

        if ($this->autorizacion == 3) {
            $this->merge([
                'estado_bodega' => EstadoTransaccion::ANULADA
            ]);
        }
    }
}
