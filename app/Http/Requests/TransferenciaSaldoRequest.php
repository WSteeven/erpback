<?php

namespace App\Http\Requests;

use App\Models\FondosRotativos\Saldo\SaldoGrupo;
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
        ];
    }
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $saldo_actual = SaldoGrupo::where('id_usuario', Auth()->user()->empleado->id)->orderBy('id', 'desc')->first();
            $saldo_actual = $saldo_actual != null ? $saldo_actual->saldo_actual : 0;
            if ($this->monto > $saldo_actual) {
                $validator->errors()->add('monto', 'El monto a transferir no puede ser mayor al saldo disponible');
            }
        });
    }
    protected function prepareForValidation()
    {
        $date = Carbon::now();
        $this->merge([
            'usuario_envia_id' =>  Auth()->user()->empleado->id,
            'fecha' =>  $date->format('Y-m-d'),
        ]);
    }
}
