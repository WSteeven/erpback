<?php

namespace App\Http\Requests\Vehiculos;

use App\Models\Vehiculos\Vehiculo;
use Illuminate\Foundation\Http\FormRequest;

class BitacoraVehicularRequest extends FormRequest
{

    public $controller_method;
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
        $rules = [
            'fecha' => 'date|required|date_format:Y-m-d',
            'hora_salida' => 'string|required',
            'hora_llegada' => 'nullable|string|sometimes',
            'km_inicial' => 'numeric|required',
            'km_final' => 'string|sometimes|nullable',
            'tanque_inicio' => 'numeric|required',
            'tanque_final' => 'numeric|sometimes|nullable',
            'firmada' => 'boolean|sometimes',
            'chofer_id' => 'sometimes|exists:empleados,id',
            'vehiculo_id' => 'numeric|exists:vehiculos,id',
            'checklistAccesoriosVehiculo' => 'required|array',
            'checklistVehiculo' => 'required|array',
            // 'checklistVehiculo.observacion_checklist_interior' => 'required|string',
            // 'checklistVehiculo.observacion_checklist_bajo_capo' => 'required|string',
            // 'checklistVehiculo.observacion_checklist_exterior' => 'required|string',
            'checklistImagenVehiculo' => 'required|array',
            // 'checklistImagenVehiculo.observacion' => 'required|string',
        ];

        if ($this->controller_method == 'update') {
            $rules['checklistAccesoriosVehiculo.observacion_accesorios_vehiculo'] = 'required|string';
            $rules['checklistVehiculo.observacion_checklist_interior'] = 'required|string';
            $rules['checklistVehiculo.observacion_checklist_bajo_capo'] = 'required|string';
            $rules['checklistVehiculo.observacion_checklist_exterior'] = 'required|string';
            $rules['checklistImagenVehiculo.observacion'] = 'required|string';
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'checklistAccesoriosVehiculo.observacion_accesorios_vehiculo' => 'observacion de accesorios del vehículo',
            'checklistVehiculo.observacion_checklist_interior' => 'observación de interior del vehículo',
            'checklistVehiculo.observacion_checklist_bajo_capo' => 'observación bajo el capó del vehículo',
            'checklistVehiculo.observacion_checklist_exterior' => 'observación de exterior del vehículo',
            'checklistImagenVehiculo.observacion' => 'observación de imágenes del vehículo',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (!is_null($this->km_final))
                if (floatval($this->km_final) < floatval($this->km_inicial)) {

                    $validator->errors()->add('km_final', 'El kilometraje final debe ser superior al km inicial.');
                    $validator->errors()->add('km_final', 'Por favor verifica y corrige el formulario.');
                }
        });
    }

    public function prepareForValidation()
    {
        $this->controller_method = $this->route()->getActionMethod();
        $this->merge([
            'fecha' => date('Y-m-d', strtotime($this->fecha)),
            // 'vehiculo_id' => Vehiculo::where('placa', $this->vehiculo)->first()?->id,
        ]);

        if ($this->controller_method == 'update') {
            $this->merge([
                'checklistAccesoriosVehiculo.bitacora_id' => $this->id,
                'checklistVehiculo.bitacora_id' => $this->id,
                'checklistImagenVehiculo.bitacora_id' => $this->id,
            ]);
        }
    }
}
