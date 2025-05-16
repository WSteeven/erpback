<?php

namespace App\Http\Requests\ComprasProveedores;

use App\Models\ComprasProveedores\Proforma;
use Illuminate\Foundation\Http\FormRequest;

class ProformaRequest extends FormRequest
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
            'autorizador_id' => 'required|numeric|exists:empleados,id',
            'autorizacion_id' => 'required|numeric|exists:autorizaciones,id',
            'preorden' => 'nullable|sometimes|numeric|exists:cmp_preordenes_compras,id',
            'pedido' => 'nullable|sometimes|numeric|exists:pedidos,id',
            'descuento_general' => 'nullable|sometimes|numeric',
            'observacion_aut' => 'nullable|sometimes|string',
            'observacion_est' => 'nullable|sometimes|string',
            'descripcion' => 'required|string',
            'forma' => 'required|string',
            'tiempo' => 'required|string',
            'estado_id' => 'required|numeric|exists:estados_transacciones_bodega,id',
            'iva' => 'required|numeric',
            'listadoProductos.*.cantidad' => 'required',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->autorizacion === 2) $this->merge(['estado' => 1]);
        if ($this->autorizacion === null)$this->merge(['autorizacion' => 1, 'estado' => 1]);
        if($this->autorizacion===1) $this->merge(['estado' => 1]);

        if (is_null($this->codigo) || $this->codigo === '') {
            $this->merge(['codigo' => Proforma::obtenerCodigo()]);
        }

        $this->merge([
            'solicitante_id' => $this->solicitante,
            'cliente_id' => $this->cliente,
            'autorizador_id' => $this->autorizador,
            'autorizacion_id' => $this->autorizacion,
            'estado_id' => $this->estado,
        ]);
    }
}
