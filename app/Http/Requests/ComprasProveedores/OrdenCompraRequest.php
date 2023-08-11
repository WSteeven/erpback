<?php

namespace App\Http\Requests\ComprasProveedores;

use Illuminate\Foundation\Http\FormRequest;

class OrdenCompraRequest extends FormRequest
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
            'solicitante' => 'required|numeric|exists:empleados,id',
            'proveedor' => 'required|numeric|exists:proveedores,id',
            'autorizador' => 'required|numeric|exists:empleados,id',
            'autorizacion' => 'required|numeric|exists:autorizaciones,id',
            'preorden' => 'nullable|sometimes|numeric|exists:cmp_preordenes_compras,id',
            'pedido' => 'nullable|sometimes|numeric|exists:pedidos,id',
            'observacion_aut' => 'nullable|sometimes|string',
            'observacion_est' => 'nullable|sometimes|string',
            'descripcion' => 'required|string',
            'forma' => 'required|string',
            'tiempo' => 'required|string',
            'fecha' => 'required|string',
            'estado' => 'required|numeric|exists:estados_transacciones_bodega,id',
            'categorias' => 'sometimes|nullable',
            'listadoProductos.*.cantidad' => 'required',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->autorizacion === 2 && $this->preorden) {
            $this->merge(['estado' => 2]);
        }
        if ($this->autorizacion === null)
            $this->merge(['autorizacion' => 1, 'estado' => 1]);
        $this->merge(['fecha' => date('Y-m-d', strtotime($this->fecha))]);

        // Modificar los datos cuando es actualizar
        if ($this->route()->getActionMethod() == 'update') {
            if ($this->autorizacion === 2) {
                $this->merge(['estado' => 2]);
            }
        }
    }
}
