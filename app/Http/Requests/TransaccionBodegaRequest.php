<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class TransaccionBodegaRequest extends FormRequest
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
            'autorizacion'=>'required|exists:autorizaciones,id',
            'observacion_aut'=>'nullable|string|sometimes',
            'justificacion'=>'required|string',
            'fecha_limite'=>'nullable|string',
            'estado'=>'required|exists:estados_transacciones_bodega,id',
            'observacion_est'=>'nullable|string|sometimes',
            'solicitante'=>'required|exists:empleados,id',
            'subtipo'=>'required|exists:subtipos_transacciones,id',
            'sucursal'=>'required|exists:sucursales,id',
            'per_autoriza'=>'required|exists:empleados,id',
            'per_atiende'=>'sometimes|exists:empleados,id',
            'lugar_destino'=>'nullable|string',
            'listadoProductosSeleccionados.*.cantidades'=>'required'
        ];
    }

    public function attributes()
    {
        return [
            'listadoProductosSeleccionados.*.cantidades'=>'listado',
        ];
    }
    
    
    public function messages()
    {
        return [
            'listadoProductosSeleccionados.*.cantidades'=>'Debes seleccionar una cantidad para el producto del :attribute',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'solicitante'=>auth()->user()->empleado->id,
        ]);

        if(auth()->user()->hasRole([User::ROL_COORDINADOR, User::ROL_BODEGA, User::ROL_GERENTE])){
            $this->merge([
                'per_autoriza'=>auth()->user()->empleado->id,
            ]);
        }else{
            $this->merge([            
                'per_autoriza'=>auth()->user()->empleado->jefe_id,
            ]);
        }
        //Log::channel('testing')->info('Log', ['Usuario es coordinador?:', auth()->user()->hasRole(User::ROL_COORDINADOR)]);
    }
}
