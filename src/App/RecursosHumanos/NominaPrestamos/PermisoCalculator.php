<?php

namespace Src\App\RecursosHumanos\NominaPrestamos;

use Carbon\Carbon;

class PermisoCalculator
{
    protected Carbon $horaInicioOficina;
    protected Carbon $horaFinOficina;

    public function __construct()
    {
        // Definir el horario de oficina (de 8:00 a 17:00 por ejemplo)
        $this->horaInicioOficina = Carbon::createFromTime(8, 0);
        $this->horaFinOficina = Carbon::createFromTime(17, 0);
    }

    public function calcularHorasPermiso($fechaInicio, $fechaFin)
    {
        $fechaInicio = Carbon::parse($fechaInicio);
        $fechaFin = Carbon::parse($fechaFin);

        // Verifica si las fechas abarcan más de un día
        if ($fechaInicio->toDateString() == $fechaFin->toDateString()) {
            // Calcular las horas en el mismo día
            return $this->calcularHorasEnDia($fechaInicio, $fechaFin);
        } else {
            // Calcular las horas para múltiples días
            $totalHoras = 0;

            // Calcular horas del primer día
            $primerDiaFin = $this->horaFinOficina->copy()->setDateFrom($fechaInicio);
            $totalHoras += $this->calcularHorasEnDia($fechaInicio, min($primerDiaFin, $fechaFin));

            // Calcular horas de los días intermedios
            $fechaActual = $fechaInicio->copy()->addDay()->startOfDay();
            while ($fechaActual->isBefore($fechaFin->copy()->startOfDay())) {
                if ($this->esDiaLaboral($fechaActual)) {
                    $totalHoras += $this->calcularHorasEnDia(
                        $this->horaInicioOficina->copy()->setDateFrom($fechaActual),
                        $this->horaFinOficina->copy()->setDateFrom($fechaActual)
                    );
                }
                $fechaActual->addDay();
            }

            // Calcular horas del último día
            if ($this->esDiaLaboral($fechaFin)) {
                $ultimoDiaInicio = $this->horaInicioOficina->copy()->setDateFrom($fechaFin);
                $totalHoras += $this->calcularHorasEnDia(max($ultimoDiaInicio, $fechaInicio), $fechaFin);
            }

            return $totalHoras;
        }
    }

    protected function calcularHorasEnDia(Carbon $inicio, Carbon $fin)
    {
        // Limitar a las horas de oficina
        $inicio = max($inicio, $this->horaInicioOficina->copy()->setDateFrom($inicio));
        $fin = min($fin, $this->horaFinOficina->copy()->setDateFrom($fin));

        // Calcular las horas de diferencia si está en horario laboral
        return $inicio->isBefore($fin) ? $inicio->diffInHours($fin) : 0;
    }

    protected function esDiaLaboral(Carbon $fecha)
    {
        // Excluir sábados y domingos
        if ($fecha->isWeekend()) {
            return false;
        }
        // Aquí puedes agregar lógica adicional para excluir días festivos
        // if (in_array($fecha->toDateString(), $this->diasFestivos)) {
        //     return false;
        // }
        return true;
    }
}
