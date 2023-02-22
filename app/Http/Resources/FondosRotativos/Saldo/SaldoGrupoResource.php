<?php

namespace App\Http\Resources\FondosRotativos\Saldo;

use App\Models\FondosRotativos\Viatico\Viatico;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class SaldoGrupoResource extends JsonResource
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
            'tipo_saldo' => $this->id_tipo_saldo,
            'usuario' => $this->id_usuario,
            'usuario_info' => $this->usuario->name,
            'estatus_info' => $this->estatus->descripcion,
            'tipo_fondo' => $this->id_tipo_fondo,
            'tipo_fondo_info' => $this->tipo_fondo->descripcion,
            'tipo_saldo_info' => $this->tipo_saldo->descripcion,
            'id_saldo' => $this->id_saldo,
            'descripcion_saldo' => $this->descripcion_saldo,
            'saldo_anterior' => $this->saldo_anterior,
            'saldo_depositado' => $this->saldo_depositado,
            'saldo_actual' => $this->saldo_actual,
            'gasto' => $this->getSaldoGrupo($this->fecha_inicio,$this->fecha_fin,$this->id_usuario),
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin,
            'transcriptor' =>  $this->transcriptor,
            'fecha_trans' => $this->fecha_trans,
        ];
        return $modelo;
    }
    private function getSaldoGrupo($fecha_inicio,$fecha_fin,$id_usuario){
        $gasto = Viatico::selectRaw("*, DATE_FORMAT(fecha_viat, '%d/%m/%Y') as fecha")
            ->whereBetween(DB::raw('date_format(fecha_viat, "%Y-%m-%d")'), [$fecha_inicio,$fecha_fin])
            ->where('estado', '=', 1)
            ->where('id_usuario', '=', $id_usuario)
            ->sum('total');
        return $gasto;
    }
}
