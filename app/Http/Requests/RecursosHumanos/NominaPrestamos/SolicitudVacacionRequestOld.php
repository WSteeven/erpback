<?php

namespace App\Http\Requests\RecursosHumanos\NominaPrestamos;

use App\Models\Empleado;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

/**
 * @property mixed $periodo
 * @property mixed $fecha_inicio
 * @property mixed $fecha_fin
 * @property mixed $descuento_vacaciones
 * @property mixed $fecha_inicio_rango1_vacaciones
 * @property mixed $fecha_fin_rango2_vacaciones
 * @property mixed $reemplazo
 * @property mixed $empleado_id
 * @property mixed $periodo_id
 * @property mixed $numero_dias
 * @property mixed $numero_dias_rango1
 * @property mixed $numero_dias_rango2
 * @property mixed $estado
 */
class SolicitudVacacionRequestOld extends FormRequest
{
    private int $id_wellington;
    private int $id_veronica_valencia;

    public function __construct()
    {
        $this->id_wellington = 117;
        $this->id_veronica_valencia = 155;
    }

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
            'derecho_vacaciones' => 'nullable',
            'fecha_inicio' => 'nullable|date_format:Y-m-d',
            'fecha_fin' => 'nullable|date_format:Y-m-d',
            'fecha_inicio_rango1_vacaciones' => 'nullable|date_format:Y-m-d',
            'fecha_fin_rango1_vacaciones' => 'nullable|date_format:Y-m-d',
            'fecha_inicio_rango2_vacaciones' => 'nullable|date_format:Y-m-d',
            'fecha_fin_rango2_vacaciones' => 'nullable|date_format:Y-m-d',
            'descuento_vacaciones' => 'required|integer',
            'numero_rangos' => 'required|integer',
            'numero_dias' => 'nullable|integer',
            'numero_dias_rango1' => 'nullable|integer',
            'numero_dias_rango2' => 'nullable|integer',
            'reemplazo_id'=>'required|exists:empleados,id',
            'funciones'=>'sometimes|nullable|string',
        ];
    }
    protected function prepareForValidation()
    {
        $empleado_id = $this->empleado ?? Auth::user()->empleado->id;
        $autorizador_id = $this->autorizador ?? Empleado::find($empleado_id)->jefe_id;
        if($autorizador_id == $this->id_wellington) $autorizador_id = $this->id_veronica_valencia;
        $this->merge([
            'empleado_id' => $empleado_id,
            'reemplazo_id'=> $this->reemplazo,
            'autorizador_id'=> $this->autorizador ?? $autorizador_id,
        ]);

        $dateFields = [
            'fecha_inicio_rango1_vacaciones',
            'fecha_fin_rango1_vacaciones',
            'fecha_inicio_rango2_vacaciones',
            'fecha_fin_rango2_vacaciones',
        ];
        foreach ($dateFields as $field) {
            if ($this->$field) {
                $this->merge([
                    $field =>$this->$field,
                ]);
            }
        }
        $this->merge([
            'periodo_id' => $this->periodo,
            'fecha_inicio' => is_null($this->fecha_inicio) ?  $this->fecha_inicio_rango1_vacaciones:$this->fecha_inicio,
            'fecha_fin' =>  is_null($this->fecha_fin) ? $this->fecha_fin_rango2_vacaciones :$this->fecha_fin,
            'descuento_vacaciones' => $this->descuento_vacaciones?$this->descuento_vacaciones:0
        ]);
    }
}
