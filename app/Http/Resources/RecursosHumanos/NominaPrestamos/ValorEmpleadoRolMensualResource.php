<?php

namespace App\Http\Resources\RecursosHumanos\NominaPrestamos;

use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ValorEmpleadoRolMensualResource extends JsonResource
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
            'tipo' => $this->tipo,
            'mes' => $this->mes,
            'empleado' => Empleado::extraerNombresApellidos($this->empleado),
            'monto' => $this->monto,
            'model_type' => class_basename($this->model_type),
            'model_id' => $this->model_id,
            'rol_pago' => $this->rol_pago_id,
            'rol_pago_id' => $this->rol_pago_id,
        ];
        if ($controller_method == 'show') {
            $modelo['empleado'] = $this->empleado_id;
        }

        return $modelo;
    }
}
