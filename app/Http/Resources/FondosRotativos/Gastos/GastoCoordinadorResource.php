<?php

namespace App\Http\Resources\FondosRotativos\Gastos;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class GastoCoordinadorResource extends JsonResource
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
            'fecha_gasto' => $this->cambiar_fecha($this->fecha_gasto),
            'lugar' => $this->id_lugar,
            'grupo' => $this->id_grupo,
            'grupo_info' => $this->grupo->nombre,
            'motivo_info' => $this->detalleMotivoGasto != null ? $this->detalleMotivoGasto($this->grupo):'',
            'motivo' => $this->detalleMotivoGasto != null ? $this->detalleMotivoGasto->pluck('id'):null,
            'lugar_info' => $this->canton->canton,
            'monto' => $this->monto,
            'observacion' => $this->observacion,
            'usuario' => $this->id_usuario,
            'empleado_info' => $this->empleado->nombres.' '.$this->empleado->apellidos,
        ];
        return $modelo;
    }
    private function motivoGasto($motivo_info){
        $descripcion = '';
        $i=0;
        foreach($motivo_info as $motivo){
            $descripcion .= $motivo->nombre;
            $i++;
            if($i < count($motivo_info)){
                $descripcion .= ', ';
            }
        }
        return $descripcion;
    }



   private function cambiar_fecha($fecha){
    $fecha_formateada = Carbon::parse( $fecha)->format('d-m-Y');
        return $fecha_formateada;
    }
}
