<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class PedidoRequest extends FormRequest
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
            'justificacion' => 'required|string',
            'fecha_limite' => 'nullable|date',
            'observacion_aut' => 'nullable|string',
            'observacion_est' => 'nullable|string',
            'solicitante' => 'required|numeric|exists:empleados,id',
            'responsable' => 'required|numeric|exists:empleados,id',
            'autorizacion' => 'required|numeric|exists:autorizaciones,id',
            'per_autoriza' => 'required|numeric|exists:empleados,id',
            'tarea' => 'sometimes|nullable|numeric|exists:tareas,id',
            'sucursal' => 'required|numeric|exists:sucursales,id',
            'estado' => 'required|numeric|exists:estados_transacciones_bodega,id',
            'listadoProductos.*.cantidad' => 'required',
        ];
    }
    public function attributes()
    {
        return [
            'listadoProductos.*.cantidad' => 'listado',
        ];
    }
    public function messages()
    {
        return [
            'listadoProductos.*.cantidad' => 'Debes seleccionar una cantidad para el producto del :attribute',
        ];
    }

    public function withValidator($validator){
        $validator->after(function ($validator){
            if(!is_null($this->fecha_limite)){
                if(date('Y-m-d', strtotime($this->fecha_limite))<now()){
                    $validator->errors()->add('fecha_limite', 'La fecha límite debe ser superior a la fecha actual');
                }
            }
        });
    }
    protected function prepareForValidation()
    {
        $user_activo_fijo = User::whereHas("roles", function($q){ $q->where("name", User::ROL_ACTIVOS_FIJOS); })->first();
        Log::channel('testing')->info('Log', ['el activo fijo es:', $user_activo_fijo]);
        if(!is_null($this->fecha_limite)){
            $this->merge([
                'fecha_limite' => date('Y-m-d', strtotime($this->fecha_limite)),
            ]);
        }
        if (is_null($this->solicitante) || $this->solicitante === '') {
            $this->merge(['solicitante' => auth()->user()->empleado->id]);
        }
        if (is_null($this->per_autoriza) || $this->per_autoriza === '') {
            $this->merge(['per_autoriza' => auth()->user()->empleado->jefe_id]);
        }
        if (is_null($this->autorizacion) || $this->autorizacion === '') {
            $this->merge(['autorizacion' => 1]);
        }
        if (is_null($this->estado) || $this->estado === '') {
            $this->merge(['estado' => 1]);
        }

        // if (auth()->user()->hasRole([User::ROL_COORDINADOR, User::ROL_BODEGA, User::ROL_GERENTE, User::ROL_ACTIVOS_FIJOS])) {
        //     $this->merge([
        //         // 'autorizacion' => 2,
        //         'per_autoriza' => auth()->user()->empleado->id,
        //     ]);
        // }
        if(auth()->user()->hasRole([User::ROL_RECURSOS_HUMANOS, User::ROL_SSO])){
            $this->merge([
                'per_autoriza'=>$user_activo_fijo->empleado->id,
            ]);
        }

        if(is_null($this->responsable)){
            $this->merge(['responsable'=>$this->solicitante]);
        }
        if($this->autorizacion===3){
            $this->merge([
                'estado'=>4,
                'observacion_est'=>'NO REALIZADO'
            ]);
        }
    }
}
