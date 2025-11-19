<?php

namespace App\Http\Resources\RecursosHumanos\NominaPrestamos;

use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class EgresoRolPagoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'descuento_id' => $this->descuento_id,
            'id_rol_pago' => $this->id_rol_pago,
//            'empleado_id' => $this->empleado_id,
//            'empleado' => Empleado::extraerNombresApellidos($this->empleado),
//            'empleado_info' => Empleado::extraerNombresApellidos($this->empleado),
            'monto' => $this->monto,
            'tipo' => class_basename($this->descuento_type),
            'descuento' => $this->descuento->nombre ?? $this->obtenerNombreDescuento(),
            'id_descuento' => $this->descuento_id
        ];
    }

    private function obtenerNombreDescuento()
    {
        Log::channel('testing')->info('Log', ['obtenerNombreDescuento', $this->descuento->descuento]);
        return $this->descuento->descuento->tipoDescuento->nombre ?? $this->descuento->descuento->multa->nombre;
    }
    /**
     * Método viejo, ya no se usa
     */
//    public function toArray($request)
//    {
//        $controller_method = $request->route()->getActionMethod();
//        $tipo ='';
//        if ($this->descuento_type === "App\\Models\\RecursosHumanos\\NominaPrestamos\\DescuentosGenerales") {
//            $tipo= "DESCUENTO_GENERAL";
//        } elseif ($this->descuento_type === "App\\Models\\RecursosHumanos\\NominaPrestamos\\Multas") {
//            $tipo = "MULTA";
//        }
//        $modelo = [
//            'id' => $this->id,
//            'empleado' => $this->empleado_id,
//            'empleado_info' => $this->empleado != null? $this->empleado->apellidos. ' ' .$this->empleado->nombres:'',
//            'tipo' => $tipo,
//            'descuento' => $this->descuento->nombre,
//            'monto' => $this->monto,
//            'id_descuento' => $this->descuento->id
//        ];
//        return  $modelo;
//    }
}
