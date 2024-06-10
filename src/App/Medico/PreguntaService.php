<?php

namespace Src\App\Medico;

use App\Models\Medico\RespuestaCuestionarioEmpleado;

class PreguntaService
{
    public function empleadoYaLlenoCuestionario(int $empleado_id)
    {
        return RespuestaCuestionarioEmpleado::where('empleado_id', $empleado_id)->whereYear('created_at', now()->year)->with('cuestionario')->exists();
    }
}
