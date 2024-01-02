<?php

namespace App\Http\Requests\Ventas;

use Illuminate\Foundation\Http\FormRequest;

class EscenarioVentaJPRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'mes'=> 'required',
            'apoyo_das_fijos'=> 'required',
            'vendedores'=> 'required',
            'productividad_minima'=> 'required',
            'vendedores_acumulados'=> 'required',
            'total_ventas_adicionales'=> 'required',
            'arpu_prom'=> 'required',
            'altas'=> 'required',
            'bajas'=> 'required',
            'neta'=> 'required',
            'stock'=> 'required',
            'stock_que_factura'=> 'required',
        ];
    }
}
