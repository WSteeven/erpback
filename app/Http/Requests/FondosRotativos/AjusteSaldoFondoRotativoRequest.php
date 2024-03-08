<?php

namespace App\Http\Requests\FondosRotativos;

use Illuminate\Foundation\Http\FormRequest;

class AjusteSaldoFondoRotativoRequest extends FormRequest
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
            'solicitante_id' => 'required|exists:empleados,id',
            'destinatario_id' => 'required|exists:empleados,id',
            'autorizador_id' => 'required|exists:empleados,id',
            'motivo' => 'required|string',
            'descripcion' => 'required|string',
            'monto' => 'required|numeric|decimal:0,2',
            'tipo' => 'sometimes|nullable',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'solicitante_id' => $this->solicitante,
            'destinatario_id' => $this->destinatario,
            'autorizador_id' => $this->autorizador,
        ]);
    }
}
