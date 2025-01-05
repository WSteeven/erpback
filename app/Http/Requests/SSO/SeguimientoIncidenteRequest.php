<?php

namespace App\Http\Requests\SSO;

use Illuminate\Foundation\Http\FormRequest;

class SeguimientoIncidenteRequest extends FormRequest
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
            'causa_raiz' => 'nullable|string',
            'acciones_correctivas' => 'nullable|string',
            'devolucion_id' => 'nullable|numeric|integer',// |exists:devoluciones,id',
            'pedido_id' => 'nullable|numeric|integer', //|exists:pedidos,id',
            'solicitud_descuento_id' => 'nullable|numeric|integer',//|exists:sso_solicitudes_descuentos,id',
//            'incidente_id' => 'required|numeric|integer|exists:sso_incidentes,id',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'devolucion_id' => $this['devolucion'],
            'pedido_id' => $this['pedido'],
            'solicitud_descuento_id' => $this['solicitud_descuento'],
//            'incidente_id' => $this['incidente'],
        ]);
    }
}
