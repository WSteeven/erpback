<?php

namespace App\Http\Resources\FondosRotativos\Saldo;

use App\Models\FondosRotativos\Gasto\Gasto;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class AcreditacionResource extends JsonResource
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
            'fecha' =>$this->cambiar_fecha($this->fecha),
            'tipo_saldo' => $this->id_tipo_saldo,
            'tipo_fondo' => $this->id_tipo_fondo,
            'id_saldo' => $this->id_saldo,
            'usuario' => $this->id_usuario,
            'usuario_info' => $this->usuario->nombres.' '.$this->usuario->apellidos,
            'descripcion_acreditacion' => $this->descripcion_acreditacion,
            'monto' => $this->monto,
        ];
        return $modelo;
    }
    private function cambiar_fecha($fecha){
        $fecha_formateada = Carbon::parse( $fecha)->format('d-m-Y');
            return $fecha_formateada;
        }
}
