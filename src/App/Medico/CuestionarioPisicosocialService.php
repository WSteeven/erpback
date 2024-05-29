<?php

namespace Src\App\Medico;

use App\Exports\Medico\ReporteCuestionarioAlcoholDrogasExport;
use App\Exports\Medico\ReporteCuestionarioPisicosocialExport;
use App\Exports\Medico\RespuestasCuestionarioExport;
use App\Models\Medico\RespuestaCuestionarioEmpleado;
use App\Models\Medico\TipoCuestionario;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Excel as ExcelCosnstant;
use Maatwebsite\Excel\Facades\Excel;

class CuestionarioPisicosocialService
{
    private $empleado_id;

    public function __construct($empleado_id)
    {

        $this->empleado_id = $empleado_id;
    }

    public function guardarCuestionario($respuestas_cuestionario)
    {
        /* foreach ($respuestas_cuestionario as $key => $value) {
            RespuestaCuestionarioEmpleado::create([
                'cuestionario_id' => !is_string($value['id_cuestionario']) ? $value['id_cuestionario'] : null,
                'respuesta' => is_string($value['id_cuestionario']) ? $value['id_cuestionario'] : null,
                'empleado_id' => $this->empleado_id,
            ]);
        } */
        // Log::channel('testing')->info('Log', ['respuestas_cuestionario',  $respuestas_cuestionario]);
        foreach ($respuestas_cuestionario as $cuestionario_respuesta) {
            // Log::channel('testing')->info('Log', ['cuestionario_respuesta',  $cuestionario_respuesta]);
            RespuestaCuestionarioEmpleado::create([
                'cuestionario_id' => !is_string($cuestionario_respuesta['id_cuestionario']) ? $cuestionario_respuesta['id_cuestionario'] : null,
                'respuesta_texto' => $cuestionario_respuesta['respuesta_texto'], // is_string($cuestionario_respuesta['respuesta']) ? $cuestionario_respuesta['respuesta'] : null,
                'empleado_id' => $this->empleado_id,
            ]);
        }
    }

    public static function imprimir_reporte($reporte, int $tipo_cuestionario_id)
    {
        $nombre_reporte = 'reporte_c_p';

        switch ($tipo_cuestionario_id) {
            case TipoCuestionario::CUESTIONARIO_PSICOSOCIAL:
                $export_excel = new ReporteCuestionarioPisicosocialExport($reporte);
                break;
            case TipoCuestionario::CUESTIONARIO_DIAGNOSTICO_CONSUMO_DE_DROGAS:
                $export_excel = new ReporteCuestionarioAlcoholDrogasExport($reporte);
                break;
        }
        return Excel::download($export_excel, $nombre_reporte . '.xlsx');
    }

    public static function imprimir_respuesta_cuestionario($reporte)
    {
        $nombre_reporte = 'reporte_c_p';
        $export_excel = new RespuestasCuestionarioExport($reporte);
        return Excel::download($export_excel, $nombre_reporte . '.csv', ExcelCosnstant::CSV);
    }

    public static function obtener_codigo_antiguedad_empleado($tiempo)
    {
        $anios_labores = array(
            array(0, 1),
            array(1, 2),
            array(2, 3),
            array(3, 4),
            array(4, 5),
            array(5, 6),
            array(6, 7),
            array(7, 8),
            array(8, 9),
            array(9, 10),
            array(10, null), // Rango para mayores de 10 años
        );
        $codigo = "";
        foreach ($anios_labores as $indice => $rango) {
            // Verifica si el número de años trabajados está dentro del rango
            if ($rango[1] === null) {
                // Caso especial para más de 10 años
                if ($tiempo > $rango[0]) {
                    $codigo = '(' . ($indice + 1) . ')'; // Corregido aquí
                }
            } elseif ($tiempo >= $rango[0] && $tiempo < $rango[1]) {
                $codigo = $indice >= 9 ? '(' . ($indice + 1) . ')' : $indice + 1; // Corregido aquí
            }
        }
        // Retorna null si no se encuentra un rango correspondiente
        return $codigo;
    }

    public static function obtener_codigo_genero($genero)
    {
        return $genero == 'F' ? 1 : 2;
    }
    public static function mapear_datos($json_data)
    {
        // Arreglo para almacenar los datos mapeados
        $datos_mapeados = array();
        // Recorrer las filas del JSON
        foreach ($json_data as $fila) {
            // Obtener la fecha de creación de respuesta
            $fecha_creacion_respuesta = $fila["fecha_creacion_respuesta"];
            // Obtener el código de antigüedad y género
            $codigo_antigueda_genero = $fila["codigo_antigueda_genero"];
            $respuestas = $fila["respuestas_concatenadas"];
            // Obtener las respuestas concatenadas (opcional)
            // $respuestas_concatenadas = $fila["respuestas_concatenadas"];
            // Agregar la fila mapeada al arreglo
            $datos_mapeados[] = array(
                '"' . $fecha_creacion_respuesta . '",',
                '"' . $codigo_antigueda_genero . '",',
                '"' . $respuestas . '",',
                '""'
            );
        }
        return $datos_mapeados;
    }
}
