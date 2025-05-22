<?php

namespace App\Http\Resources\Ventas;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VentaResource extends JsonResource
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
            'orden_id' => $this->orden_id,
            //'orden_interna' => $this->orden_interna,
            'fecha_ingreso' => $this->fecha_ingreso,
            'fecha_agendamiento' => $this->fecha_agendamiento,
            'vendedor' => $this->vendedor_id,
            'vendedor_info' => $this->vendedor != null ? $this->vendedor->empleado->apellidos . ' ' . $this->vendedor->empleado->nombres : '',
            'cliente' => $this->cliente_id,
            'cliente_info' => $this->cliente != null ? $this->cliente->apellidos . ' ' . $this->cliente->nombres : '',
            'producto' => $this->producto_id,
            'producto_info' => $this->producto != null ? $this->producto->bundle_id : '',
            'producto_precio' => $this->producto != null ? $this->producto->precio : '',
            'plan' => $this->producto != null ? strtoupper($this->producto->plan->nombre) : '',
            'fecha_activacion' => $this->fecha_activacion,
            'mes' => strtoupper(Carbon::parse($this->created_at)->translatedFormat('F-Y')),
            'estado_activacion' => $this->estado_activacion,
            'forma_pago' => $this->forma_pago,
            'banco' => $this->banco,
            'numero_tarjeta' => $this->numero_tarjeta,
            'tipo_cuenta' => $this->tipo_cuenta,
            'comisiona' => $this->comisiona ? 'SI' : 'NO',
            'comision' => $this->comision_id,
            'comision_info' => $this->comision != null ? $this->comision->comision : '',
            'chargeback' => $this->chargeback,
            'comision_vendedor' => $this->comision_vendedor,
            'activo' => $this->activo,
            'observacion' => $this->observacion,
            'primer_mes' => $this->primer_mes,
            'fecha_pago_primer_mes' => $this->fecha_pago_primer_mes,
            'estado' => $this->estado?->nombre,
            'adicionales' => $this->adicionales,
            'novedades' => $this->novedadesVenta?->count() || 0,
        ];
        if ($controller_method == 'show') {
            $modelo['estado'] = $this->estado_id;
        }
        return $modelo;
    }
}
