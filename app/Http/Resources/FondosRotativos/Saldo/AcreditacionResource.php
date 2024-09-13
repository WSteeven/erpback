<?php

namespace App\Http\Resources\FondosRotativos\Saldo;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $id
 */
class AcreditacionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'fecha' => Carbon::parse($this->fecha)->format('Y-m-d'),
            'tipo_saldo' => $this->id_tipo_saldo,
            'tipo_fondo' => $this->id_tipo_fondo,
            'id_saldo' => $this->id_saldo,
            'usuario' => $this->id_usuario,
            'empleado_info' => $this->usuario->nombres . ' ' . $this->usuario->apellidos,
            'estado' => $this->estado != null ? $this->estado->estado : ' ',
            'descripcion_acreditacion' => $this->descripcion_acreditacion,
            'motivo' => $this->motivo,
            'monto' => $this->monto,
        ];
    }
}
