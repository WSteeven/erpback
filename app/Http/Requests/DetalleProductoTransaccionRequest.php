<?php

namespace App\Http\Requests;

use App\Models\DetalleProducto;
use App\Models\DetalleProductoTransaccion;
use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class DetalleProductoTransaccionRequest extends FormRequest
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
            'inventario_id' => 'required|exists:inventarios,id',
            'transaccion_id' => 'required|exists:transacciones_bodega,id',
            'cantidad_inicial' => 'required|numeric',
            'recibido' => 'required|numeric',
        ];
    }
    protected function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (in_array($this->method(), ['PUT', 'PATCH'])) {
                $detalle = DetalleProductoTransaccion::where('inventario_id', $this->inventario_id)->where('transaccion_id', $this->transaccion_id)->withSum('devoluciones', 'cantidad')->first();
                Log::channel('testing')->info('Log', ['Detalle en funcion de validacion es:', $detalle]);
                if ($detalle->cantidad_inicial < $this->recibido) {
                    $validator->errors()->add('recibido', 'La cantidad ingresada no puede ser mayor a la cantidad inicial');
                } else {
                    if (($this->recibido + $detalle->devoluciones_sum_cantidad) > $this->cantidad_inicial) {
                        $validator->errors()->add('recibido', 'La cantidad que intentas ingresar supera a la cantidad inicial');
                    }
                }
            }
        });
    }
    /* public function prepareForValidation(){
        $detalleProducto = DetalleProductoTransaccion::where('transaccion_id', $this->transaccion_id)
            ->where('detalle_id', $this->detalle_id)->get();
        if($detalleProducto)
    } */
}
