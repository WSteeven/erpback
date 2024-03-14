<?php

namespace App\Http\Requests;

use App\Models\FondosRotativos\Saldo\SaldoGrupo;
use App\Models\User;
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
    protected function prepareForValidation()
    {
        $date = Carbon::now();
        $this->merge([
            'usuario_envia_id' =>  Auth()->user()->empleado->id,
            'fecha' =>  $date->format('Y-m-d'),
        ]);
        $admin_contabilidad = User::whereHas('roles', function ($q) {
            $q->where('name', User::COORDINADOR_CONTABILIDAD);
        })->first();
        $this->tarea == 0 ?  $this->merge(['id_tarea' => null]) :  $this->merge(['id_tarea' => $this->tarea]);
        $this->es_devolucion ? $this->merge(['usuario_recibe_id' => $admin_contabilidad->empleado->id]) : $this->merge(['usuario_recibe_id' => $this->usuario_recibe]);
    }
}
