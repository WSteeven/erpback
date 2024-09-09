<?php

namespace Src\App;

use App\Models\ActividadRealizada;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Src\App\RegistroTendido\GuardarImagenIndividual;
use Src\Config\RutasStorage;
use Src\Shared\Utils;

class ActividadRealizadaService
{

    public function __construct()
    {
    }

    public static function index(Model $entidad)
    {
        return $entidad->actividades()->get();
    }

    public static function store(Model $entidad, array|ActividadRealizada $actividad, RutasStorage  $ruta = RutasStorage::FOTOGRAFIAS_ACTIVIDADES_REALIZADAS)
    {
        try {
            DB::beginTransaction();
            if ($actividad['fotografia']) $actividad['fotografia'] = (new GuardarImagenIndividual($actividad['fotografia'], $ruta))->execute();

            $modelo = $entidad->actividades()->create($actividad);
            DB::commit();
            return $modelo;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new Exception('Error en tabla polimórfica. ' . $th->getMessage() . '. [LINE CODE ERROR]: ' . $th->getLine(), $th->getCode());
        }
    }

    public static function update(Model $entidad, array|ActividadRealizada $actividad, RutasStorage  $ruta = RutasStorage::FOTOGRAFIAS_ACTIVIDADES_REALIZADAS)
    {
        try {
            DB::beginTransaction();
            if ($actividad['fotografia'] && Utils::esBase64($actividad['fotografia']))
                $actividad['fotografia'] = (new GuardarImagenIndividual($actividad['fotografia'], $ruta))->execute();
            else unset($actividad['fotografia']);

            $modelo = $entidad->actividades()->where('id', $actividad['id'])->update($actividad);
            DB::commit();
            return $modelo;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new Exception('Error en tabla polimórfica. ' . $th->getMessage() . '. [LINE CODE ERROR]: ' . $th->getLine(), $th->getCode());
        }
    }
}
