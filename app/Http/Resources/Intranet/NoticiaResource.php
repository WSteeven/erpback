<?php

namespace App\Http\Resources\Intranet;

use App\Models\Empleado;
use App\Models\Intranet\Etiqueta;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Src\Shared\Utils;

class NoticiaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        $controller_method = $request->route()->getActionMethod();
        $modelo = [
            'id' => $this->id,
            'titulo' => $this->titulo,
            'descripcion' => $this->descripcion,
            'autor_id' => $this->autor_id,
            'autor' => Empleado::extraerNombresApellidos($this->autor),
            'categoria_id' => $this->categoria_id,
            'categoria' => $this->categoria->nombre,
//            'etiquetas'=>array_map('intval', Utils::convertirStringComasArray($this->etiquetas)),
            'etiquetas' => Etiqueta::whereIn('id', Utils::convertirStringComasArray($this->etiquetas))->pluck('nombre'),
            'imagen_noticia' => url($this->imagen_noticia) ?? null,
            'fecha_vencimiento' => $this->fecha_vencimiento,
        ];

        if ($controller_method == 'show' || $controller_method == 'ultima') {
            $modelo['categoria'] = $this->categoria_id;
            $modelo['etiquetas'] = Etiqueta::whereIn('id', Utils::convertirStringComasArray($this->etiquetas))->pluck('nombre');
        }

        return $modelo;
    }
}
