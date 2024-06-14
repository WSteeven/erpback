<?php

namespace App\Http\Requests\Vehiculos;

use Illuminate\Foundation\Http\FormRequest;
use Src\Shared\Utils;

class TransferenciaVehiculoRequest extends FormRequest
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
            'entrega_id' => 'required|exists:veh_conductores,empleado_id',
            'responsable_id' => 'required|exists:veh_conductores,empleado_id',
            'canton_id' => 'required|exists:cantones,id',
            'motivo' => 'required|string',
            'observacion_recibe' => 'nullable|sometimes|string',
            'observacion_entrega' => 'nullable|sometimes|string',
            'fecha_entrega' => 'required|string',
            'estado' => 'required|string',
            'transferido' => 'boolean',
            'devuelto' => 'boolean',
            'observaciones_devolucion' => 'nullable|sometimes|string',
            'devuelve_id' => 'nullable|sometimes|exists:empleados,id',
            'asignacion_id' => 'nullable|sometimes|exists:veh_asignaciones_vehiculos,id',
            'transferencia_id' => 'nullable|sometimes|exists:veh_transferencias_vehiculos,id',
            'fecha_devolucion' => 'nullable|string',
            'accesorios' => 'nullable|sometimes|string',
            'estado_carroceria' => 'nullable|sometimes|string',
            'estado_mecanico' => 'nullable|sometimes|string',
            'estado_electrico' => 'nullable|sometimes|string',
        ];
    }

    protected function prepareForValidation()
    {
        //Adaptación de foreign keys
        $this->merge([
            'vehiculo_id' => $this->vehiculo,
            'entrega_id' => $this->entrega,
            'responsable_id' => $this->responsable,
            'canton_id' => $this->canton,
            'accesorios' => Utils::convertArrayToString($this->accesorios, ','),
            'estado_carroceria' => Utils::convertArrayToString($this->estado_carroceria,),
            'estado_mecanico' => Utils::convertArrayToString($this->estado_mecanico,),
            'estado_electrico' => Utils::convertArrayToString($this->estado_electrico,),
            'devuelve_id' => $this->devuelve,
        ]);

        if (!is_null($this->asignacion)) $this->merge([
            'asignacion_id' => $this->asignacion
        ]);
        if (!is_null($this->transferencia)) $this->merge([
            'transferencia_id' => $this->transferencia,
        ]);
    }
}
