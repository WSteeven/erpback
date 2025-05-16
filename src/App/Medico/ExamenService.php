<?php

namespace Src\App\Medico;

use App\Models\Medico\Examen;
use Log;

class ExamenService
{
    private $ignoreRequest = ['campos', 'pendiente_solicitar'];

    public function listar()
    {
        if (request('pendiente_solicitar')) return $this->obtenerPendientesSolicitar();
        return Examen::ignoreRequest($this->ignoreRequest)->filter()->get();
    }

    // ExamenFilter
    private function obtenerPendientesSolicitar()
    {
        $todos = Examen::all();
        $filters = request()->only(['empleado_id', 'registro_empleado_examen_id']);

        Log::channel('testing')->info('Log', ['filters', $filters]);
        $results = Examen::ignoreRequest($this->ignoreRequest)->filter($filters)->get();
        return $todos->diff($results);
    }
}
