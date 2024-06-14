<?php

namespace App\Http\Requests\Vehiculos;

use Illuminate\Foundation\Http\FormRequest;

class TanqueoRequest extends FormRequest
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
            'solicitante_id' => 'required|exists:empleados,id',
            'fecha_hora' => 'required|date_format:Y-m-d H:i:s',
            'km_tanqueo' => 'required|integer|numeric',
            'monto' => 'required|numeric',
            'imagen_comprobante' => 'string|nullable',
            'imagen_tablero' => 'string|nullable',
        ];
    }


    public function prepareForValidation()
    {

        if (is_null($this->solicitante_id)) {
            $this->merge([
                'solicitante_id' => auth()->user()->empleado->id,
            ]);
        }
        $this->merge([
            'vehiculo_id' => $this->vehiculo,
        ]);
    }
}
