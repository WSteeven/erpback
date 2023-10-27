<?php

namespace App\Http\Requests\Ventas;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class ChargebacksRequest extends FormRequest
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

            'venta_id' => 'required',
            'fecha' => 'required',
            'valor' => 'required',
            'id_tipo_chargeback' => 'required',
            'porcentaje' => 'nullable',
        ];
    }
    protected function prepareForValidation()
    {
        $date = Carbon::createFromFormat('d-m-Y', $this->fecha);
        $this->merge([
            'venta_id' => $this->venta,
            'fecha' => $date->format('Y-m-d'),
            'id_tipo_chargeback' => $this->tipo_chargeback,
        ]);
    }
}
