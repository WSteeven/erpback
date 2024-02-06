<?php

namespace Src\App\Medico;

use App\Models\Medico\Examen;

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
        $results = Examen::ignoreRequest($this->ignoreRequest)->filter()->get();
        return $todos->diff($results);
    }
}
