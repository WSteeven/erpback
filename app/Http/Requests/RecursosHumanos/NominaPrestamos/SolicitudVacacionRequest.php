<?php

namespace App\Http\Requests\RecursosHumanos\NominaPrestamos;

use App\Models\Autorizacion;
use App\Models\Empleado;
use App\Models\RecursosHumanos\NominaPrestamos\Periodo;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class SolicitudVacacionRequest extends FormRequest
{
    private int $id_patricio_pazmino = 2;
    private int $id_wellington = 117;

    private int $id_veronica_valencia = 155;


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
            'empleado_id' => 'required|exists:empleados,id',
            'autorizador_id' => 'required|exists:empleados,id',
            'periodo_id' => 'required|exists:periodos,id',
            'dias_solicitados' => 'required|numeric',
            'fecha_inicio' => 'required|date_format:Y-m-d',
            'fecha_fin' => 'required|date_format:Y-m-d',
            'autorizacion_id' => 'required|exists:autorizaciones,id',
            'numero_dias' => 'nullable|integer',
            'reemplazo_id' => 'required|exists:empleados,id',
            'funciones' => 'sometimes|nullable|string',
        ];
    }

    protected function prepareForValidation()
    {
        $empleado_id = $this->empleado ?? Auth::user()->empleado->id;
        $autorizador_id = $this->autorizador ?? Empleado::find($empleado_id)->jefe_id;
        if ($autorizador_id == $this->id_patricio_pazmino || $autorizador_id == $this->id_wellington) $autorizador_id = $this->id_veronica_valencia;
        $periodo_id = is_string($this->periodo) ? Periodo::where('nombre', $this->periodo)->first()->id : $this->periodo;
        $this->merge([
            'empleado_id' => $empleado_id,
            'reemplazo_id' => $this->reemplazo,
            'periodo_id' => $periodo_id,
            'autorizacion_id' => $this->autorizacion ?? Autorizacion::PENDIENTE_ID,
            'autorizador_id' => $this->autorizador ?? $autorizador_id,
        ]);
    }
}
