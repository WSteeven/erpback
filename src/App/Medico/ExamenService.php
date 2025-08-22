<?php

namespace Src\App\Medico;

use App\Models\Medico\Examen;
use Log;

class ExamenService
{
    private array $ignoreRequest = ['empleado_id','campos', 'pendiente_solicitar','registro_empleado_examen_id'];

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

//        Log::channel('testing')->info('Log', ['filters', $filters]);
        $results = Examen::ignoreRequest($this->ignoreRequest)->filter($filters)->get();
//        return $todos->diff($results);
        return $todos;
    }
}
