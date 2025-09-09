<?php

namespace Src\App\Plantillas;

use App\Models\Plantillas\PlantillaCapacitacion;
use Carbon\Carbon;

class ReporteCapacitacionService
{
    public function __construct() {}

    public function generar(array $filtros): array
    {
        $capacitacion = PlantillaCapacitacion::with(['capacitador', 'asistentes.departamento'])
            ->findOrFail($filtros['capacitacion_id']);

        $detalle = $capacitacion->asistentes->map(function ($empleado) {
            return [
                'identificacion' => $empleado->identificacion,
                'nombres'        => $empleado->nombres,
                'apellidos'      => $empleado->apellidos,
                'departamento'   => $empleado->departamento->nombre ?? '-',
            ];
        });

        return [
            'capacitacion' => $capacitacion,
            'detalle'      => $detalle,
            'fecha_corte'  => $filtros['fecha'] ?? Carbon::now(),
        ];
    }
}
