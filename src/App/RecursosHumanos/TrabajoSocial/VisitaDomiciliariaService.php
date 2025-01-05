<?php

namespace Src\App\RecursosHumanos\TrabajoSocial;

use App\Models\RecursosHumanos\TrabajoSocial\VisitaDomiciliaria;
use DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class VisitaDomiciliariaService
{
    public function __construct()
    {
    }

    /**
     * @throws Throwable
     */
    public function actualizarEconomiaFamiliar(VisitaDomiciliaria $visita, array $datos)
    {
        Log::channel('testing')->info('Log', ['Antes de actualizar Conyuge']);
        $datos['empleado_id'] = $visita->empleado_id;
        DB::transaction(function () use ($visita, $datos) {
            if ($visita->economiaFamiliar()->exists()) $visita->economiaFamiliar()->update($datos);
            else $visita->economiaFamiliar()->create($datos);
        });
    }
}
