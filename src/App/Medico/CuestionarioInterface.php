<?php

namespace Src\App\Medico;

interface CuestionarioInterface
{
    public function guardarCuestionario($respuestas_cuestionario);

    public static function imprimir_reporte($reporte, int $tipo_cuestionario_id);

    public static function imprimir_respuesta_cuestionario($reporte);

    public static function obtener_codigo_antiguedad_empleado($tiempo);

    public static function obtener_codigo_genero($genero);

    public static function mapear_datos($json_data);
}
