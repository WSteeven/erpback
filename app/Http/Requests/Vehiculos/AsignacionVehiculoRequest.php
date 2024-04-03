<?php

namespace App\Http\Requests\Vehiculos;

use App\Models\Vehiculos\AsignacionVehiculo;
use Illuminate\Foundation\Http\FormRequest;
use Src\Shared\Utils;

class AsignacionVehiculoRequest extends FormRequest
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
            'canton_id' => 'required|exists:cantones,id',
            'responsable_id' => 'required|exists:veh_conductores,empleado_id',
            'observacion_recibe' => 'nullable|sometimes|string',
            'observacion_entrega' => 'nullable|sometimes|string',
            'fecha_entrega' => 'required|string',
            'estado' => 'required|string',
            'accesorios' => 'nullable|sometimes|string',
            'estado_carroceria' => 'nullable|sometimes|string',
            'estado_mecanico' => 'nullable|sometimes|string',
            'estado_electrico' => 'nullable|sometimes|string',
        ];
    }
    // public function withValidator($validator){
    //     $validator->after(function($validator){
    //         $vehiculoAsignado = AsignacionVehiculo::where('vehiculo_id', $this->vehiculo_id)->where('responsable_id')->first();
    //     })
    // }

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
        ]);
    }
}
