<?php

namespace App\Http\Requests\Vehiculos;

use Illuminate\Foundation\Http\FormRequest;

class MantenimientoVehiculoRequest extends FormRequest
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
            'vehiculo_id' => 'required|exists:vehiculos,id',
            'servicio_id' => 'required|exists:veh_servicios,id',
            'empleado_id' => 'required|exists:empleados,id',
            'supervisor_id' => 'required|exists:empleados,id',
            'fecha_realizado' => 'sometimes|string|nullable',
            'km_realizado' => 'string|nullable',
            'imagen_evidencia' => 'string|nullable',
            'estado' => 'required|string',
            'km_retraso' => 'string|nullable',
            'dias_postergado' => 'integer|nullable',
            'motivo_postergacion' => 'string|nullable',
            'observacion' => 'string|nullable',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'vehiculo_id' => $this->vehiculo,
            'servicio_id' => $this->servicio,
            'empleado_id' => $this->empleado,
            'supervisor_id' => $this->supervisor,
        ]);
    }
}
