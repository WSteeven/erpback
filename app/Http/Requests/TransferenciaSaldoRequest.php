<?php

namespace App\Http\Requests;

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
            'usuario_recibe' => 'required',
            'usuario_envia_id' => 'required',
            'monto' => 'required|numeric',
            'motivo' => 'required|string',
            'cuenta' => 'required|string',
            'tarea' => 'nullable',
            'comprobante' => 'required|string',
            'detalle_estado' => 'nullable|srtring',
        ];
    }
    protected function prepareForValidation()
    {

        $this->merge([
            'usuario_envia_id' =>  Auth()->user()->empleado->id,
        ]);
    }
}
