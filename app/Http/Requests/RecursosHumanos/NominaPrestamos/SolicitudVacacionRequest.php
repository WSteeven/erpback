<?php

namespace App\Http\Requests\RecursosHumanos\NominaPrestamos;

use App\Models\Autorizacion;
use App\Models\Empleado;
use App\Models\RecursosHumanos\NominaPrestamos\Periodo;
use App\Models\RecursosHumanos\NominaPrestamos\Vacacion;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Src\App\RecursosHumanos\NominaPrestamos\VacacionService;
use Src\Shared\Utils;

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
            'dias_solicitados' => 'required|numeric|min:1',
            'fecha_inicio' => 'required|date_format:Y-m-d',
            'fecha_fin' => 'required|date_format:Y-m-d',
            'autorizacion_id' => 'required|exists:autorizaciones,id',
            'numero_dias' => 'nullable|integer',
            'reemplazo_id' => 'required|exists:empleados,id',
            'funciones' => 'sometimes|nullable|string',
        ];
    }

    public function attributes()
    {
        return [
            'dias_solicitados' => 'Dias que voy a tomar',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Primero consultamos si es empleado nuevo
            $empleado = Empleado::find($this->empleado_id);
            if (Vacacion::where('empleado_id', $empleado->id)->where('periodo_id', $this->periodo_id)->exists()) {
                if (!VacacionService::validarDiasDisponibles($this->empleado_id, $this->periodo_id, $this->fecha_inicio, $this->fecha_fin))
                    $validator->errors()->add('dias_solicitados', 'La cantidad de días que estás solicitando es mayor a la cantidad de días disponibles para vacaciones, por favor ingresa una cantidad inferior.');
            } else {
                // Significa que aún no tiene un año de labores o no tiene registro de vacaciones
                $dias_permitidos_nuevo_empleado = VacacionService::calcularDiasDeVacacionEmpleadoNuevo($empleado);
                if (Utils::calcularDiasTranscurridos($this->fecha_inicio, $this->fecha_fin) > $dias_permitidos_nuevo_empleado)
                    $validator->errors()->add('dias_solicitados', 'La cantidad de días que estás solicitando es mayor a la cantidad de días disponibles para vacaciones, por favor ingresa una cantidad inferior o igual a ' . $dias_permitidos_nuevo_empleado . '.');
            }
        });
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
