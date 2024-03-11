<?php

namespace App\Http\Requests\Tareas;

use App\Models\Autorizacion;
use App\Models\MaterialEmpleadoTarea;
use App\Models\Tarea;
use App\Models\Tareas\TransferenciaProductoEmpleado;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TransferenciaProductoEmpleadoRequest extends FormRequest
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
            'causa_anulacion' => 'nullable|string',
            // 'estado' => ['sometimes', Rule::in([TransferenciaProductoEmpleado::PENDIENTE, TransferenciaProductoEmpleado::ANULADA, TransferenciaProductoEmpleado::COMPLETA])],
            'observacion_aut' => 'nullable|string',
            'solicitante' => 'required|exists:empleados,id',
            'empleado_origen' => 'required|exists:empleados,id',
            'empleado_destino' => 'required|exists:empleados,id',
            'proyecto_origen' => 'sometimes|nullable|exists:proyectos,id',
            'proyecto_destino' => 'sometimes|nullable|exists:proyectos,id',
            'etapa_origen' => 'sometimes|nullable|exists:tar_etapas,id',
            'etapa_destino' => 'sometimes|nullable|exists:tar_etapas,id',
            'tarea_origen' => 'sometimes|nullable|exists:tareas,id',
            'tarea_destino' => 'sometimes|nullable|exists:tareas,id',
            'autorizacion' => 'nullable|numeric|integer|exists:autorizaciones,id',
            'autorizador' => 'required|numeric|exists:empleados,id',
            'listado_productos.*.cantidad' => 'required',
            // 'listado_productos.*.cliente_id' => 'required',
            // 'listado_productos.*.descripcion' => 'required',
        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'listado_productos.*.cantidad' => 'listado',
        ];
    }

    public function messages()
    {
        return [
            'listado_productos.*.cantidad' => 'Debes seleccionar una cantidad para el producto del :attribute',
        ];
    }

    protected function withValidator($validator)
    {
        $validator->after(function ($validator) {
            foreach ($this->listado_productos as $listado) {
                $material = null;

                $material = MaterialEmpleadoTarea::where('tarea_id', $this->tarea_origen)
                    ->where('empleado_id', $this->empleado_origen)
                    ->where('detalle_producto_id', $listado['id'])
                    ->first();

                if ($material && $listado['cantidad'] > $material->cantidad_stock) {
                    $validator->errors()->add('listado_productos.*.cantidad', 'La cantidad para el item ' . $listado['descripcion'] . ' no debe ser superior a la existente en el stock. En stock ' . $material->cantidad_stock);
                }
            }
        });
    }

    // Esto se ejecuta antes de validar las rules
    /*protected function prepareForValidation()
    {
        $this->merge([
            'estado' => TransferenciaProductoEmpleado::PENDIENTE
        ]);

        if (is_null($this->autorizador) || $this->autorizador === '') {
            $this->merge(['autorizador' => $this->obtenerAutorizador()]);
        }

        if (is_null($this->autorizacion) || $this->autorizacion === '') {
            $this->merge(['autorizacion' => Autorizacion::PENDIENTE_ID]);
        }

        if (is_null($this->solicitante) || $this->solicitante === '') {
            $this->merge(['solicitante' => auth()->user()->empleado->id]);
        }

         if (auth()->user()->hasRole([User::ROL_COORDINADOR, User::ROL_COORDINADOR_BACKUP]) && $this->route()->getActionMethod() != 'update') {
            $this->merge([
                'autorizacion' => Autorizacion::APROBADO_ID,
                'autorizador' => auth()->user()->empleado->id,
            ]);
        }
    }*/

    private function obtenerAutorizador()
    {
        $tarea = Tarea::find($this->tarea_origen);
        return $tarea?->etapa_id ? $tarea->coordinador_id : auth()->user()->empleado->jefe_id;
    }
}
