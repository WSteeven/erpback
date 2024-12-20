<?php

namespace App\Http\Resources\RecursosHumanos\TrabajoSocial;

use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FichaSocioeconomicaResource extends JsonResource
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
//        Log::channel('testing')->info('Log', ['$controller_method es ', $controller_method]);
        $modelo = [
            'id' => $this->id,
            'empleado' => Empleado::extraerNombresApellidos($this->empleado),
            'lugar_nacimiento' => $this->lugar_nacimiento,
            'canton' => $this->canton->canton,
            'contacto_emergencia' => $this->contacto_emergencia,
            'parentesco_contacto_emergencia' => $this->parentesco_contacto_emergencia,
            'telefono_contacto_emergencia' => $this->telefono_contacto_emergencia,
            'problemas_ambiente_social_familiar' => $this->problemas_ambiente_social_familiar,
            'observaciones_ambiente_social_familiar' => $this->observaciones_ambiente_social_familiar,
            'conocimientos' => $this->conocimientos,
            'capacitaciones' => $this->capacitaciones,
            'vias_transito_regular_trabajo' => $this->vias_transito_regular_trabajo,
            'conclusiones' => $this->conclusiones,
            'created_at' => $this->created_at,
        ];
        if ($controller_method == 'show' || $controller_method == 'ultimaFichaEmpleado') {
            $modelo['empleado'] = $this->empleado_id;
            $modelo['canton'] = $this->canton_id;
            $modelo['imagen_rutagrama'] = is_null($this->imagen_rutagrama) ? null : url($this->imagen_rutagrama);
            $modelo['tiene_conyuge'] = !!$this->conyuge;
            $modelo['conyuge'] = $this->conyuge ? new ConyugeResource($this->conyuge) : null;
            $modelo['tiene_hijos'] = !!$this->hijos->count() > 0;
            $modelo['tiene_capacitaciones'] = !!($this->capacitaciones || $this->conocimientos);
            $modelo['hijos'] = $this->hijos ? HijoResource::collection($this->hijos) : null;
            $modelo['tiene_experiencia_previa'] = !!$this->experienciaPrevia;
            $modelo['experiencia_previa'] = $this->experienciaPrevia ? new ExperienciaPreviaResource($this->experienciaPrevia) : null;
            $modelo['vivienda'] = $this->vivienda ? new ViviendaResource($this->vivienda) : null;
            $modelo['situacion_socioeconomica'] = $this->situacionSocioeconomica ? new SituacionSocioeconomicaResource($this->situacionSocioeconomica) : null;
            $modelo['composicion_familiar'] = $this->composicionFamiliar ? ComposicionFamiliarResource::collection($this->composicionFamiliar) : null;
            $modelo['salud'] = $this->salud ? new SaludResource($this->salud) : null;


        }

        return $modelo;
    }
}
