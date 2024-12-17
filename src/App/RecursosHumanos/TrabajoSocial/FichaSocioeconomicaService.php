<?php

namespace Src\App\RecursosHumanos\TrabajoSocial;

use App\Models\RecursosHumanos\TrabajoSocial\FichaSocioeconomica;
use DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class FichaSocioeconomicaService
{
    public function __construct()
    {
    }

    /**
     * @throws Throwable
     */
    public function actualizarConyuge(FichaSocioeconomica $ficha, mixed $datos)
    {
        Log::channel('testing')->info('Log', ['Antes de actualizar Conyuge']);
        $datos['empleado_id'] = $ficha->empleado_id;
        DB::transaction(function () use ($ficha, $datos) {
            if ($ficha->conyuge()->exists()) $ficha->conyuge()->update($datos);
            else $ficha->conyuge()->create($datos);
        });
    }

    /**
     * @throws Throwable
     */
    public function actualizarHijos(FichaSocioeconomica $ficha, mixed $datos)
    {
        $datos['empleado_id'] = $ficha->empleado_id;
        DB::transaction(function () use ($ficha, $datos) {
            $ficha->hijos()->delete(); //borramos registros anteriores para crear nuevos
            foreach ($datos as $dato) {
                $ficha->hijos()->create($dato);
            }
        });
    }

    /**
     * @throws Throwable
     */
    public function actualizarExperienciaPrevia(FichaSocioeconomica $ficha, mixed $datos)
    {
        $datos['empleado_id'] = $ficha->empleado_id;
        DB::transaction(function () use ($ficha, $datos) {
            if ($ficha->experienciaPrevia()->exists()) $ficha->experienciaPrevia()->update($datos);
            else $ficha->experienciaPrevia()->create($datos);
        });
    }

    /**
     * @throws Throwable
     */
    public function actualizarSituacionSocioeconomica(FichaSocioeconomica $ficha, mixed $datos)
    {
        $datos['empleado_id'] = $ficha->empleado_id;
        DB::transaction(function () use ($ficha, $datos) {
            if ($ficha->situacionSocioeconomica()->exists()) $ficha->situacionSocioeconomica()->update($datos);
            else $ficha->situacionSocioeconomica()->create($datos);
        });
    }


}
