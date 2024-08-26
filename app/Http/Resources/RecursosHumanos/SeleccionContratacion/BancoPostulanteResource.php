<?php

namespace App\Http\Resources\RecursosHumanos\SeleccionContratacion;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $user_type
 * @property mixed $user_id
 * @property mixed $postulacion_id
 * @property mixed $user
 * @property mixed $cargo
 * @property mixed $observacion
 * @property mixed $fue_contactado
 * @property mixed $created_at
 * @property mixed $cargo_id
 * @property mixed $descartado
 * @property mixed $puntuacion
 * @property mixed $id
 */
class BancoPostulanteResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        $persona = $this->user_type === User::class ? $this->user->empleado : $this->user->persona;
        $controller_method = $request->route()->getActionMethod();
        $modelo = [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'user_type' => $this->user_type,
            'cargo' => $this->cargo->nombre,
            'nombres_apellidos' => $persona?->nombres . ' ' . $persona?->apellidos,
            'postulacion_id' => $this->postulacion_id,
            'puntuacion' => $this->puntuacion,
            'observacion' => $this->observacion,
            'descartado' => $this->descartado,
            'fue_contactado' => $this->fue_contactado,
            'created_at' => date('Y-m-d H:m:s', strtotime($this->created_at)),
        ];
        if ($controller_method == 'show') {
            $modelo['cargo'] = $this->cargo_id;
        }

        return $modelo;
    }
}
