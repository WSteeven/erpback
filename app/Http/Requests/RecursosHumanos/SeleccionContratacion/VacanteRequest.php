<?php

namespace App\Http\Requests\RecursosHumanos\SeleccionContratacion;

use Illuminate\Foundation\Http\FormRequest;
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
            'anios_experiencia' => 'sometimes|nullable|string',
            'areas_conocimiento' => 'required|string',
            'numero_postulantes' => 'required|numeric|integer',
            'tipo_puesto_id' => 'required|exists:rrhh_contratacion_tipos_puestos,id',
            'modalidad_id' => 'required|exists:rrhh_contratacion_modalidades,id',
            'publicante_id' => 'required|exists:empleados,id',
            'solicitud_id' => 'sometimes|nullable|exists:rrhh_contratacion_solicitudes_nuevas_vacantes,id',
            'disponibilidad_viajar' => 'boolean',
            'acepta_discapacitados' => 'boolean',
            'requiere_licencia' => 'boolean',
            'activo' => 'boolean',
            'canton_id'=>'required|exists:cantones,id',
            'num_plazas'=>'required|numeric|min:1',
            'edad_personalizada'=> 'required'
        ];
    }

    public function prepareForValidation()
    {
        $this->merge(['publicante_id' => is_null($this->publicante) ? auth()->user()->empleado->id : $this->publicante]);
        $this->merge([
            'canton_id' => $this->canton,
            'tipo_puesto_id' => $this->tipo_puesto,
            'modalidad_id' => $this->modalidad,
            'solicitud_id' => $this->solicitud,
            'areas_conocimiento' => Utils::convertArrayToString($this->areas_conocimiento),
        ]);

//        Log::channel('testing')->info('Log', ['despues de formatear', $this->areas_conocimiento]);
    }
}
