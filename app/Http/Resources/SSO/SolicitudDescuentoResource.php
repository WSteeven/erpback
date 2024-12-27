<?php

namespace App\Http\Resources\SSO;

use App\Models\Empleado;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SolicitudDescuentoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $controller_method = request()->route()->getActionMethod();

        $modelo = [
            'id' => $this['id'],
            'titulo' => $this['titulo'],
            'descripcion' => $this['descripcion'],
            'estado' => $this['estado'],
            'detalles_productos' => json_decode($this['detalles_productos']),
            'empleado_involucrado' => Empleado::extraerNombresApellidos($this->empleadoInvolucrado),
            'empleado_solicitante' => Empleado::extraerNombresApellidos($this->empleadoSolicitante),
            'cliente' => $this['cliente_id'],
            'incidente' => $this['incidente_id'],
            'created_at' => Carbon::parse($this['created_at'])->format('Y-m-d H:i:s'),
        ];

        if ($controller_method == 'show') {
//            $modelo['empleado_solicitante'] = $this['empleado_solicitante_id'];
            $modelo['empleado_involucrado'] = $this['empleado_involucrado_id'];
        }

        return $modelo;
    }
}
