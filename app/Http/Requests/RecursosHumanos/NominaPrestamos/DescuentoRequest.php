<?php

namespace App\Http\Requests\RecursosHumanos\NominaPrestamos;

use Illuminate\Foundation\Http\FormRequest;

class DescuentoRequest extends FormRequest
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
            'fecha_descuento'=>'required|string',
            'empleado_id'=>'required|exists:empleados,id',
            'tipo_descuento_id'=>'sometimes|nullable|exists:descuentos_generales,id',
            'multa_id'=>'sometimes|nullable|exists:multas,id',
            'descripcion'=>'required|string',
            'valor'=>'required|decimal:2',
            'cantidad_cuotas'=>'required|integer',
            'mes_inicia_cobro'=>'required|string',
            'pagado'=>'boolean',
            'cuotas'=>'array',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
           'empleado_id'=> $this->empleado,
            'tipo_descuento_id'=> $this->tipo_descuento,
            'multa_id'=>$this->multa
        ]);
    }
}
