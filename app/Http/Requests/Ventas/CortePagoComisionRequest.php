<?php

namespace App\Http\Requests\Ventas;

use App\Models\EstadoTransaccion;
use Illuminate\Foundation\Http\FormRequest;

class CortePagoComisionRequest extends FormRequest
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
            'nombre' => 'required',
            'fecha_inicio' => 'required',
            'fecha_fin' => 'required',
            'estado' => 'required',
        ];
    }

    protected function prepareForValidation()
    {
        if (is_null($this->estado)) $this->merge(['estado' => EstadoTransaccion::PENDIENTE]);
    }
}
