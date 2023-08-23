<?php

namespace App\Http\Resources\RecursosHumanos\NominaPrestamos;

use Illuminate\Http\Resources\Json\JsonResource;

class ArchivoRolPagoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $modelo = [
            'id' => $this->id,
            'nombre' =>$this->rol_firmado!= null?json_decode($this->rol_firmado)->nombre:'',
            'ruta' =>$this->rol_firmado?json_decode($this->rol_firmado)->ruta:null,
            'tamanio_bytes' =>$this->rol_firmado!= null?json_decode($this->rol_firmado)->tamanio_bytes:0,
        ];
        return $modelo;
    }
}
