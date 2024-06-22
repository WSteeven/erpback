<?php

namespace App\Http\Resources\Medico;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Src\App\Medico\CuestionariosRespondidosService;

class TipoCuestionarioResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'titulo' => $this->titulo,
            'finalizado' => $this->verificarCuestionarioFinalizado($this->id),
        ];
    }

    private function verificarCuestionarioFinalizado($tipo_cuestionario_id)
    {
        $preguntaService = new CuestionariosRespondidosService();

        if (Auth::check()) {
            $empleado_id = Auth::user()->empleado->id;
            return $preguntaService->empleadoYaLlenoCuestionario($empleado_id, $tipo_cuestionario_id);
        } else return false;
    }
}
