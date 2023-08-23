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
            'proforma' => 'nullable|sometimes|numeric|exists:cmp_proformas,id',
            'solicitante' => 'required|numeric|exists:empleados,id',
            'cliente' => 'required|numeric|exists:clientes,id',
            'observacion_est' => 'nullable|sometimes|string',
            'descripcion' => 'required|string',
            'forma' => 'required|string',
            'tiempo' => 'required|string',
            'estado' => 'required|numeric|exists:estados_transacciones_bodega,id',
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
    }
}
