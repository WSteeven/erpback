<?php

namespace App\Http\Resources\FondosRotativos\Saldo;

use Illuminate\Http\Resources\Json\JsonResource;

class AcreditacionSemanaResource extends JsonResource
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
            'id'=>$this->id,
            'semana'=>$this->semana,
            'acreditar'=>$this->acreditar,
        ];
        return $modelo;
    }
}
