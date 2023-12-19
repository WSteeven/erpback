<?php

namespace App\Http\Requests\Ventas;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

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
            'fecha_inicio'=> 'required',
            'fecha_fin'=> 'required',
            'vendedor_id'=> 'nullable|integer',
            'chargeback' => 'nullable',
            'valor' => 'nullable'
        ];
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
