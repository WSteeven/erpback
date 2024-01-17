<?php

namespace App\Http\Requests\Ventas;

use App\Models\Ventas\Comision;
use App\Models\Ventas\ProductoVenta;
use App\Models\Ventas\Vendedor;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class VentaRequest extends FormRequest
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

            'orden_id' => 'required',
            'orden_interna' => 'required',
            'vendedor_id' => 'required',
            'producto_id' => 'required',
            'fecha_activ' => 'nullable',
            'estado_activacion' => 'required',
            'forma_pago' => 'required',
            'comision_id' => 'required',
            'chargeback' => 'nullable',
            'comision_vendedor' => 'nullable',
            'cliente_id' => 'nullable',

        ];
    }
    protected function prepareForValidation()
    {
        $vendedor = Vendedor::where('id', $this->vendedor_id)->first();
        $tipo_vendedor = $vendedor !== null ?$vendedor->tipo_vendedor:'VENDEDOR';

        $producto = ProductoVenta::where('id', $this->producto)->first();
        $comision = Comision::where('plan_id', $producto->plan_id)->where('forma_pago', $this->forma_pago)->where('tipo_vendedor', $tipo_vendedor)->first();
        $chargeback = $this->chargeback !== null ? $this->chargeback : 0;
        $comision_valor = floatval($comision != null ? $comision->comision : 0);
        $comision_total = $this->estado_activacion == 'APROBADO' ? ($producto->precio * $comision_valor) / 100 : 0;
        if ($this->fecha_activ != null) {
            $date_activ = Carbon::createFromFormat('d-m-Y', $this->fecha_activ);
            $this->merge([
                'fecha_activ' => $date_activ->format('Y-m-d'),
            ]);
        }
        if ($this->cliente) {
            $this->merge([
                'cliente_id' => $this->cliente,
            ]);
        }
        $this->merge([
            'vendedor_id' => $this->vendedor,
            'producto_id' => $this->producto,
            'comision_id' => $comision->id,
            'comision_vendedor' => $comision_total,
            'chargeback' => $chargeback
        ]);
    }
}
