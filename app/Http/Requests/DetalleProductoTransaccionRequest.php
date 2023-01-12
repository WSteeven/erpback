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
            'detalle_id' => 'required|exists:detalles_productos,id',
            'transaccion_id' => 'required|exists:transacciones_bodega,id',
            'cantidad_inicial' => 'required|numeric',
            'cantidad_final' => 'required|numeric',
        ];
    }
    protected function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (in_array($this->method(), ['PUT', 'PATCH'])) {
                $detalle = DetalleProductoTransaccion::where('detalle_id', $this->detalle_id)->where('transaccion_id', $this->transaccion_id)->withSum('devoluciones', 'cantidad')->first();
                Log::channel('testing')->info('Log', ['Detalle en funcion de validacion es:', $detalle]);
                if ($detalle->cantidad_inicial < $this->cantidad_final) {
                    $validator->errors()->add('cantidad_final', 'La cantidad ingresada no puede ser mayor a la cantidad inicial');
                } else {
                    if (($this->cantidad_final + $detalle->devoluciones_sum_cantidad) > $this->cantidad_inicial) {
                        $validator->errors()->add('cantidad_final', 'La cantidad que intentas ingresar supera a la cantidad inicial');
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
