<?php

namespace Src\App\Medico;

use App\Models\Medico\CuestionarioPublico;
use App\Models\Medico\Persona;
use App\Models\Medico\RespuestaCuestionarioEmpleado;
use Illuminate\Database\Eloquent\Builder;

class CuestionariosRespondidosService
{
    public function empleadoYaLlenoCuestionario(int $empleado_id, int $tipo_cuestionario_id)
    {
        return RespuestaCuestionarioEmpleado::where('empleado_id', $empleado_id)->whereYear('created_at', now()->year)->whereHas('cuestionario', function (Builder $q) use ($tipo_cuestionario_id) {
            $q->where('tipo_cuestionario_id', $tipo_cuestionario_id);
        })->exists();
    }

    public function personaYaLlenoCuestionario(string $identificacion, int $tipo_cuestionario_id)
    {
        $ultimaPersona = Persona::where('identificacion', $identificacion)->orderBy('created_at', 'desc')->first();

        if (!$ultimaPersona) return false;

        return CuestionarioPublico::where('persona_id', $ultimaPersona->id)->whereYear('created_at', now()->year)->whereHas('cuestionario', function (Builder $q) use ($tipo_cuestionario_id) {
            $q->where('tipo_cuestionario_id', $tipo_cuestionario_id);
        })->exists();
    }
}
