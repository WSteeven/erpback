<?php

namespace App\Http\Resources\Conecel\GestionTareas;

use App\Models\Conecel\GestionTareas\Tarea;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Src\Shared\Utils;
use Str;

class TareaResource extends JsonResource
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
            'fecha' => $this->raw_data['_v']['D'],
//            'fecha' => $this->atime_of_assignment ? Carbon::createFromFormat('d/m/y H:i', $this->atime_of_assignment)->format(Utils::MASKFECHA) : Carbon::parse($this->received_at)->format(Utils::MASKFECHA),
            'registrador' => 'automatico',
            'tipo_actividad' => $this->activity_workskills,
            'grupo' => Tarea::obtenerGrupoRelacionado($this->source)?->nombre_alternativo,
            'asignada' => !!Tarea::obtenerGrupoRelacionado($this->source),
            'estado_tarea' => $this->astatus,
            'orden_trabajo' => $this->appt_number,
            'nombre_cliente' => $this->cname,
            'direccion' => Str::limit($this->direccion, 50),
            'referencia' => 'no referencia',
            'latitud' => $this->lat,
            'longitud' => $this->lng,
            'coordenadas' => $this->mapearCoordenadas(),
            'observacion' => '$this->observacion',
            'telefonos' => '$this->telefonos',
        ];

        if ($controller_method == 'show') {
            $modelo['fecha'] = $this->atime_of_assignment ? Carbon::createFromFormat('d/m/y H:i', $this->atime_of_assignment)->format(Utils::MASKFECHAHORA) : Carbon::parse($this->received_at)->format(Utils::MASKFECHAHORA);
            $modelo['registrador'] = $this->registrador_id;
            $modelo['tipo_actividad'] = $this->tipo_actividad_id;
            $modelo['grupo'] = Tarea::obtenerGrupoRelacionado($this->source)?->id;
            $modelo['coordenadas'] = $this->mapearCoordenadas();
        }

        return $modelo;
    }

    private function mapearCoordenadas()
    {
        $estilo = Utils::obtenerEstiloPorEstado($this->astatus);
        return ['lat' => $this->lat,
            'lng' => $this->lng,
            'titulo' => $this->appt_number ?? 'No OT',
            'descripcion' => "$this->cname, estado: $this->astatus",
            'color'=>$estilo['color'],
//            'icono'=>$estilo['icono']
        ];
    }
}
