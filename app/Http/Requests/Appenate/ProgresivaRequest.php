<?php

namespace App\Http\Requests\Appenate;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class ProgresivaRequest extends FormRequest
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
            'metadatos' => 'required',
            'filename' => 'required|string',
            'proyecto' => 'required|string',
            'ciudad' => 'required|string',
            'enlace' => 'required|string',
            'fecha_instalacion' => 'required|string',
            'cod_bobina' => 'required|string',
            'mt_inicial' => 'required|string',
            'mt_final' => 'required|string',
            'fo_instalada' => 'required|string',
            'num_tarea' => 'required|string',
            'hilos' => 'required|string',
            'responsable' => 'required|string',
            'registros_progresivas' => 'required|array',
            'registros_progresivas.*.num_elemento' => 'required|string',
            'registros_progresivas.*.propietario' => 'required|string',
            'registros_progresivas.*.elemento' => 'required|string',
            'registros_progresivas.*.tipo_poste' => 'required|string',
            'registros_progresivas.*.material_poste' => 'required|string',
            'registros_progresivas.*.ubicacion_gps' => 'required|string',
            'registros_progresivas.*.foto' => 'required|string',
            'registros_progresivas.*.observaciones' => 'sometimes|nullable|string',
            'registros_progresivas.*.tiene_control_cambio' => 'boolean',
            'registros_progresivas.*.observacion_cambio' => 'nullable|sometimes|string',
            'registros_progresivas.*.foto_cambio' => 'nullable|sometimes|string',
            'registros_progresivas.*.hora_cambio' => 'nullable|sometimes|string',
            'registros_progresivas.*.materiales' => 'required|array',
            'registros_progresivas.*.materiales.*.material_utilizado' => 'nullable|string',
            'registros_progresivas.*.materiales.*.cantidad' => 'nullable|numeric',
            'registros_progresivas.*.materiales.*.otro_material' => 'nullable|string',


        ];
    }

    public function prepareForValidation()
    {
        Log::channel('testing')->info('Log', ['prepareForValidation::all:', $this->all()]);
        Log::channel('testing')->info('Log', ['prepareForValidation::3:', $this->only('ProviderId', 'Entry')]);

        $entry = $this->Entry;
        unset($entry['AnswersJson']);

        Log::channel('testing')->info('Log', ['lo que se guarda en metadatos:', ['ProviderId' => $this->ProviderId, 'Entry' => $entry]]);
        $this->merge([
            'metadatos' =>$this->all(),// ['ProviderId' => $this->ProviderId, 'Entry' => $entry],
            'filename' => $this->header('filename'),
            'proyecto' => $this->Entry['AnswersJson']['telconet']['proyecto'],
            'ciudad' => $this->Entry['AnswersJson']['telconet']['ciudad'],
            'enlace' => $this->Entry['AnswersJson']['telconet']['enlace'],
            'fecha_instalacion' => $this->Entry['AnswersJson']['telconet']['fecha_instalacion'],
            'cod_bobina' => $this->Entry['AnswersJson']['telconet']['cod_bobina'],
            'mt_inicial' => $this->Entry['AnswersJson']['telconet']['mt_inicial'],
            'mt_final' => $this->Entry['AnswersJson']['telconet']['mt_final'],
            'fo_instalada' => $this->Entry['AnswersJson']['telconet']['fo_instalada'],
            'num_tarea' => $this->Entry['AnswersJson']['telconet']['num_tarea'],
            'hilos' => $this->Entry['AnswersJson']['telconet']['hilos'],
            'responsable' => $this->Entry['AnswersJson']['telconet']['responsable'],
            'registros_progresivas' => $this->procesarRegistrosProgresivas($this->Entry['AnswersJson']['telconet']['registros_progresivas']),

        ]);
    }

    private function procesarRegistrosProgresivas(array $registrosProgresivas)
    {
        return array_map(function ($registro) {
            return array_merge($registro, [
                'num_elemento' => $registro['num_elemento'],
                'propietario' => $registro['propietario'],
                'elemento' => $registro['elemento'],
                'tipo_poste' => $registro['tipo_poste'],
                'material_poste' => $registro['material_poste'],
                'ubicacion_gps' => $registro['ubicacion_gps'],
                'foto' => $registro['foto'],
                'observaciones' => $registro['observaciones'],
                'tiene_control_cambio' => $registro['tiene_control_cambio'] == 'SI',
                'observacion_cambio' => array_key_exists('observacion_cambio', $registro) ? $registro['observacion_cambio'] : null,
                'foto_cambio' => array_key_exists('foto_cambio', $registro) ? $registro['foto_cambio'] : null,
                'hora_cambio' => array_key_exists('hora_cambio', $registro) ? $registro['hora_cambio'] : null,
                'materiales' => $registro['materiales'],
            ]);
        }, $registrosProgresivas);
    }
}
