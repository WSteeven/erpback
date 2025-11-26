<?php

namespace App\Http\Resources\FondosRotativos;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Src\Shared\Utils;

class AjusteSaldoFondoRotativoResource extends JsonResource
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

        $modelo =  [
            'id' => $this->id,
            'solicitante' => $this->solicitante ? $this->solicitante->nombres . ' ' . $this->solicitante->apellidos : null,
            'destinatario' => $this->destinatario ? $this->destinatario->nombres . ' ' . $this->destinatario->apellidos : null,
            'autorizador' => $this->autorizador ? $this->autorizador->nombres . ' ' . $this->autorizador->apellidos : null,
            'motivo' => $this->motivo,
            'descripcion' => $this->descripcion,
            'monto' => $this->monto,
            'tipo' => $this->tipo,
            'fecha'=> Carbon::parse($this->created_at)->format(Utils::MASKFECHA)
        ];

        if ($controller_method == 'show') {
            $modelo['solicitante'] = $this->solicitante_id;
            $modelo['destinatario'] = $this->destinatario_id;
            $modelo['autorizador'] = $this->autorizador_id;
        }

        return $modelo;
    }
}
