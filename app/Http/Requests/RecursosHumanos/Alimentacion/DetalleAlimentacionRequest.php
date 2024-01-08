<?php

namespace App\Http\Requests\RecursosHumanos\Alimentacion;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class DetalleAlimentacionRequest extends FormRequest
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
        $reglas =[
            'empleado_id' => 'required',
            'valor_asignado' => 'required',
            'fecha_corte' => 'required',
            'alimentacion_id' => 'required',
        ];
        if ($this->route()->getActionMethod() === 'store') {
            $reglas =[
                'alimentacion_id' => 'required',
            ];
        }
        return $reglas;
    }
    protected function prepareForValidation()
    {
        if ($this->fecha_corte !== null) {
            $fecha_corte = Carbon::parse($this->fecha_corte);
            $this->merge([
                'fecha_corte' => $fecha_corte->format('Y-m-d'),
            ]);
        }
        if($this->empleado != null){
            $this->merge([
                'empleado_id' => $this->empleado
            ]);
        }
    }
}
