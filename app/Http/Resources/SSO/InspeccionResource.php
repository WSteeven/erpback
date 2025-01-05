<?php

namespace App\Http\Resources\SSO;

use App\Models\Empleado;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $responsable
 */
class InspeccionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        $controller_method = request()->route()->getActionMethod();

        $modelo = [
            'id' => $this['id'],
            'titulo' => $this['titulo'],
            'descripcion' => $this['descripcion'],
            'fecha_inicio' => $this['fecha_inicio'],
            'responsable' => Empleado::extraerNombresApellidos($this->responsable),
            'estado' => $this['estado'],
            'empleado_involucrado' => Empleado::extraerNombresApellidos($this->empleadoInvolucrado),
            'tiene_incidencias' => !!$this['tiene_incidencias'],
            'coordenadas' => $this['coordenadas'],
            'cantidad_incidentes' => $this->incidentes->count(),
            'created_at' => Carbon::parse($this['created_at'])->format('Y-m-d H:i:s'),
        ];


        if ($controller_method == 'show') {
            $modelo['empleado_involucrado'] = $this['empleado_involucrado_id'];
            $modelo['seguimiento'] = $this['seguimiento'];
        }

        return $modelo;
    }
}
