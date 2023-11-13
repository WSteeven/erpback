<?php

namespace App\Http\Resources\Ventas;

use Illuminate\Http\Resources\Json\JsonResource;

class VentasResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $controller_method = $request->route()->getActionMethod();
        $modelo = [
            'id' => $this->id,
            'orden_id' => $this->orden_id,
            'orden_interna' => $this->orden_interna,
            'vendedor' => $this->vendedor_id,
            'vendedor_info' =>$this->vendedor != null? $this->vendedor->empleado->apellidos.' '.$this->vendedor->empleado->nombres:'',
            'producto' => $this->producto_id,
            'producto_info' =>  $this->producto!=null? $this->producto->bundle_id:'',
            'plan' =>   $this->producto!=null? strtoupper($this->producto->plan->nombre):'',
            'fecha_activ' => $this->fecha_activ,
            'estado_activ' => $this->estado_activ,
            'forma_pago' => $this->forma_pago,
            'comision' => $this->comision_id,
            'comision_info' =>  $this->comision!=null?$this->comision->comision:'',
            'chargeback' => $this->chargeback,
            'comision_vendedor' => $this->comision_vendedor
        ];
        return $modelo;
    }
}
