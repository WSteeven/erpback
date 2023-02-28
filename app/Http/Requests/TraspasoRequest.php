<?php

namespace App\Http\Requests;

use App\Models\DetalleInventarioTraspaso;
use App\Models\DetalleProducto;
use App\Models\EstadoTransaccion;
use App\Models\Inventario;
use App\Models\Producto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class TraspasoRequest extends FormRequest
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
        $rules = [
            'justificacion' => 'string|sometimes|nullable',
            'devuelta' => 'sometimes|boolean',
            'solicitante' => 'required|exists:empleados,id',
            'desde_cliente' => 'required|exists:clientes,id',
            'hasta_cliente' => 'required|exists:clientes,id',
            'tarea' => 'sometimes|nullable|exists:tareas,id',
            'sucursal' => 'required|exists:sucursales,id',
            'estado' => 'sometimes|nullable|exists:estados_transacciones_bodega,id',
            'listadoProductos.*.cantidades' => 'required',
        ];
        // if (in_array($this->method(), ['PUT', 'PATCH'])) {
        //     $rules['listadoProductos.*.devolucion'] = 'required';
        // }

        return $rules;
    }

    public function attributes()
    {
        return [
            'listadoProductos.*.cantidades' => 'cantidad',
            // 'listadoProductos.*.devolucion' => 'devolucion',
        ];
    }
    public function messages()
    {
        return [
            'listadoProductos.*.cantidades' => 'Debes seleccionar una cantidad para el producto del listado',
            // 'listadoProductos.*.devolucion' => 'Debes seleccionar una cantidad de devolucion para el producto del listado',
        ];
    }
    protected function withValidator($validator)
    {
        Log::channel('testing')->info('Log', ['withValidator de Traspaso:', $this->all()]);
        $validator->after(function ($validator) {
            foreach ($this->listadoProductos as $listado) {
                if (in_array($this->method(), ['PUT', 'PATCH'])) {
                    if (($listado['devolucion'] + $listado['devuelto']) > $listado['cantidades']) {
                        $validator->errors()->add('listadoProductos.*.cantidades', 'La cantidad de devolución del item ' . $listado['producto'] . ' no puede ser mayor a la cantidad prestada.');
                    }
                    //Consultar si la cantidad a devolver existe en el inventario o no
                    $producto = Producto::where('nombre', $listado['producto'])->first();
                    Log::channel('testing')->info('Log', ['producto en Traspaso:', $producto]);
                    $detalle = DetalleProducto::where('producto_id', $producto->id)->where('descripcion', $listado['detalle_id'])->first();
                    Log::channel('testing')->info('Log', ['detalle en Traspaso:', $detalle]);
                    $itemInventario = Inventario::where('detalle_id', $detalle->id)->where('sucursal_id', $this->sucursal)->where('cliente_id', $this->hasta_cliente)->first();
                    Log::channel('testing')->info('Log', ['item del inventario en Traspaso:', $itemInventario]);
                    if($listado['devolucion']>$itemInventario->cantidad){
                        $validator->errors()->add('listadoProductos.*.cantidades', 'La cantidad de devolución del item '. $listado['producto'].' no puede ser mayor a la cantidad existente en inventario');
                    }
                } else {
                    if ($listado['cantidades'] > $listado['cantidad']) {
                        $validator->errors()->add('listadoProductos.*.cantidades', 'La cantidad del item ' . $listado['producto'] . ' no puede ser mayor a la cantidad del inventario.');
                        $validator->errors()->add('listadoProductos.*.cantidades', 'En inventario:' . $listado['cantidad']);
                    }
                }
            }
            /* if ($this->desde_cliente === $this->hasta_cliente) {
                $validator->errors()->add('hasta_cliente', 'No se puede hacer traspaso al mismo cliente.');
            } */
        });
    }


    protected function prepareForValidation()
    {
        $this->merge([
            'solicitante' => auth()->user()->empleado->id,
            'devuelta' => false,
            'estado' => 1
        ]);

        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            foreach ($this->listadoProductos as $listado) {
                // $detalle = DetalleInventarioTraspaso::withSum('devoluciones', 'cantidad')->where('traspaso_id',$item->pivot->traspaso_id)->where('inventario_id', $item->pivot->inventario_id)->first();
                $completa = $listado['cantidades'] == ($listado['devolucion'] + $listado['devuelto']) ? true : false;
                if (is_null($listado['devolucion'])) {
                    $this->merge([
                        $listado['devolucion'] => 0
                    ]);
                }
            }
            if ($completa) {
                $this->merge([
                    'estado' => 2,
                    'devuelta' => true,
                ]);
            } else {
                $this->merge([
                    'estado' => 3,
                ]);
            }
        }
    }
}
