<?php

namespace App\Http\Requests\Ventas;

use App\Models\Ventas\Comisiones;
use App\Models\Ventas\ProductoVentas;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class VentasRequest extends FormRequest
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
            'estado_activ' => 'required',
            'forma_pago' => 'required',
            'comision_id' => 'required',
            'chargeback' => 'nullable',
            'comision_vendedor' => 'nullable',

        ];
    }
    protected function prepareForValidation()
    {
        $producto = ProductoVentas::where('id', $this->producto)->first();
        $comision = Comisiones::where('plan_id', $producto->plan_id)->where('forma_pago', $this->forma_pago)->first();
        $chargeback = $this->chargeback!==null ? $this->chargeback:0;
        $comision_value = $this->estado_activ=='APROBADO' ? ($producto->precio*$comision->comision)/100:0;
        if($this->fecha_activ!=null){
            $date_activ = Carbon::createFromFormat('d-m-Y', $this->fecha_activ);
            $this->merge([
                'fecha_activ'=>$date_activ->format('Y-m-d'),
            ]);
        }
        $this->merge([
            'vendedor_id' => $this->vendedor,
            'producto_id' => $this->producto,
            'comision_id' => $comision->id,
            'comision_vendedor'=>$comision_value ,
            'chargeback' =>$chargeback
        ]);
    }
}
