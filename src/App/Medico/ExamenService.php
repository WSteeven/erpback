<?php

namespace Src\App\Medico;

use App\Models\Empleado;
use App\Models\MaterialEmpleado;
use App\Models\MaterialEmpleadoTarea;
use App\Models\Medico\EstadoSolicitudExamen;
use App\Models\Medico\Examen;
use App\Models\Proyecto;
use App\Models\Subtarea;
use App\Models\Tarea;
use Illuminate\Support\Facades\Log;

class ExamenService
{
    private $ignoreRequest = ['campos', 'pendiente_solicitar', 'empleado_idf'];

    public function listar()
    {
        if (request('pendiente_solicitar')) return $this->obtenerPendientesSolicitar();
        return Examen::ignoreRequest($this->ignoreRequest)->filter()->get();
    }

    // --
    private function obtenerPendientesSolicitar()
    {
        Log::channel('testing')->info('Log', ['Mensaje', 'Dentro de obtenerPendientesSolicitar...']);
        // $examenesSolicitados = EstadoSolicitudExamen;
        $todos = Examen::all();
        $results = Examen::ignoreRequest($this->ignoreRequest)->filter()->get();
        return $todos->diff($results);
        // return $todos;
    }
}
