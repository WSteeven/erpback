<?php

namespace App\Http\Resources\Medico;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Src\App\Medico\FichasMedicasService;

class FichaReintegroResource extends JsonResource
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
            'fecha_reingreso' => $this->fecha_reingreso ? Carbon::parse($this->fecha_reingreso)->format('Y-m-d') : null,
            'fecha_ultimo_dia_laboral' => $this->fecha_ultimo_dia_laboral ? Carbon::parse($this->fecha_ultimo_dia_laboral)->format('Y-m-d') : null,
            'causa_salida' => $this->causa_salida,
            'motivo_consulta' => $this->motivo_consulta,
            'enfermedad_actual' => $this->enfermedad_actual,
            'observacion_examen_fisico_regional' => $this->observacion_examen_fisico_regional,
            'cargo' => $this->cargo_id,
            // Constante vital
            'constante_vital' => [
                'presion_arterial' => $this->constanteVital()->first()?->presion_arterial,
                'temperatura' => $this->constanteVital()->first()?->temperatura,
                'frecuencia_cardiaca' => $this->constanteVital()->first()?->frecuencia_cardiaca,
                'saturacion_oxigeno' => $this->constanteVital()->first()?->saturacion_oxigeno,
                'frecuencia_respiratoria' => $this->constanteVital()->first()?->frecuencia_respiratoria,
                'peso' => $this->constanteVital()->first()?->peso,
                'estatura' => $this->constanteVital()->first()?->estatura,
                'talla' => $this->constanteVital()->first()?->talla,
                'indice_masa_corporal' => $this->constanteVital()->first()?->indice_masa_corporal,
                'perimetro_abdominal' => $this->constanteVital()->first()?->perimetro_abdominal,
            ],
            // Examenes fisicos regionales
            'examenes_fisicos_regionales' => FichasMedicasService::mapearExamenesFisicosRegionales($this), // $this->mapearExamenesFisicosRegionales(),
            /*  $this->examenesFisicosRegionales()->get()->map(fn ($item) => [
                'categoria_examen_fisico_id' => $item->categoria_examen_fisico_id,
                'categoria_examen_fisico' => $item->categoriaexamenFisico->nombre,
                'observacion' => $item['observacion'],
            ]), */
            // Aptitudes medicas
            'aptitud_medica' => $this->aptitudesMedicas,
        ];
    }

    
}
