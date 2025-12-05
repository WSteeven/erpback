<?php

namespace App\Http\Resources\ControlPersonal;

use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Src\Shared\Utils;

class MarcacionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'empleado_id' => $this->empleado_id,
            'empleado' => Empleado::extraerNombresApellidos($this->empleado),
            'fecha' => $this->fecha,
            'marcaciones' => $this->castearMarcaciones(),
//            'marcaciones' => collect($this->marcaciones)->map(function ($marcacion) {
//                foreach ($marcacion as $key=>$hora) {
//                    return trim($hora) ? "<b>$key</b>: $hora":null;
//                }
//            })->filter()->implode(', '),
        ];
    }

    private function castearMarcaciones()
    {
        $marcaciones = $this->marcaciones;

        //si es string(JSON) lo decodificamos
        if(is_string($marcaciones)) {
            $marcaciones = json_decode($marcaciones, true);
        }

        // si es null o no es array lo convertimos en array vacio
        if(!is_array($marcaciones)) {
            $marcaciones = [];
        }

        // Ahora procesamos de forma segura
        return collect($marcaciones)->map(function ($marcacion) {
            // $marcacion puede ser un objeto o un array asociativo
            // Aseguramos que sea iterable
            $items = is_array($marcacion) ? $marcacion : (array) $marcacion;

            $salida = [];
            foreach ($items as $key => $hora) {
                $hora = trim($hora ?? '');
                if ($hora !== '') {
                    $salida[] = "<b>$key</b>: $hora";
                }
            }
            return !empty($salida) ? implode(' | ', $salida) : null;
        })->filter()->implode(', ');
    }
}
