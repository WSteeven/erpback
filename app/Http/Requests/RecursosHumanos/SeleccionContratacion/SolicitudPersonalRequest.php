<?php

namespace App\Http\Requests\RecursosHumanos\SeleccionContratacion;

use App\Models\Autorizacion;
use App\Models\Departamento;
use Illuminate\Foundation\Http\FormRequest;
use Src\Shared\Utils;

/**
 * Estas propiedades son las llaves foraneas obtenidas desde el front
 * y casteadas en el metodo `prepareForValidation`
 * @property mixed $autorizacion
 * @property mixed $autorizador
 * @property mixed $tipo_puesto
 * @property mixed $cargo
 */
class SolicitudPersonalRequest extends FormRequest
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
            'tipo_puesto_id' => 'required|exists:rrhh_contratacion_tipos_puestos,id',
            'cargo_id' => 'sometimes|nullable|exists:cargos,id',
            'solicitante_id' => 'required|exists:empleados,id',
            'autorizador_id' => 'required|exists:empleados,id',
            'autorizacion_id' => 'required|exists:autorizaciones,id',
            'areas_conocimiento' => 'required|string'
        ];
    }

    public function prepareForValidation()
    {
        $this->merge(['solicitante_id' => is_null($this->solicitante) ? auth()->user()->empleado->id : $this->solicitante]);

        $this->merge([
            'autorizador_id' => $this->autorizador,
            'cargo_id' => $this->cargo,
            'tipo_puesto_id' => $this->tipo_puesto,
            'areas_conocimiento' => Utils::convertArrayToString($this->areas_conocimiento),
        ]);

        //Obtenemos el gerente de acuerdo al departamento, no al rol
        $departamento = Departamento::where('nombre', Departamento::DEPARTAMENTO_GERENCIA)->first();
        $this->merge(['autorizador_id' => $departamento->responsable_id]);

        if (is_null($this->autorizacion)) {
            $this->merge(['autorizacion_id' => Autorizacion::PENDIENTE_ID]);
        } else {
            $this->merge(['autorizacion_id' => $this->autorizacion]);
        }
    }
}
