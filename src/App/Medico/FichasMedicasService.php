<?php

namespace Src\App\Medico;

use App\Models\Medico\AccidenteEnfermedadLaboral;
use App\Models\Medico\CategoriaExamenFisico;
use App\Models\Medico\ResultadoExamen;
use App\Models\Medico\SolicitudExamen;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class FichasMedicasService
{
    public function consultarResultadosExamenes(int $registro_empleado_examen_id)
    {
        $examenes_solicitados = collect([]);
        $solicitudesExamenes = SolicitudExamen::where('registro_empleado_examen_id', $registro_empleado_examen_id)->where('estado_solicitud_examen', SolicitudExamen::SOLICITADO)->latest()->get();
        $resultadosExamenesRegistrados = $this->consultarResultadosExamenesRegistrados($registro_empleado_examen_id);
        Log::channel('testing')->info('Log', ['resultadosExamenesRegistrados', $resultadosExamenesRegistrados]);

        foreach ($solicitudesExamenes as $solicitudExamen) {
            foreach ($solicitudExamen->examenesSolicitados as $examenSolicitado) {
                // Log::channel('testing')->info('Log', ['consultarResultadosExamenes', $examenSolicitado->examen->nombre]);
                $examenes_solicitados->push([
                    'examen' => $examenSolicitado->examen->nombre,
                    'fecha_asistencia' => Carbon::parse($examenSolicitado->fecha_hora_asistencia)->format('Y-m-d'),
                    'resultados' => $this->filtrarResultadosExamenesRegistradosPorIdExamenSolicitado($resultadosExamenesRegistrados, $examenSolicitado->id),
                ]);
            }
        }

        Log::channel('testing')->info('Log', ['examenes_solicitados', $examenes_solicitados]);
        return $examenes_solicitados;
    }

    public function consultarResultadosExamenesRegistrados(int $registro_empleado_examen_id)
    {
        $solicitudExamenService = new SolicitudExamenService();
        $ids_examenes_solicitados = $solicitudExamenService->obtenerIdsExamenesSolicitados($registro_empleado_examen_id);

        $results = ResultadoExamen::ignoreRequest(['campos', 'registro_empleado_examen_id'])->filter()->whereIn('examen_solicitado_id', $ids_examenes_solicitados)->get();
        return $results;
    }

    public function filtrarResultadosExamenesRegistradosPorIdExamenSolicitado(Collection $resultadosExamenesRegistrados, int $examen_solicitado_id)
    {
        return $resultadosExamenesRegistrados->filter(fn ($resultado_examen_registrado) => $resultado_examen_registrado->examen_solicitado_id == $examen_solicitado_id)->map(fn ($resultado_examen_registrado) => [
            'resultado' => $resultado_examen_registrado->resultado,
            'configuracion_examen_campo' => $resultado_examen_registrado->configuracionExamenCampo->campo,
            'unidad_medida' => $resultado_examen_registrado->configuracionExamenCampo->unidad_medida,
            'observaciones' => $resultado_examen_registrado->observaciones,
        ]);
    }

    public function mapearObservacionesExamenFisicoRegional($observaciones_examen_fisico_regional)
    {
        return $observaciones_examen_fisico_regional->map(fn ($item) => [
            'categoria' => CategoriaExamenFisico::find($item['categoria_examen_fisico_id'])->nombre,
            'observacion' => $item['observacion'],
        ]);
    }

    public static function mapearExamenesFisicosRegionales($ficha_medica)
    {
        $mapeado = $ficha_medica->examenesFisicosRegionales()?->get()->map(fn ($item) => [
            'categoria_examen_fisico_id' => $item->categoria_examen_fisico_id,
            'categoria_examen_fisico' => $item->categoriaexamenFisico->nombre,
            'region_cuerpo' => $item->categoriaexamenFisico->region?->nombre,
            'observacion' => $item['observacion'],
        ]);
        return $mapeado;
    }

    public static function mapearAccidenteTrabajo($ficha_medica, string $accidente_enfermedad_laboral)
    {
        $modelo = $ficha_medica->accidentesEnfermedades()->where('tipo', $accidente_enfermedad_laboral)->orderBy('id', 'desc')->first();
        return $modelo;
        [
            'id' => $modelo->id,
            'calificado_iss' => boolval($modelo?->calificado_iss),
            'instituto_seguridad_social' => $modelo?->instituto_seguridad_social,
            'fecha' => $modelo?->fecha ? Carbon::parse($modelo?->fecha)?->format('Y-m-d') : null,
            'observacion' => $modelo?->observacion,
            'tipo_descripcion_antecedente_trabajo' => $modelo?->tipo,
            'ficha_preocupacional_id' => $modelo?->ficha_preocupacional_id,
        ];
        // return $ficha_medica->accidentesEnfermedades()->where('tipo', AccidenteEnfermedadLaboral::ACCIDENTE_TRABAJO)->orderBy('id', 'desc')->first();
    }

    public function mapearConstanteVital($ficha_medica)
    {
        return [
            'presion_arterial' => $ficha_medica->constanteVital()->first()?->presion_arterial,
            'temperatura' => $ficha_medica->constanteVital()->first()?->temperatura,
            'frecuencia_cardiaca' => $ficha_medica->constanteVital()->first()?->frecuencia_cardiaca,
            'saturacion_oxigeno' => $ficha_medica->constanteVital()->first()?->saturacion_oxigeno,
            'frecuencia_respiratoria' => $ficha_medica->constanteVital()->first()?->frecuencia_respiratoria,
            'peso' => $ficha_medica->constanteVital()->first()?->peso,
            'estatura' => $ficha_medica->constanteVital()->first()?->estatura,
            'talla' => $ficha_medica->constanteVital()->first()?->talla,
            'indice_masa_corporal' => $ficha_medica->constanteVital()->first()?->indice_masa_corporal,
            'perimetro_abdominal' => $ficha_medica->constanteVital()->first()?->perimetro_abdominal,
        ];
    }
}
