<?php

namespace Src\App\Medico;

use Illuminate\Http\Request;

abstract class ReporteCuestionarioAbstract
{
    public const CODIGOS_ALCOHOL_DROGAS = [
        4,
        4.1,
        4.2,
        4.3,
        4.4,
        4.5,
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
