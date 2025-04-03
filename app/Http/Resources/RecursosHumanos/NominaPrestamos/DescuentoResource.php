<?php

namespace App\Http\Resources\RecursosHumanos\NominaPrestamos;

use App\Models\Empleado;
use Illuminate\Http\Resources\Json\JsonResource;

class DescuentoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array
     */
    public function toArray($request)
    {
        $controller_method = $request->route()->getActionMethod();
        $modelo = [
            'id' => $this->id,
            'fecha_descuento' => $this->fecha_descuento,
            'empleado' => Empleado::extraerNombresApellidos($this->empleado),
            'descripcion' => $this->descripcion,
            'tipo_descuento' => $this->tipoDescuento->nombre,
            'multa' => $this->multa?->nombre,
            'valor' => $this->valor,
            'cantidad_cuotas' => $this->cantidad_cuotas,
            'mes_inicia_cobro' => $this->mes_inicia_cobro,
            'pagado' => $this->pagado,
            'pendiente_pagar'=> $this->cuotas()->where('pagada', false)->sum('valor_cuota'),
        ];

        if ($controller_method == 'show') {
            $modelo['empleado'] = $this->empleado_id;
            $modelo['tipo_descuento'] = $this->tipo_descuento_id;
            $modelo['multa'] = $this->multa_id;
            $modelo['cuotas'] = CuotaDescuentoResource::collection($this->cuotas);
        }

        return $modelo;
    }
}
