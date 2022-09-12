<?php

namespace App\Http\Requests;

use App\Models\TipoTransaccion;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TipoTransaccionRequest extends FormRequest
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
            'nombre' => 'required|string|unique:tipo_de_transacciones,nombre', 
            'tipo'=>Rule::in(TipoTransaccion::INGRESO, TipoTransaccion::EGRESO),
        ];
    }
}
