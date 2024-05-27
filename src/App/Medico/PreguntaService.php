<?php

namespace Src\App\Medico;

use App\Models\Medico\RespuestaCuestionarioEmpleado;
use Illuminate\Database\Eloquent\Builder;

class PreguntaService
{
    public function empleadoYaLlenoCuestionario(int $empleado_id, int $tipo_cuestionario_id)
    {
        return RespuestaCuestionarioEmpleado::where('empleado_id', $empleado_id)->whereYear('created_at', now()->year)->whereHas('cuestionario', function (Builder $q) use ($tipo_cuestionario_id) {
            $q->where('tipo_cuestionario_id', $tipo_cuestionario_id);
        })->exists();
    }
}
