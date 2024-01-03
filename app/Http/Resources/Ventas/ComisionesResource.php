<?php

namespace App\Http\Resources\Ventas;

use Illuminate\Http\Resources\Json\JsonResource;

class ComisionesResource extends JsonResource
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
            'plan' => $this->plan_id,
            'plan_info' => $this->plan!= null?$this->plan->nombre:'',
            'forma_pago' => $this->forma_pago,
            'comision' => $this->comision,
            'tipo_vendedor' => $this->tipo_vendedor
        ];
        return $modelo;
    }
}
