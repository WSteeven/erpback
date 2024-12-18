<?php

namespace App\Http\Resources\RecursosHumanos\TrabajoSocial;

use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VisitaDomiciliariaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        $controller_method = $request->route()->getActionMethod();
        $modelo = [
            'id' => $this->id,
            'empleado' => Empleado::extraerNombresApellidos($this->empleado),
            'lugar_nacimiento' => $this->lugar_nacimiento,
            'canton' => $this->canton->canton,
            'contacto_emergencia' => $this->contacto_emergencia,
            'parentesco_contacto_emergencia' => $this->parentesco_contacto_emergencia,
            'telefono_contacto_emergencia' => $this->telefono_contacto_emergencia,
            'diagnostico_social' => $this->diagnostico_social,
            'observaciones' => $this->observaciones,
        ];

        if ($controller_method == 'show' || $controller_method == 'ultimaFichaEmpleado') {
            $modelo['empleado'] = $this->empleado_id;
            $modelo['canton'] = $this->canton_id;
            $modelo['economia_familiar'] = new EconomiaFamiliarResource($this->economiaFamiliar);
            $modelo['salud'] = $this->salud ? new SaludResource($this->salud) : null;
            $modelo['vivienda'] = $this->vivienda ? new ViviendaResource($this->vivienda) : null;
            $modelo['composicion_familiar'] = $this->composicionFamiliar ? ComposicionFamiliarResource::collection($this->composicionFamiliar) : null;

        }


        return $modelo;
    }
}
