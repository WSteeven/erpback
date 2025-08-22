<?php

namespace Src\App\RecursosHumanos\SeleccionContratacion;

use App\Models\RecursosHumanos\SeleccionContratacion\EvaluacionPersonalidad;

class EvaluacionPersonalidadService
{
    public function __construct()
    {
    }

    /**
     * Verifica si existe una evaluación para el ID de postulación proporcionado,
     * en caso de que exista devuelve true, caso contrario devuelve falso.
     * @param int $postulacion_id
     * @param bool $completado
     * @return mixed
     */
    public static function verificarExisteEvaluacionPostulacion(int $postulacion_id, bool $completado = false)
    {
        return EvaluacionPersonalidad::where('postulacion_id', $postulacion_id)->where('completado', $completado)->exists();
    }

}
