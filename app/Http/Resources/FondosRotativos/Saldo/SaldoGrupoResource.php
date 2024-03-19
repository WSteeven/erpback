<?php

namespace App\Http\Resources\FondosRotativos\Saldo;

use App\Http\Resources\UserResource;
use App\Models\FondosRotativos\Gasto\Gasto;
use App\Models\FondosRotativos\Viatico\Viatico;
use App\Models\User;
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
            'empleado_info' => $this->usuario,
            'descripcion_saldo' => $this->descripcionSaldo,
            'saldo_anterior' => $this->saldo_anterior,
            'saldo_depositado' => $this->saldo_depositado,
            'saldo_actual' => $this->saldo_actual,
            'gasto' => $this->getSaldoGrupo($this->fecha_inicio,$this->fecha_fin,$this->id_usuario),
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin,
        ];
        return $modelo;
    }
    private function getSaldoGrupo($fecha_inicio,$fecha_fin,$id_usuario){
        $gasto = Gasto::selectRaw("*, DATE_FORMAT(fecha_viat, '%d/%m/%Y') as fecha")
            ->whereBetween(DB::raw('date_format(fecha_viat, "%Y-%m-%d")'), [$fecha_inicio,$fecha_fin])
            ->where('estado', '=', 1)
            ->where('id_usuario', '=', $id_usuario)
            ->sum('total');
        return $gasto;
    }
}
