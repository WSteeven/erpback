<?php

namespace App\Http\Requests;

use App\Models\EstadoTransaccion;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class GastoCoordinadorRequest extends FormRequest
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
            'fecha_gasto' => 'required|date_format:Y-m-d',
            'lugar' => 'required|integer',
            'monto' => 'required|numeric',
            'grupo' => 'required|integer',
            'observacion' => 'required|string',
            'id_usuario' => 'required|integer',
            'id_lugar' => 'required|integer',
            'id_grupo' => 'required|integer',
            'observacion_contabilidad' => 'nullable|string',
            'estado_id'=>'nullable|integer|exists:estados_transacciones_bodega,id',
        ];
    }

    protected function prepareForValidation()
    {
        $user =Auth::user()->empleado->id;
        $this->merge([
            'fecha_gasto' =>  date('Y-m-d'),
            'id_usuario' =>$this->id_usuario?: $user,
            'id_lugar' => $this->lugar,
            'id_grupo' => $this->grupo,
            'estado_id' => $this->estado ?: EstadoTransaccion::PENDIENTE_ID,
        ]);

    }

}
