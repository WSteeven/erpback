<?php

namespace App\Http\Resources\ComprasProveedores;

use App\Models\Empleado;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class PagoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $beneficiario = $this->beneficiario;
        $cuentaBanco = $this->cuentaBanco;

        return [
            // Campos para reporte
            'tipo' => $this['tipo'],
            'num_cuenta_empresa' => $this['num_cuenta_empresa'],
            'num_secuencial' => null, // $this['num_secuencial'],
            'num_comprobante' => $this['num_comprobante'],
            'codigo_beneficiario' => $beneficiario->codigo_beneficiario,
            'moneda' => $this['moneda'],
            'valor' => $this['valor'],
            'forma_pago' => $this['forma_pago'],
            'codigo_banco' => $cuentaBanco->banco->codigo,
            'tipo_cuenta' => $cuentaBanco->tipo_cuenta,
            'numero_cuenta' => $cuentaBanco->numero_cuenta,
            'tipo_documento' => $beneficiario->tipo_documento,
            'identificacion_beneficiario' => $beneficiario->identificacion_beneficiario,
            'nombre_beneficiario' => $beneficiario->nombre_beneficiario,
            'direccion' => $this['direccion'],
            'ciudad' => $this['ciudad'],
            'telefono' => $this['telefono'],
            'localidad' => $this['localidad'],
            'referencia' => $this['referencia'],
            'referencia_adicional' => $this['referencia_adicional'],
            // Extras (no forman parte del reporte)
            'id' => $this['id'],
            'generador_cash_id' => $this['generador_cash_id'],
            'beneficiario_id' => $beneficiario->id,
            'cuenta_banco_id' => $cuentaBanco->id,
        ];
    }
}
