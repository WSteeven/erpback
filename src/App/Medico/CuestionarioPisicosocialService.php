<?php

namespace Src\App\Medico;
use App\Models\Medico\RespuestaCuestionarioEmpleado;
use Illuminate\Support\Facades\Log;

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
            $respuesta = RespuestaCuestionarioEmpleado::create([
                'cuestionario_id' => $value['id_cuestionario'],
                'empleado_id' => $this->empleado_id,
            ]);
        }
    }
}
