<?php

namespace App\Http\Requests;

use App\Models\ActivoFijo;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class ActivoFijoRequest extends FormRequest
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
        $rules = [
            'fecha_desde'=>'required|string',
            'fecha_hasta'=>'nullable|string',
            'accion'=>['required', Rule::in([ActivoFijo::ASIGNACION, ActivoFijo::DEVOLUCION])],
            'observacion'=>'nullable|string',
            'lugar'=>'required|string',
            'detalle_id'=>'required|exists:detalles_productos,id|unique:activos_fijos,detalle_id',
            'empleado'=>'required|exists:empleados,id',
            'sucursal'=>'required|exists:sucursales,id',
            'condicion'=>'required|exists:condiciones_de_productos,id',
        ];

        Log::channel('testing')->info('Log', ['activo_fijo_parameter: ', $this->route()->parameter('activo')]);
        if(in_array($this->method(), ['PUT', 'PATCH'])){
            $activo = $this->route()->parameter('activo');

            $rules['detalle_id'] = Rule::unique('activos_fijos')->ignore($activo);
        }

        return $rules;
    }

}
