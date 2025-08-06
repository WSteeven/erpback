<?php

namespace App\Http\Requests\Seguridad;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Validator;

class BitacoraRequest extends FormRequest
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
        $rules = [
            'fecha_hora_inicio_turno'     => 'nullable',
            'fecha_hora_fin_turno'        => 'nullable|string',
            'jornada'                     => 'required|string',
            'observaciones'               => 'nullable|string',
            'prendas_recibidas_ids'       => 'required|string',
            'zona_id'                     => 'required|numeric|integer|exists:seg_zonas,id',
            'agente_turno_id'             => 'required|numeric|integer|exists:empleados,id',
            'protector_id'                => 'required|numeric|integer|exists:empleados,id',
            'conductor_id'                => 'required|numeric|integer|exists:empleados,id',
            'revisado_por_supervisor'     => 'nullable|boolean',
            'retroalimentacion_supervisor'=> 'nullable|string|max:1000',
        ];

        if ($this->isMethod('patch')) {
            $rules = collect($rules)
                ->only(array_keys($this->all()))
                ->toArray();
        }

        return $rules;
    }

    /**
     * Normaliza/transforma los datos antes de validar.
     */
    protected function prepareForValidation()
    {
        if ($this->isMethod('post')) {
            $this->merge([
                'zona_id'               => $this['zona']          ?? $this->input('zona_id'),
                'agente_turno_id'       => $this['agente_turno']  ?? $this->input('agente_turno_id'),
                'protector_id'          => $this['protector']     ?? $this->input('protector_id'),
                'conductor_id'          => $this['conductor']     ?? $this->input('conductor_id'),
                'prendas_recibidas_ids' => json_encode($this['prendas_recibidas']),
            ]);
        }
    }

    /**
     * Validación adicional: impedir bitácora duplicada por zona + jornada + fecha actual.
     */
    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            if ($this->isMethod('post')) {
                $zonaId  = $this->input('zona_id');
                $jornada = $this->input('jornada');
                $fechaHoy = Carbon::today()->toDateString();

                if ($zonaId && $jornada) {
                    $existe = DB::table('seg_bitacoras')
                        ->where('zona_id', $zonaId)
                        ->where('jornada', $jornada)
                        ->whereDate('created_at', $fechaHoy) 
                        ->exists();

                    if ($existe) {
                        $validator->errors()->add(
                            'jornada',
                            'Ya existe una bitácora para esta zona y jornada hoy.'
                        );
                    }
                }
            }
        });
    }
}
