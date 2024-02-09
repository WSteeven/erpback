<?php

namespace App\Http\Requests\Ventas;

use App\Models\Ventas\Comision;
use App\Models\Ventas\ProductoVenta;
use App\Models\Ventas\Vendedor;
use App\Models\Ventas\Venta;
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
            'orden_interna' => 'sometimes|string|nullable',
            'supervisor_id' => 'required',
            'vendedor_id' => 'required',
            'producto_id' => 'required',
            'fecha_activacion' => 'nullable',
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
        $chargeback = $this->chargeback !== null ? $this->chargeback : 0;
        [$comision_valor, $comision] = Comision::calcularComisionVenta($this->vendedor, $this->producto, $this->forma_pago);
        $comision_total = $this->estado_activacion == Venta::ACTIVADO ?  $comision_valor : 0;
        if ($this->estado_activacion == Venta::APROBADO) $this->merge(['fecha_activacion' => null]);
        if ($this->fecha_activacion != null) {
            $this->merge([
                'fecha_activacion' => date('Y-m-d', strtotime($this->fecha_activacion)),
            ]);
        }
        $this->merge([
            'supervisor_id' => auth()->user()->empleado->id,
            'cliente_id' => $this->cliente,
            'vendedor_id' => $this->vendedor,
            'producto_id' => $this->producto,
            'comision_id' => $comision->id,
            'comisiona' => Venta::obtenerVentaComisiona($this->vendedor),
            'comision_vendedor' => $comision_total,
            'chargeback' => $chargeback
        ]);
    }
}
