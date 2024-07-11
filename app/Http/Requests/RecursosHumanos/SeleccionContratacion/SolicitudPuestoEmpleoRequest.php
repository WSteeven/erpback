<?php

namespace App\Http\Requests\RecursosHumanos\SeleccionContratacion;

use App\Models\Autorizacion;
use App\Models\Departamento;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Src\App\EmpleadoService;
use Src\Config\Departamentos;

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
            'nombre' => 'required|string',
            'descripcion' => 'required|string',
            'anios_experiencia' => 'required|string',
            'tipo_puesto_id' => 'required|exists:rrhh_tipos_puestos_trabajos,id',
            'cargo_id' => 'sometimes|nullable|exists:cargos,id',
            'autorizador_id' => 'required|exists:empleados,id',
            'autorizacion_id' => 'required|exists:autorizaciones,id',
        ];
    }

    public function prepareForValidation()
    {
        //Obtenemos el gerente de acuerdo al departamento, no al rol
        $departamento = Departamento::where('nombre', Departamento::DEPARTAMENTO_GERENCIA)->first();
        if (is_null($this->autorizacion)) $this->merge(['autorizador_id' => $departamento->responsable_id]);
        Log::channel('testing')->info('Log', ['request en store', $this->all()]);
        $this->merge([
            'autorizador_id' => $this->autorizador,
            'cargo_id' => $this->puesto,
            'tipo_puesto_id' => $this->tipo_puesto,
        ]);
        if (is_null($this->autorizacion)) {
            $this->merge(['autorizacion_id' => Autorizacion::PENDIENTE_ID]);
        } else {
            $this->merge(['autorizacion_id' => $this->autorizacion]);
        }
    }
}
