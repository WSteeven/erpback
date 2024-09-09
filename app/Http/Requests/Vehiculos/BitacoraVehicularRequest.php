<?php

namespace App\Http\Requests\Vehiculos;

use App\Models\User;
use App\Models\Vehiculos\BitacoraVehicular;
use Illuminate\Foundation\Http\FormRequest;

class BitacoraVehicularRequest extends FormRequest
{

    private string $controllerMethod;

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
            'imagen_inicial' => 'required|string',
            'hora_salida' => 'string|required',
            'hora_llegada' => 'nullable|string|sometimes',
            'km_inicial' => 'numeric|required',
            'km_final' => 'numeric|sometimes|nullable',
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
            'tareas' => 'nullable|array',
            'tickets' => 'nullable|array',
        ];

        if ($this->controllerMethod == 'update') {
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
            if (!is_null($this->km_final)) {
                if (floatval($this->km_final) < floatval($this->km_inicial)) {
                    $validator->errors()->add('km_final', 'El kilometraje final debe ser superior al km inicial.');
                    $validator->errors()->add('km_final', 'Por favor verifica y corrige el formulario.');
                }
            }
            if (!auth()->user()->hasRole(User::ROL_ADMINISTRADOR_VEHICULOS)) { // Si no es administrador de vehiculos, verifica el ultimo KM
                //Verificamos si el km_inicial no es inferior al ultimo registrado
                $ultima_bitacora = BitacoraVehicular::where('vehiculo_id', $this->vehiculo_id)->where('firmada', true)->orderBy('id', 'desc')->first();
                if ($ultima_bitacora) {
                    if ($this->km_inicial < $ultima_bitacora->km_final) {
                        $validator->errors()->add('km_inicial', 'El kilometraje inicial no debe ser superior al último km final. Ultimo km_final: ' . $ultima_bitacora->km_final);
                    }
                }
            }
        });
    }

    public function prepareForValidation()
    {
        $this->controllerMethod = $this->route()->getActionMethod();
        $this->merge([
            'fecha' => date('Y-m-d', strtotime($this->fecha)),
            // 'vehiculo_id' => Vehiculo::where('placa', $this->vehiculo)->first()?->id,
        ]);
        // if (is_null($this->checklistAccesoriosVehiculo['observacion_accesorios_vehiculo']))
        $this->merge(['checklistAccesoriosVehiculo' => array_merge($this->checklistAccesoriosVehiculo, [
            'observacion_accesorios_vehiculo' => 'NINGUNA'
        ])]);

        $this->merge(['checklistVehiculo' => array_merge($this->checklistVehiculo, [
            'observacion_checklist_interior' => 'NINGUNA',
            'observacion_checklist_bajo_capo' => 'NINGUNA',
            'observacion_checklist_exterior' => 'NINGUNA',
        ])]);

        if ($this->controllerMethod == 'update') {
            $this->merge([
                'checklistAccesoriosVehiculo.bitacora_id' => $this->id,
                'checklistVehiculo.bitacora_id' => $this->id,
                'checklistImagenVehiculo.bitacora_id' => $this->id,
            ]);
        }
    }
}
