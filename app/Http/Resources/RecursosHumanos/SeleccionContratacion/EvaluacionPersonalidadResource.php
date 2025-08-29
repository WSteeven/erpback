<?php

namespace App\Http\Resources\RecursosHumanos\SeleccionContratacion;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EvaluacionPersonalidadResource extends JsonResource
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
        $persona = $this->user_type === User::class ? $this->user?->empleado : $this->user?->persona;
        $modelo = [
            'id' => $this->id,
            'postulacion' => $this->postulacion->vacante->nombre,
            'respuestas' => $this->respuestas,
            'fecha_realizacion' => $this->fecha_realizacion,
            'enviada_mail' => $this->enviada_mail,
            'completado' => $this->completado,
//            'completado' =>  false,
            'usuario' => $this->user,
            'persona' => $persona,
            'user_id' => $this->user_id,
            'user_type' => $this->user_type,
            'observacion' => $this->observacion,
            'created_at' => Carbon::parse($this->created_at)->format('d-m-Y H:i:s'),
        ];

        if ($controller_method == 'show') {
            $modelo['postulacion'] = $this->postulacion_id;
        }


        return $modelo;
    }
}
