<?php

namespace App\Http\Requests;

use App\Models\TransaccionBodega;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ComprobanteRequest extends FormRequest
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
            'transaccion_id'=>'required',
            'firmada'=>'boolean',
            'estado'=>['required', Rule::in([TransaccionBodega::PENDIENTE, TransaccionBodega::ACEPTADA, TransaccionBodega::RECHAZADA])],
            'observacion'=>'nullable|string',
        ];
    }
}
