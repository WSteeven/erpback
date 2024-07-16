<?php

namespace App\Http\Resources\Medico;

use App\Models\Medico\Cuestionario;
use App\Models\Medico\Pregunta;
use App\Models\Medico\Respuesta;
use App\Models\Medico\RespuestaCuestionarioEmpleado;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class CuestionarioEmpleadoResource extends JsonResource
{
    private  $respuesta_cuestionario = null;
    /**
     * Transform the resource into an array.
     *  Recibe collection Empleados
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'empleado' => $this->apellidos . ' ' . $this->nombres,
            'empleado_info' => $this->apellidos . ' ' . $this->nombres,
            'repuesta' => $this->respuesta,
            'finalizado' => $this->tieneCuestionario($this->id),
            // 'preguntas' => $this->obtenerPreguntas()
        ];
    }

    // Empleado completó el cuestionario del periodo actual
    private function tieneCuestionario(int $empleado_id)
    {
        $tipo_cuestionario_id = request('tipo_cuestionario_id');
        return $this->respuesta_cuestionario = RespuestaCuestionarioEmpleado::where('empleado_id', $empleado_id)->whereYear('created_at', request('anio'))->whereHas('cuestionario', function (Builder $q) use ($tipo_cuestionario_id) {
            $q->where('tipo_cuestionario_id', $tipo_cuestionario_id);
        })->exists();
        // return  $this->respuesta_cuestionario->count() > 0 ? true : false;
    }

    private function obtenerCuestionario($empleado_id)
    {
        $respuesta_cuestionario = RespuestaCuestionarioEmpleado::where('empleado_id', $empleado_id)->with('cuestionario')->get();
        if ($respuesta_cuestionario) {
            $cuestionarios = array_map(function ($cuestionario) {
                $respuesta = Respuesta::find($cuestionario['cuestionario']['respuesta_id']);
                $new_cuestionario = ["pregunta_id" => $cuestionario['cuestionario']['pregunta_id'], 'respuesta' => $respuesta];
                return $new_cuestionario;
            }, $respuesta_cuestionario->toArray());
            return $cuestionarios;
        }
        return null;
    }

    private function obtenerPreguntas()
    {
        $respuestas = [];
        if ($this->respuesta_cuestionario) {
            $respuestas = $this->respuesta_cuestionario;
            $respuesta = array_map(function ($cuestionario) {
                $pregunta = Pregunta::find($cuestionario['cuestionario']['pregunta_id']);
                $pregunta = new PreguntaVisualizarResource($pregunta);
                return $pregunta;
            }, $respuestas->toArray());
            return $respuesta;
        }
        return  $respuestas;
    }
}
