<?php

namespace App\Http\Requests\RecursosHumanos\SeleccionContratacion;

use Illuminate\Foundation\Http\FormRequest;
use Log;
use Src\Shared\Utils;

class VacanteRequest extends FormRequest
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
            'nombre' => 'required|string',
            'descripcion' => 'required|string',
            'fecha_caducidad' => 'required|string',
            'imagen_referencia' => 'required|string',
            'imagen_publicidad' => 'required|string',
            'anios_experiencia' => 'required|string',
            'areas_conocimiento' => 'required|string',
            'numero_postulantes' => 'required|numeric|integer',
            'tipo_puesto_id' => 'required|exists:rrhh_contratacion_tipos_puestos,id',
            'publicante_id' => 'required|exists:empleados,id',
            'solicitud_id' => 'required|exists:rrhh_contratacion_solicitudes_nuevas_vacantes,id',
            'activo' => 'boolean',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge(['publicante_id' => is_null($this->publicante) ? auth()->user()->empleado->id : $this->publicante]);
        $this->merge([
            'tipo_puesto_id' => $this->tipo_puesto,
            'solicitud_id' => $this->solicitud,
            'areas_conocimiento' => Utils::convertArrayToString($this->areas_conocimiento),
        ]);

        Log::channel('testing')->info('Log', ['despues de formatear', $this->areas_conocimiento]);
    }
}
