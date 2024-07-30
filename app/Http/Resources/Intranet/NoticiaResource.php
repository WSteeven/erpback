<?php

namespace App\Http\Resources\Intranet;

use App\Models\Empleado;
use Illuminate\Http\Resources\Json\JsonResource;
use Src\Shared\Utils;

class NoticiaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $modelo =  [
            'id'=>$this->id,
            'titulo'=>$this->titulo,
            'descripcion'=>$this->descripcion,
            'autor_id'=>$this->autor_id,
            'autor'=>Empleado::extraerNombresApellidos($this->autor),
            'categoria_id'=>$this->categoria_id,
            'categoria'=>$this->categoria_id,
            'etiquetas'=>array_map('intval', Utils::convertirStringComasArray($this->etiquetas)),
            'imagen_noticia'=>url($this->imagen_noticia)??null,
            'fecha_vencimiento'=>$this->fecha_vencimiento,
        ];
        return $modelo;
    }
}
