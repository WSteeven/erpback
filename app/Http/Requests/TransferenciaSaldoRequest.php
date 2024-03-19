<?php

namespace App\Http\Requests;

use App\Models\FondosRotativos\Saldo\SaldoGrupo;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

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
            try {
                if ($this->route()->getActionMethod() === 'store') {
                    if (Auth()->user()->empleado->id === $this->usuario_recibe) {
                        $validator->errors()->add('usuario_envia', 'No se puede transferir  a si mismo');
                    }
                }
            } catch (Exception $e) {
                throw ValidationException::withMessages(['Error al validar gasto' => $e->getMessage()]);
            }
        });
    }
    protected function prepareForValidation()
    {
        $date = Carbon::now();
        $admin_contabilidad = User::whereHas('roles', function ($q) {
            $q->where('name', User::COORDINADOR_CONTABILIDAD);
        })->first();
        $this->es_devolucion ? $this->merge(['usuario_recibe_id' => $admin_contabilidad->empleado->id]) : $this->merge(['usuario_recibe_id' => $this->usuario_recibe]);
        $this->merge(['usuario_envia_id' => $this->usuario_envia, 'fecha' =>  $date->format('Y-m-d'),]);
        $this->tarea == 0 ?  $this->merge(['id_tarea' => null]) :  $this->merge(['id_tarea' => $this->tarea]);
        if ($this->route()->getActionMethod() === 'store') {
            $this->merge([
                'usuario_envia_id' =>  Auth()->user()->empleado->id,
            ]);
        }
    }
}
