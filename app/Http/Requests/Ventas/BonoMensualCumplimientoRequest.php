<?php

namespace App\Http\Requests\Ventas;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class BonoMensualCumplimientoRequest extends FormRequest
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
            'vendedor_id'=> 'required|integer',
            'cant_ventas'=> 'required|integer',
            'mes' => 'required',
            'bono_id' => 'required',
            'valor' => 'required'
        ];
    }
    protected function prepareForValidation()
    {
        $date = Carbon::createFromFormat('m-Y', $this->mes);
        $this->merge([
            'vendedor_id' => $this->vendedor,
            'bono_id' => $this->bono,
            'mes' =>  $date->format('Y-m'),
        ]);
    }
}
