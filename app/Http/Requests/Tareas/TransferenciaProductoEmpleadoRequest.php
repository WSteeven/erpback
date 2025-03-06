<?php

namespace App\Http\Requests\Tareas;

use App\Models\Autorizacion;
use App\Models\MaterialEmpleadoTarea;
use App\Models\Tarea;
use App\Models\Tareas\TransferenciaProductoEmpleado;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Log;

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
            'observacion_aut' => 'nullable|string',
            'novedades_transferencia_recibida' => 'nullable|string',
            'solicitante_id' => 'required|exists:empleados,id',
            'empleado_origen_id' => 'required|exists:empleados,id',
            'empleado_destino_id' => 'required|exists:empleados,id',
            'proyecto_origen_id' => 'sometimes|nullable|exists:proyectos,id',
            'proyecto_destino_id' => 'sometimes|nullable|exists:proyectos,id',
            'etapa_origen_id' => 'sometimes|nullable|exists:tar_etapas,id',
            'etapa_destino_id' => 'sometimes|nullable|exists:tar_etapas,id',
            'tarea_origen_id' => 'sometimes|nullable|exists:tareas,id',
            'tarea_destino_id' => 'sometimes|nullable|exists:tareas,id',
            'autorizacion_id' => 'nullable|numeric|integer|exists:autorizaciones,id',
            'autorizador_id' => 'required|numeric|exists:empleados,id',
            'cliente_id' => 'required|numeric|exists:clientes,id',
            'listado_productos.*.cantidad' => 'required|numeric|integer',
            'listado_productos.*.recibido' => 'nullable|numeric|integer',
        ];

        if ($this->isMethod('patch')) {
            $rules = collect($rules)->only(array_keys($this->all()))->toArray(); // Esta regla está bien para pach, verificado el 14/8/2024
        }

        /* if ($this->isMethod('patch')) {
            // Filtramos solo las reglas de los campos que vienen en la solicitud
            $inputKeys = array_keys($this->all()); // Obtener las claves de los datos enviados
            Log::channel('testing')->info('Log', ['input', $inputKeys]);
            $rules = array_intersect_key($rules, array_flip($inputKeys)); // Mantener solo las reglas de los datos enviados
            Log::channel('testing')->info('Log', ['Reglas', $rules]);
        } */

        return $rules;
    }

    public function attributes()
    {
        return [
            'listado_productos.*.cantidad' => 'listado',
        ];
    }

    protected function prepareForValidation()
    {
        // if ($this->isMethod('post')) {
        $data = [
            'solicitante_id' => $this['solicitante'],
            'empleado_origen_id' => $this['empleado_origen'],
            'empleado_destino_id' => $this['empleado_destino'],
            'proyecto_origen_id' => $this['proyecto_origen'],
            'proyecto_destino_id' => $this['proyecto_destino'],
            'etapa_origen_id' => $this['etapa_origen'],
            'etapa_destino_id' => $this['etapa_destino'],
            'tarea_origen_id' => $this['tarea_origen'],
            'tarea_destino_id' => $this['tarea_destino'],
            'autorizacion_id' => $this['autorizacion'],
            'autorizador_id' => $this['autorizador'],
            'cliente_id' => $this['cliente'],
        ];

        // Filtrar los valores nulos y solo fusionar los que existen
        $this->merge(array_filter($data, fn($value) => !is_null($value)));
        // }
    }

    public function messages()
    {
        return [
            'listado_productos.*.cantidad' => 'Debes seleccionar una cantidad para el producto del :attribute',
        ];
    }

    protected function withValidator($validator)
    {
        if ($this->autorizacion === Autorizacion::CANCELADO_ID) return;

        $validator->after(function ($validator) {
            if ($this->listado_productos) {

                foreach ($this->listado_productos as $listado) {
                    $material = null;

                    $material = MaterialEmpleadoTarea::where('tarea_id', $this->tarea_origen)
                        ->where('empleado_id', $this->empleado_origen)
                        ->where('detalle_producto_id', $listado['id'])
                        ->first();

                    if ($material && $listado['recibido'] > $material->cantidad_stock) {
                        $validator->errors()->add('listado_productos.*.cantidad', 'La cantidad para el item ' . $listado['descripcion'] . ' no debe ser superior a la existente en el stock. En stock ' . $material->cantidad_stock);
                    }
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
