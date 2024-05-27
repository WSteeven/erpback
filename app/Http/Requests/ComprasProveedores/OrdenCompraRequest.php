<?php

namespace App\Http\Requests\ComprasProveedores;

use App\Models\ComprasProveedores\OrdenCompra;
use App\Models\User;
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
            'codigo' => 'required|string',
            'solicitante_id' => 'required|numeric|exists:empleados,id',
            'proveedor_id' => 'nullable|numeric|exists:proveedores,id',
            'autorizador_id' => 'required|numeric|exists:empleados,id',
            'autorizacion_id' => 'required|numeric|exists:autorizaciones,id',
            'preorden_id' => 'nullable|sometimes|numeric|exists:cmp_preordenes_compras,id',
            'pedido_id' => 'nullable|sometimes|numeric|exists:pedidos,id',
            'tarea_id' => 'nullable|sometimes|numeric|exists:tareas,id',
            'observacion_aut' => 'nullable|sometimes|string',
            'observacion_est' => 'nullable|sometimes|string',
            'descripcion' => 'required|string',
            'forma' => 'nullable|string',
            'tiempo' => 'nullable|string',
            'fecha' => 'required|string',
            'estado_id' => 'nullable|numeric|exists:estados_transacciones_bodega,id',
            'categorias' => 'sometimes|nullable',
            'iva' => 'required|numeric',
            'listadoProductos.*.cantidad' => 'required',
        ];
    }

    protected function prepareForValidation()
    {
        if (auth()->user()->hasRole(User::ROL_COMPRAS)) $this->merge(['estado' => 1]);
        $this->merge(['fecha' => date('Y-m-d', strtotime($this->fecha))]);
        if ($this->autorizacion === 2 && $this->preorden) $this->merge(['estado' => 1]);

        if ($this->autorizacion === null) $this->merge(['autorizacion' => 1, 'estado' => 1]);
        if ($this->autorizacion === 1) $this->merge(['estado' => 1]);

        if ($this->completada) $this->merge(['estado' => 2, 'revisada_compras' => true]);

        // Modificar los datos cuando es actualizar
        // if ($this->route()->getActionMethod() == 'update') {
        //     if ($this->autorizacion === 2) {
        //         $this->merge(['estado' => 2]);
        //     }
        // }
        if (is_null($this->codigo) || $this->codigo === '') {
            $this->merge(['codigo' => OrdenCompra::obtenerCodigo()]);
        }
        $this->merge([
            'solicitante_id' => $this->solicitante,
            'proveedor_id' => $this->proveedor,
            'autorizador_id' => $this->autorizador,
            'autorizacion_id' => $this->autorizacion,
            'estado_id' => $this->estado,
        ]);
        if ($this->preorden) $this->merge(['preorden_id' => $this->preorden]);
        if ($this->pedido) $this->merge(['pedido_id' => $this->pedido]);
        if ($this->tarea) $this->merge(['tarea_id' => $this->tarea]);
        if (count($this->categorias) == 0) {
            $this->merge(['categorias' => null]);
        } else {
            $this->merge(['categorias' => implode(',', $this->categorias)]);
        }
    }
}
