<?php

namespace App\Http\Requests\Ventas;

use App\Models\Ventas\PagoComision;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class PagoComisionRequest extends FormRequest
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
            'fecha_inicio' => 'required',
            'fecha_fin' => 'required',
            'vendedor_id' => 'nullable|integer',
            'chargeback' => 'nullable',
            'valor' => 'nullable'
        ];
    }
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $validarPagoComision = PagoComision::select('fecha_fin')->latest()->first();
            $fecha_inicio = new Carbon($this->fecha_inicio);
            $fecha_fin = new Carbon($this->fecha_fin);
            $fecha_fin_db = $validarPagoComision != null? new Carbon($validarPagoComision->fecha_fin):new Carbon();
            if ($validarPagoComision != null) {
                if ($fecha_inicio->lt($fecha_fin_db)) {
                    $validator->errors()->add('fecha', 'La fecha de Inicio no puede ser menor a: '.$validarPagoComision->fecha_fin);
                }
                $pagoComision = PagoComision::where('fecha_inicio', $fecha_inicio)->where('fecha_fin', $fecha_fin)->get()->count();
                if ($pagoComision > 0) {
                    $validator->errors()->add('fecha', 'Ya se ha realizado Pago de comisiones en rango de fechas indicadas');
                }
            }
        });
    }
    protected function prepareForValidation()
    {
        $this->merge([
            'vendedor_id' => $this->vendedor,
            'fecha_inicio' =>   Carbon::createFromFormat('d-m-Y', $this->fecha_inicio)->format('Y-m-d'),
            'fecha_fin' =>   Carbon::createFromFormat('d-m-Y', $this->fecha_fin)->format('Y-m-d'),

        ]);
    }
}
