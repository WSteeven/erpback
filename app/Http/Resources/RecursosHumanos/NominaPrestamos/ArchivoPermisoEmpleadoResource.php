<?php

namespace App\Http\Resources\RecursosHumanos\NominaPrestamos;

use Illuminate\Http\Resources\Json\JsonResource;

class ArchivoPermisoEmpleadoResource extends JsonResource
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
            'nombre' =>$this->documento!= null?json_decode($this->documento)->nombre:'',
            'ruta' =>$this->documento?json_decode($this->documento)->ruta:null,
            'tamanio_bytes' =>$this->documento!= null?json_decode($this->documento)->tamanio_bytes:0,
        ];
        return $modelo;
    }
}
