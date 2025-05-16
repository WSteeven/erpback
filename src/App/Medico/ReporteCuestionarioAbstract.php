<?php

namespace Src\App\Medico;

use Illuminate\Http\Request;

abstract class ReporteCuestionarioAbstract
{
    public const CODIGOS_ALCOHOL_DROGAS = [
        4,  // 0 - Principal droga que consume
        4.1, // 1 - En caso de seleccionar otros especfique cual
        4.2, // 2 - Frecuencia de consum
        4.3, // 3 - Empleado reconoce eneer n problema de consumo
        4.4, // 4 - Factores psicosociles relaconados al consumo
        4.5, // 5 - En caso de seleccionar otros especifique cual
        4.6, // 6 - Desea recibir tratamiento en caso de ser consumidor
    ];

    // Excel
    abstract public function reportesCuestionarios(Request $request);

    // Text - FPSICO 4.0
    abstract public function imprimirCuestionarioFPSICO();

    protected function obtenerRespuestasConcatenadas($cuestionario)
    {
        $respuestas_concatenadas = '';
        foreach ($cuestionario as $key => $value) {
            $respuestas_concatenadas .= $value['respuesta']['valor'];
        }
        return $respuestas_concatenadas;
    }
}
