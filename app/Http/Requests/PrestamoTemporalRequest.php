<?php

namespace App\Http\Requests;

use App\Models\PrestamoTemporal;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule as ValidationRule;

class PrestamoTemporalRequest extends FormRequest
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
            'fecha_salida'=>'required|string',
            'fecha_devolucion'=>'nullable|string',
            'observacion'=>'nullable|string|sometimes',
            'solicitante'=>'required|exists:empleados,id',
            'per_entrega'=>'required|exists:empleados,id',
            'per_recibe'=>'nullable|sometimes|exists:empleados,id',
            'listadoProductos.*.cantidad'=>'required',
            'estado'=>['required', Rule::in([PrestamoTemporal::PENDIENTE, PrestamoTemporal::DEVUELTO])],
        ];
    }

    public function attributes()
    {
        return [
            'listadoProductos.*.cantidad'=>'listado',
        ];
    }
    public function messages()
    {
        return [
            'listadoProductos.*.cantidad'=>'Debes seleccionar una cantidad para el producto del :attribute',
        ];
    }

    protected function prepareForValidation()
    {
        if(!is_null($this->fecha_salida)){
            $this->merge([
                'fecha_salida'=>date('Y-m-d', strtotime($this->fecha_salida)),
            ]);
        }
        if(!is_null($this->fecha_devolucion)){
            $this->merge([
                'fecha_devolucion'=>date('Y-m-d', strtotime($this->fecha_devolucion)),
            ]);
        }
        $this->merge([
            'per_entrega'=>auth()->user()->empleado->id
        ]);
    }
}
