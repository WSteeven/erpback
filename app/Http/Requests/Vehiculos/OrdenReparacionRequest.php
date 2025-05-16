<?php

namespace App\Http\Requests\Vehiculos;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Src\App\EmpleadoService;
use Src\Shared\Utils;
use Throwable;

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
            'fecha' => 'required|string',
            'valor_reparacion' => 'nullable|numeric',
            'motivo' => 'nullable|string',
            'num_factura' => 'nullable|string',
        ];
    }

    /**
     * @throws Throwable
     */
    public function prepareForValidation()
    {
        if(auth()->user()->hasRole(User::MECANICO_GENERAL)){
            $this->merge([
                'autorizador_id'=>auth()->user()->empleado->id,
            ]);
        }else{
            $this->merge([
                'autorizador_id' => EmpleadoService::obtenerEmpleadoRolEspecifico(User::ROL_ADMINISTRADOR_VEHICULOS)->id,
            ]);
        }
        $this->merge([
            'autorizacion_id' => $this->autorizacion,
            'vehiculo_id' =>  $this->vehiculo,
            'solicitante_id'=> $this->solicitante,
            'servicios' => Utils::convertArrayToString($this->servicios),
        ]);
    }
}
