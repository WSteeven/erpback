<?php

namespace App\Http\Resources\FondosRotativos\Saldo;

use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransferenciaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $controller_method = $request->route()->getActionMethod();
        $modelo = [
            'id' => $this->id,
            'fecha' => $this->fecha,
            'usuario_envia_info' => $this->empleadoEnvia?->nombres . ' ' . $this->empleadoEnvia?->apellidos,
            'usuario_recibe_info' => $this->motivo === 'DEVOLUCION' ||  $this->es_devolucion ? 'JPCONSTRUCRED C.LTDA' :  $this->empleadoRecibe->nombres . ' ' . $this->empleadoRecibe->apellidos,
            'usuario_recibe' => $this->usuario_recibe_id,
            'usuario_envia' => $this->usuario_envia_id,
            'usuario_envia_id' => $this->usuario_envia_id,
            'usuario_recive_id' => $this->usuario_recibe_id,
            'estado' => $this->estado,
            'estado_info' => $this->estadoViatico->descripcion,
            'cuenta' => $this->cuenta,
            'es_devolucion' => $this->motivo === 'DEVOLUCION' ? true : $this->es_devolucion,
            'tarea' => $this->tarea == null ? 'SIN TAREA' : $this->tarea->titulo,
            'monto' => $this->monto,
            'motivo' => $this->motivo,
            'comprobante' => url($this->comprobante),
        ];
        if($controller_method=='show'){
            $modelo['observacion'] = $this->observacion;
            $modelo['motivo_aprobacion_tercero'] = $this->motivo_aprobacion_tercero;
            $modelo['usuario_tercero_aprueba'] = Empleado::extraerNombresApellidos($this->terceroAprueba);
            $modelo['usuario_tercero_aprueba_id'] = $this->usuario_tercero_aprueba_id;
        }
        return $modelo;
    }
}
