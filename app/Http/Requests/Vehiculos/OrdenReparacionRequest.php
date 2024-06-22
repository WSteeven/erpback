<?php

namespace App\Http\Requests\Vehiculos;

use App\Models\User;
use App\Models\Vehiculos\Vehiculo;
use Illuminate\Foundation\Http\FormRequest;
use Src\App\EmpleadoService;
use Src\Shared\Utils;

class OrdenReparacionRequest extends FormRequest
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
            'solicitante_id' => 'required',
            'autorizador_id' => 'required',
            'autorizacion_id' => 'required',
            'vehiculo_id' => 'required',
            'servicios' => 'required|string',
            'observacion' => 'required|string',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'autorizador_id' => EmpleadoService::obtenerEmpleadoRolEspecifico(User::ROL_ADMINISTRADOR_VEHICULOS)->id,
            'autorizacion_id' => $this->autorizacion,
            'vehiculo_id' => Vehiculo::where('placa', $this->vehiculo)->first()?->id,
            'servicios' => Utils::convertArrayToString($this->servicios),
        ]);
    }
}
