<?php

namespace App\Http\Resources\ComprasProveedores;

use App\Models\RecursosHumanos\Banco;
use Illuminate\Http\Resources\Json\JsonResource;

class BeneficiarioResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $controller_method = $request->route()->getActionMethod();
        $modelo = [
            'id' => $this['id'],
            'codigo_beneficiario' => $this['codigo_beneficiario'],
            'tipo_documento' => $this['tipo_documento'],
            'identificacion_beneficiario' => $this['identificacion_beneficiario'],
            'nombre_beneficiario' => $this['nombre_beneficiario'],
            'resumen_cuentas_bancarias' => $this->mapearResumenCuentasBancarias(CuentaBancariaResource::collection($this->cuentasBancarias()->latest()->get())),
            /* 'direccion' => $this['direccion'],
            'telefono' => $this['telefono'],
            'localidad' => $this['localidad'],
            'correo' => $this['correo'],
            'canton' => $this['canton_id'], */
        ];

        if ($controller_method == 'show') {
            $modelo['cuentas_bancarias'] = CuentaBancariaResource::collection($this->cuentasBancarias()->latest()->get());
        }

        return $modelo;
    }

    private function mapearResumenCuentasBancarias($listado)
    {
        $mapeado = $listado->map(fn($cuenta) => [
            'Tipo' => '<b>' . $cuenta['tipo_cuenta'] . '</b>',
            'Banco' => '<b>' . Banco::find($cuenta['banco_id'])->nombre . '</b>',
            'Cuenta' => '<b>' . $cuenta['numero_cuenta'] . '</b>',
        ]);

        return $mapeado->map(fn($cuenta) => implode(' ', array_map(fn($k, $v) => "$k: $v", array_keys($cuenta), $cuenta)))
            ->implode(' <br> ');
    }
}
