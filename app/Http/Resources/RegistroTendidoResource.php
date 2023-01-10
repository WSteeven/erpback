<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RegistroTendidoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'tipo_elemento' => $this->tipo_elemento,
            'propietario_elemento' => $this->propietario_elemento,
            'numero_elemento' => $this->numero_elemento,
            'codigo_elemento' => $this->codigo_elemento,
            'progresiva_entrada' => $this->progresiva_entrada,
            'progresiva_salida' => $this->progresiva_salida,
            'coordenada_del_elemento_latitud' => $this->coordenada_del_elemento_latitud,
            'coordenada_del_elemento_longitud' => $this->coordenada_del_elemento_longitud,
            'coordenada_cruce_americano_longitud' => $this->coordenada_cruce_americano_longitud,
            'coordenada_cruce_americano_latitud' => $this->coordenada_cruce_americano_latitud,
            'coordenada_poste_anclaje1_longitud' => $this->coordenada_poste_anclaje1_longitud,
            'coordenada_poste_anclaje1_latitud' => $this->coordenada_poste_anclaje1_latitud,
            'coordenada_poste_anclaje2_longitud' => $this->coordenada_poste_anclaje2_longitud,
            'coordenada_poste_anclaje2_latitud' => $this->coordenada_poste_anclaje2_latitud,
            'estado_elemento' => $this->estado_elemento,
            'tiene_transformador' => $this->tiene_transformador,
            'cantidad_transformadores' => $this->cantidad_transformadores,
            'tiene_americano' => $this->tiene_americano,
            'tiene_retenidas' => $this->tiene_retenidas,
            'cantidad_retenidas' => $this->cantidad_retenidas,
            'instalo_manga' => $this->instalo_manga,
            'instalo_reserva' => $this->instalo_reserva,
            'cantidad_reserva' => $this->cantidad_reserva,
            'observaciones' => $this->observaciones,
            'tension' => $this->tension,
            'tendido_id' => $this->tendido_id,
            'materiales_ocupados' => $this->materiales_ocupados,
            'imagen_elemento' => $this->imagen_elemento ? url($this->imagen_elemento) : null,
            'imagen_cruce_americano' => $this->imagen_cruce_americano ? url($this->imagen_cruce_americano) : null,
            'imagen_poste_anclaje1' => $this->imagen_poste_anclaje1 ? url($this->imagen_poste_anclaje1) : null,
            'imagen_poste_anclaje2' => $this->imagen_poste_anclaje2 ? url($this->imagen_poste_anclaje2) : null,
        ];
    }
}
