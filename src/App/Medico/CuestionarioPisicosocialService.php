<?php

namespace Src\App\Medico;

use App\Exports\Medico\ReporteCuestionarioPisicosocialExport;
use App\Models\Medico\RespuestaCuestionarioEmpleado;
use Illuminate\Support\Facades\Log;
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
        foreach ($respuestas_cuestionario as $key => $value) {
            RespuestaCuestionarioEmpleado::create([
                'cuestionario_id' => $value['id_cuestionario'],
                'empleado_id' => $this->empleado_id,
            ]);
        }
    }
    public static function imprimir_reporte($reporte){
        $nombre_reporte ='reporte_c_p';
        $export_excel = new ReporteCuestionarioPisicosocialExport($reporte);
        return Excel::download($export_excel, $nombre_reporte . '.xlsx');
    }
}
