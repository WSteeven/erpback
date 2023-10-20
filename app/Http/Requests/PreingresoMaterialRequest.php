<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class PreingresoMaterialRequest extends FormRequest
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
            'observacion' => 'string|nullable',
            'cuadrilla' => 'string',
            'num_guia' => 'string',
            'courier' => 'string',
            'fecha' => 'string',
            'tarea' =>  'sometimes|nullable|numeric|exists:tareas,id',
            'cliente' => 'sometimes|nullable|numeric|exists:clientes,id',
            'autorizador' => 'nullable|sometimes|numeric|exists:empleados,id',
            'responsable' => 'required',
            'coordinador' => 'required|numeric|exists:empleados,id',
            'autorizacion' => 'sometimes|numeric|exists:autorizaciones,id',
            'listadoProductos.*.cantidad' => 'required',
        ];
    }
    

    protected function prepareForValidation()
    {
        $userCoordinadorBodega = User::whereHas('roles', function ($q) {
            $q->where('name', User::ROL_COORDINADOR_BODEGA);
        })->first();

        $this->merge([
            'fecha' => date('Y-m-d', strtotime($this->fecha))
        ]);

        if (is_null($this->tarea)) { //si no hay tarea, el autorizador es el coordinador de bodega y los materiales se cargarán al stock del tecnico
            $this->merge([
                'autorizador' => $userCoordinadorBodega->empleado->id
            ]);
        } else { //caso contrario, el material se asignará al stock de tarea y el autorizador es el coordinador
            $this->merge([
                'autorizador' => $this->coordinador
            ]);
        }
    }
}
