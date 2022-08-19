<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransaccionBodegaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'autorizacion_id'=>'required|exists:autorizaciones,id',
            'observacion'=>'nullable|string',
            'fecha_limite'=>'nullable|date',
            'estado_id'=>'required|exists:estados_transacciones_bodega,id',
            'solicitante_id'=>'required|exists:users,id',
            'tipo_id'=>'required|exists:tipo_de_transacciones,id',
            'sucursal_id'=>'required|exists:sucursales,id',
            'per_autoriza_id'=>'required|exists:users,id',
            'per_entrega_id'=>'nullable|exists:users,id',
            'lugar_destino'=>'nullable|string',
        ];
    }
}
