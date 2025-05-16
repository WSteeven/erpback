<?php

namespace App\Http\Resources\SSO;

use App\Models\Empleado;
use Illuminate\Http\Resources\Json\JsonResource;

class CertificacionEmpleadoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $controller_method = request()->route()->getActionMethod();

        $modelo = [
            'id' => $this['id'],
            'empleado' => Empleado::extraerNombresApellidos($this->empleado),
            'certificaciones' => implode(' | ', json_decode($this->certificaciones()->pluck('descripcion'))),
        ];

        if ($controller_method == 'show') {
            $modelo['empleado'] = $this['empleado_id'];
            $modelo['certificaciones'] = $this->certificaciones()->pluck('id');
        }

        return $modelo;
    }
}
