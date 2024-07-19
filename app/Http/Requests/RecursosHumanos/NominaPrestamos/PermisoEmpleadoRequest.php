<?php

namespace App\Http\Requests\RecursosHumanos\NominaPrestamos;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Log;

class PermisoEmpleadoRequest extends FormRequest
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
            'tipo_permiso' => 'required',
            'fecha_hora_inicio' => 'required|string',
            'fecha_hora_fin' => 'required|string',
            'fecha_recuperacion' => 'nullable|date_format:Y-m-d',
            'hora_recuperacion' => 'nullable|string',
            'justificacion' => 'required|string',
            'observacion' => 'nullable|string',
            'fecha_hora_reagendamiento' => 'nullable|string',
            'empleado_id' => 'nullable|exists:empleados,id',
            'estado' => 'nullable',
            'tieneDocumento' => 'required',
            'cargo_vacaciones' => 'nullable',
            'aceptar_sugerencia' => 'nullable',
            'recupero' => 'boolean'
        ];
    }

    protected function prepareForValidation()
    {
        $mask = 'Y-m-d H:i:s';
        $controller_method = $this->route()->getActionMethod();
        $fecha_inicio = Carbon::createFromFormat($mask, $this->fecha_hora_inicio);
        $fecha_fin = Carbon::createFromFormat($mask, $this->fecha_hora_fin);
        if ($this->fecha_hora_reagendamiento != null) {
//            $fecha_hora_reagendamiento = Carbon::createFromFormat('d-m-Y H:i', $this->fecha_hora_reagendamiento);
            $fecha_hora_reagendamiento = Carbon::createFromFormat($mask, $this->fecha_hora_reagendamiento);
        }
        if ($this->fecha_recuperacion != null) {
            $fecha_recuperacion = $this->fecha_recuperacion;
        }
        //usuario logueado
        if ($this->empleado == null) {
            $this->merge([
                'empleado_id' => auth()->user()->empleado->id,
            ]);
        }
        if ($controller_method == 'store')
            $this->merge([
                'fecha_hora_inicio' => $fecha_inicio,
                'fecha_hora_fin' => $fecha_fin,
            ]);

//        Log::channel('testing')->info('Log', ['PrepareForValidation', gettype($this->fecha_hora_inicio), gettype($this->fecha_hora_fin)]);

        if ($this->fecha_hora_reagendamiento != null) {
            $this->merge([
                'fecha_hora_reagendamiento' => $fecha_hora_reagendamiento ?? null,
            ]);
        }
        if ($this->fecha_recuperacion != null) {
            $this->merge([
                'fecha_recuperacion' => $fecha_recuperacion ?? null,
            ]);
        }


    }
}
