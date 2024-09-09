<?php

namespace App\Http\Resources\Medico;

use App\Models\Medico\EsquemaVacuna;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class EsquemaVacunaResource extends JsonResource
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
            /*
            'dosis_aplicadas' => $this->dosis_aplicadas,
            'dosis_totales' => $this->tipoVacuna?->dosis_totales,
            'observacion' => $this->observacion,
            'tipo_vacuna' => $this->tipoVacuna?->nombre,
            'tipo_vacuna_id' => $this->tipo_vacuna_id,
            'fecha' => $this->fecha,
            'lote' => $this->lote,
            'responsable_vacunacion' => $this->responsable_vacunacion,
            'establecimiento_salud' => $this->establecimiento_salud,
            */
            'id' => $this->id,
            'tipo_vacuna' => $this->tipoVacuna?->nombre,
            'dosis_totales' => $this->tipoVacuna?->dosis_totales,
            'dosis_aplicadas' => $this->aplicadas,
            'tipo_vacuna_id' => $this->tipo_vacuna_id,
            'observacion' => $this->observacion,
            'fecha' => Carbon::parse($this->fecha)->format('Y-m-d'),
            'lote' => $this->lote,
            'responsable_vacunacion' => $this->responsable_vacunacion,
            'establecimiento_salud' => $this->establecimiento_salud,
            'es_dosis_unica' => $this->es_dosis_unica,
            'fecha_caducidad' => Carbon::parse($this->fecha_caducidad)->format('Y-m-d'),
            'archivos' => $this->archivos->map(fn ($archivo) => [
                'id' => $archivo->id, 
                'nombre' => $archivo->nombre, 
                'ruta' => url($archivo->ruta), 
                'tamanio_bytes' => $archivo->tamanio_bytes, 
                'archivable_id' => $archivo->archivable_id, 
                'archivable_type' => $archivo->archivable_type, 
            ]),
        ];

        if ($controller_method == 'show') {
            $modelo['tipo_vacuna'] = $this->tipo_vacuna_id;
        }

        return $modelo;
    }
}
