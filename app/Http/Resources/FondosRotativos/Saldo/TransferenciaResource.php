<?php

namespace App\Http\Resources\FondosRotativos\Saldo;

use App\Models\FondosRotativos\Gasto\Gasto;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class TransferenciaResource extends JsonResource
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
            'fecha' => $this->fecha,
            'cuenta' => $this->cuenta,
            'usuario_envia_info' => $this->empleadoEnvia?->nombres . ' ' . $this->empleadoEnvia?->apellidos,
            'usuario_recibe_info' => $this->motivo === 'DEVOLUCION' ||  $this->es_devolucion ? 'JPCONSTRUCRED C.LTDA' :  $this->empleadoRecibe->nombres . ' ' . $this->empleadoRecibe->apellidos,
            'usuario_recibe' => $this->usuario_recibe_id,
            'usuario_envia' => $this->usuario_envia_id,
            'usuario_envia_id' => $this->usuario_envia_id,
            'usuario_recive_id' => $this->usuario_recibe_id,
            'estado' => $this->estado,
            'estado_info' => $this->estadoViatico->descripcion,
            'cuenta' => $this->cuenta,
            'tarea' => $this->id_tarea,
            'es_devolucion' => $this->motivo === 'DEVOLUCION' ? true : $this->es_devolucion,
            'tarea' => $this->tarea == null ? 'SIN TAREA' : $this->tarea->titulo,
            'monto' => $this->monto,
            'motivo' => $this->motivo,
            'comprobante' => url($this->comprobante),
        ];
        return $modelo;
    }
}
