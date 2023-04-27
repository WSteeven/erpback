<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class AcreditacionRequest extends FormRequest
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
            'usuario' => 'required',
            'tipo_fondo' => 'required',
            'tipo_saldo' => 'required',
            'monto' => 'required',
            'id_saldo' => 'required',
            'descripcion_acreditacion' => 'required',
        ];
    }
    protected function prepareForValidation()
    {
        $date = Carbon::now();
        $this->merge([
            'fecha' =>  $date->format('Y-m-d'),
        ]);
    }
}
