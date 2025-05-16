<?php

namespace App\Http\Requests\ComprasProveedores;

use App\Models\ComprasProveedores\Prefactura;
use Illuminate\Foundation\Http\FormRequest;

class PrefacturaRequest extends FormRequest
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
            'codigo' => 'required|string',
            'solicitante_id' => 'required|numeric|exists:empleados,id',
            'cliente_id' => 'required|numeric|exists:clientes,id',
            'estado_id' => 'required|numeric|exists:estados_transacciones_bodega,id',
            'proforma_id' => 'nullable|sometimes|numeric|exists:cmp_proformas,id',
            'observacion_est' => 'nullable|sometimes|string',
            'descuento_general' => 'nullable|sometimes|numeric',
            'descripcion' => 'required|string',
            'forma' => 'required|string',
            'tiempo' => 'required|string',
            'iva' => 'required|numeric',
            'listadoProductos.*.cantidad' => 'required',
        ];
    }

    protected function prepareForValidation()
    {
        if (is_null($this->estado) || $this->estado === '')
            $this->merge(['estado' => 2]);
        if (is_null($this->codigo) || $this->codigo === '') {
            $this->merge(['codigo' => Prefactura::obtenerCodigo()]);
        }
        $this->merge([
            'solicitante_id' => $this->solicitante,
            'cliente_id' => $this->cliente,
            'estado_id' => $this->estado,
            'proforma_id' => $this->proforma ?? null,
        ]);
    }
}
