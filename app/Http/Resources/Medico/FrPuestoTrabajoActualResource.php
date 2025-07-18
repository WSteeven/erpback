<?php

namespace App\Http\Resources\Medico;

use App\Models\Medico\CategoriaFactorRiesgo;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class FrPuestoTrabajoActualResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $modelo = [
            'id' => $this->id,
            'puesto_trabajo' => $this->puesto_trabajo,
            'actividad' => $this->actividad,
            'medidas_preventivas' => $this->medidas_preventivas,
            'detalle_categ_factor_riesg_fr_puest_trab_act' => $this->detalleCategFactorRiesgoFrPuestoTrabAct->pluck('id'),
            'categorias' => CategoriaFactorRiesgo::whereIn('id', $this->detalleCategFactorRiesgoFrPuestoTrabAct->pluck('categoria_factor_riesgo_id'))->pluck('nombre'),
            'ficha_preocupacional' => $this->fichaPreocupacional
        ];
//        Log::channel('testing')->info('Log', ['Categorias:', $this->detalleCategFactorRiesgoFrPuestoTrabAct]);
        foreach ($this->detalleCategFactorRiesgoFrPuestoTrabAct as $categoria) {
//            Log::channel('testing')->info('Log', ['Categoria indivudual:', $categoria]);
            $modelo[$categoria->categoriaFactorRiesgo->tipo->nombre][] = $categoria->categoriaFactorRiesgo->id;
        }
        return $modelo;
    }

}
