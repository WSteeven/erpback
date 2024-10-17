<?php

namespace App\Http\Requests;

use App\Models\Departamento;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class TransferenciaSaldoRequest extends FormRequest
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
            'fecha' => 'required',
            'usuario_envia_id' => 'required',
            'monto' => 'required|numeric',
            'motivo' => 'required|string',
            'cuenta' => 'required|string',
            'tarea' => 'nullable',
            'comprobante' => 'required|string',
            'detalle_estado' => 'nullable|srtring',
            'observacion' => 'string',
            'usuario_recibe_id' => 'required|exists:empleados,id',
            'id_tarea' => 'nullable|exists:tareas,id',
            'es_devolucion' => 'required'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->route()->getActionMethod() === 'store') {
                if (Auth()->user()->empleado->id === $this->usuario_recibe) {
                    $validator->errors()->add('empleadoEnvia', 'No se puede transferir  a si mismo');
                }
            }
        });
    }

    protected function prepareForValidation()
    {
        $date = Carbon::now();
        $departamento_contabilidad = Departamento::where("nombre", Departamento::DEPARTAMENTO_CONTABILIDAD)->first();
        $responsable_contabilidad = $departamento_contabilidad ? $departamento_contabilidad->responsable_id : 10;
        if ($this->es_devolucion) {
            $this->merge(['usuario_recibe_id' => $responsable_contabilidad]);
        } else {
            $this->merge(['usuario_recibe_id' => $this->usuario_recibe]);
        }
        $this->merge(['fecha' => $date->format('Y-m-d'),]);
        $this->tarea == 0 ? $this->merge(['id_tarea' => null]) : $this->merge(['id_tarea' => $this->tarea]);
        if ($this->route()->getActionMethod() === 'store') {
            $this->merge([
                'usuario_envia_id' => $this->usuario_envia ? $this->usuario_envia: Auth()->user()->empleado->id,
            ]);
        }
        $this->merge([
            'monto' => round($this->monto, 2)
        ]);
    }
}
