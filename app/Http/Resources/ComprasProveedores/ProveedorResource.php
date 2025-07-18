<?php

namespace App\Http\Resources\ComprasProveedores;

use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Src\App\ComprasProveedores\ProveedorService;
use Src\Shared\Utils;

class ProveedorResource extends JsonResource
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
        [$salud, $descripcion] = ProveedorService::datosProveedor($this);
        $modelo = [
            'id' => $this->id,
            'empresa' => $this->empresa_id,
            'ruc' => $this->empresa->identificacion,
            'razon_social' => $this->empresa->razon_social,
            'nombre_comercial' => $this->empresa->nombre_comercial,
            'sitio_web' => $this->empresa->sitio_web,
            'sucursal' => $this->sucursal,
            'ubicacion' => $this->parroquia ? $this->parroquia->canton->provincia->provincia . ' - ' . $this->parroquia->canton->canton . ' - ' . $this->parroquia->parroquia : null,
            'canton' => $this->parroquia?->canton->canton,
            'parroquia' => $this->parroquia?->parroquia,
            'direccion' => $this->direccion,
            'celular' => $this->celular,
            'telefono' => $this->telefono,
            'estado' => $this->estado,
            'correo' => $this->correo,
            'tipos_ofrece' => $this->servicios_ofertados->map(fn ($item) => $item->id),
            'categorias_ofrece' => $this->categorias_ofertadas->map(fn ($item) => $item->id),
            'departamentos' => $this->departamentos_califican->map(fn ($item) => $item->id),
            'related_departamentos' => $this->departamentos_califican,
            'calificacion' => $this->calificacion ?: 0,
//            'tiene_calificacion'=>
            'require_recalificacion'=> Proveedor::consultarProveedorRequireCalificacion($this->id),
            'estado_calificado' => $this->estado_calificado ?: Proveedor::SIN_CONFIGURAR,
            "forma_pago" => $this->forma_pago? Utils::convertirStringComasArray($this->forma_pago) : null,
            "referencia" => $this->referencia,
            "plazo_credito" => $this->plazo_credito,
            "anticipos" => $this->anticipos,
            "salud" => $salud,
            "observaciones" => $descripcion,


            //Logistica del proveedor
            'tiempo_entrega' => $this->empresa->logistica?->tiempo_entrega,
            'envios' => $this->empresa->logistica?->envios,
            // 'tipo_envio' => $this->empresa->logistica?->tipo_envio,
            'tipo_envio' => $this->empresa->logistica?->tipo_envio ? Utils::convertirStringComasArray($this->empresa->logistica?->tipo_envio) : null,
            'transporte_incluido' => $this->empresa->logistica?->transporte_incluido,
            'garantia' => $this->empresa->logistica?->garantia,
        ];

        if ($controller_method == 'show') {
            //listados
            $modelo['canton'] = $this->parroquia?->canton_id;
            $modelo['parroquia'] = $this->parroquia_id;
            $modelo['tipos_ofrece'] = $this->servicios_ofertados->map(fn ($item) => $item->id);
            $modelo['categorias_ofrece'] = $this->categorias_ofertadas->map(fn ($item) => $item->id);
            $modelo['departamentos'] = $this->departamentos_califican->map(fn ($item) => $item->id)->unique();
            $modelo['contactos'] = ContactoProveedorResource::collection($this->empresa->contactos);
        }
        return $modelo;
    }
}
