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
            'solicitante' => 'required|numeric|exists:empleados,id',
            'proveedor' => 'nullable|numeric|exists:proveedores,id',
            'autorizador' => 'required|numeric|exists:empleados,id',
            'autorizacion' => 'required|numeric|exists:autorizaciones,id',
            'preorden' => 'nullable|sometimes|numeric|exists:cmp_preordenes_compras,id',
            'pedido' => 'nullable|sometimes|numeric|exists:pedidos,id',
            'tarea' => 'nullable|sometimes|numeric|exists:tareas,id',
            'observacion_aut' => 'nullable|sometimes|string',
            'observacion_est' => 'nullable|sometimes|string',
            'descripcion' => 'required|string',
            'forma' => 'nullable|string',
            'tiempo' => 'nullable|string',
            'fecha' => 'required|string',
            'estado' => 'nullable|numeric|exists:estados_transacciones_bodega,id',
            'categorias' => 'sometimes|nullable',
            'iva' => 'required|numeric',
            'listadoProductos.*.cantidad' => 'required',
        ];
    }

    protected function prepareForValidation()
    {
        if(auth()->user()->hasRole(User::ROL_COMPRAS)) $this->merge(['estado'=>1]);
        $this->merge(['fecha' => date('Y-m-d', strtotime($this->fecha))]);
        if ($this->autorizacion === 2 && $this->preorden) $this->merge(['estado' => 1]);

        if ($this->autorizacion === null) $this->merge(['autorizacion' => 1, 'estado' => 1]);
        if ($this->autorizacion === 1) $this->merge(['estado' => 1]);

        if($this->completada)$this->merge(['estado' => 2, 'revisada_compras'=>true]);

        // Modificar los datos cuando es actualizar
        // if ($this->route()->getActionMethod() == 'update') {
        //     if ($this->autorizacion === 2) {
        //         $this->merge(['estado' => 2]);
        //     }
        // }
        if (is_null($this->codigo) || $this->codigo === '') {
            $this->merge(['codigo' => OrdenCompra::obtenerCodigo()]);
        }
    }
}
// "SQLSTATE[23000]: Integrity constraint violation: 1452 Cannot add or update a child row: a foreign key constraint fails
// (`jpconstrucred`.`cmp_item_detalle_orden_compra`, CONSTRAINT `cmp_item_detalle_orden_compra_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE ON U) (SQL:
    // insert into `cmp_item_detalle_orden_compra` (`cantidad`, `created_at`, `descripcion`, `facturable`, `grava_iva`, `iva`, `orden_compra_id`, `porcentaje_descuento`, `precio_unitario`, `producto_id`, `subtotal`, `total`, `updated_at`)
    // values           (1, 2023-11-06 18:52:57, MONITOR ASUS PROART PA247CV LED 24'' FHD GRIS Y NEGRO, 1, 1, 0.0000, 30, 0, 0.0000, 270, 0.0000, 0.0000, 2023-11-06 18:52:57)), 286"
