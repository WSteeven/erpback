<?php

namespace App\Http\Resources\FondosRotativos\Saldo;

use App\Models\FondosRotativos\Viatico\Viatico;
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
            'fecha' => $this->fecha,
            'tipo_saldo' => $this->id_tipo_saldo,
            'usuario' => $this->id_usuario,
            'usuario_info' => $this->usuario->name,
            'descripcion_acreditacion' => $this->descripcion_saldo,
            'monto' => $this->monto,
            'gasto' => $this->getSaldoGrupo($this->fecha_inicio,$this->fecha_fin,$this->id_usuario),
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
