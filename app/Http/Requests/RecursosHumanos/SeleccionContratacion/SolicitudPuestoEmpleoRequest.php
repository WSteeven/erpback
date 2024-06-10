<?php

namespace App\Http\Requests\RecursosHumanos\SeleccionContratacion;

use App\Models\Autorizacion;
use Illuminate\Foundation\Http\FormRequest;

class SolicitudPuestoEmpleoRequest extends FormRequest
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
            'descripcion' => 'required|string',
            'anos_experiencia' => 'required|numeric|integer',
            'tipo_puesto_id' => 'required|exists:rrhh_tipos_puestos_trabajos,id',
            'cargo_id' => 'required|exists:cargos,id',
            'autorizacion_id' => 'required|exists:autorizaciones,id',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge(['cargo_id' => $this->puesto, 'tipo_puesto_id' => $this->tipo_puesto]);
        if (is_null($this->autorizacion)) {
            $this->merge(['autorizacion_id' => Autorizacion::PENDIENTE_ID]);
        }else{
            $this->merge(['autorizacion_id' => $this->autorizacion]);

        }
    }
}
