<?php

namespace App\Http\Requests\RecursosHumanos\NominaPrestamos;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class LicenciaEmpleadoRequest extends FormRequest
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
            'empleado' => 'nullable',
            'tipo_licencia' => 'required',
            'fecha_inicio' => 'nullable|date_format:Y-m-d',
            'fecha_fin' => 'nullable|date_format:Y-m-d',
            'justificacion' => 'required|string',
            'estado' => 'nullable',
            'tieneDocumento' => 'required',
        ];
    }
    protected function prepareForValidation()
    {
        $fecha_inicio = Carbon::createFromFormat('d-m-Y',$this->fecha_inicio);
        $fecha_fin = Carbon::createFromFormat('d-m-Y',$this->fecha_fin);

        if (is_null($this->empleado)) {
            $empleado = Auth::user()->empleado->id;
            $this->merge([
                'empleado' => $empleado,
            ]);
        }
        $this->merge([
            'fecha_inicio' => $fecha_inicio->format('Y-m-d'),
            'fecha_fin' => $fecha_fin->format('Y-m-d'),
        ]);
    }
}
