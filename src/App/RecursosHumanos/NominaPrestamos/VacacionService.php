<?php

namespace Src\App\RecursosHumanos\NominaPrestamos;

use App\Models\RecursosHumanos\NominaPrestamos\Vacacion;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Src\Shared\Utils;
use Throwable;

class VacacionService
{

    public function __construct()
    {
    }

    /**
     * Metodo generico para registrar días de vacaciones para una entidad
     * @param int $empleado_id
     * @param int $periodo_id
     * @param Model $entidad
     * @param string|Carbon $fecha_inicio
     * @param string|Carbon $fecha_fin
     * @return void
     * @throws Throwable
     */
    public function registrarDiasVacaciones(int $empleado_id, int $periodo_id, Model $entidad, string|Carbon $fecha_inicio, string|Carbon $fecha_fin)
    {
        try {
            $vacacion = Vacacion::where('empleado_id', $empleado_id)->where('periodo_id', $periodo_id)->first();
            if ($vacacion) {
                DB::beginTransaction();

                $entidad->detallesVacaciones()->create([
                    'vacacion_id' => $vacacion->id,
                    'fecha_inicio' => $fecha_inicio,
                    'fecha_fin' => $fecha_fin,
                    'dias_utilizados' => Utils::calcularDiasTranscurridos($fecha_inicio, $fecha_fin),
                    'observacion' => 'Registro automático de días de vacaciones a tráves de ' . class_basename($entidad)
                ]);
                DB::commit();

            } else throw new Exception("No se encontró registro de vacaciones para el empleado y periodo proporcionados");
        } catch (Throwable $th) {
            DB::rollBack();
            Log::channel('testing')->error('Log', ['Error en registrarDiasVacaciones:', $th->getLine(), $th->getMessage()]);
            throw $th;
        }
    }

    public static function validarDiasDisponibles(int $empleado_id, int $periodo_id, string|Carbon $fecha_inicio, string|Carbon $fecha_fin)
    {
        $dias = 0;
        $dias_disponibles = 0;

        $vacacion = Vacacion::where('empleado_id', $empleado_id)->where('periodo_id', $periodo_id)->first();
        if ($vacacion) {
            $dias = Utils::calcularDiasTranscurridos($fecha_inicio, $fecha_fin);
            $vacacion->detalles()->sum('dias_utilizados');
            $dias_disponibles = $vacacion->dias - $vacacion->detalles()->sum('dias_utilizados');
        }
        return !($dias > $dias_disponibles);
    }

}
