<?php

namespace Src\App;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class PolymorphicGenericService
{


    public function __construct()
    {
    }

    /**
     * La función `crearActividadPolimorfica` crea una actividad polimórfica asociada con una entidad
     * modelo dada y una matriz de datos dentro de una transacción de base de datos en PHP.
     *
     * @param Model $entidad El parámetro `$entidad` es una instancia de Modelo. Se utiliza para crear
     * una relación polimórfica con actividades.
     * @param array $data El parámetro `$data` en la función `crearActividadPolimorfica` es una matriz
     * que contiene los datos necesarios para crear una nueva actividad asociada con la entidad
     * proporcionada. Estos datos generalmente incluyen atributos como fecha_hora, actividad_realizada,
     * observacion, fotografia y empleado_id.
     *
     * @return mixed $actividad.
     * @throws Throwable
     */
    public function crearActividadPolimorfica(Model $entidad, array $data)
    {
        $data['tarea_id'] = $data['tarea']; // se castea la propiedad tarea
        try {
            DB::beginTransaction();
            $actividad = $entidad->actividades()->create($data);
            DB::commit();

            return $actividad;
        } catch (Throwable $th) {
            DB::rollBack();
            throw new Exception($th->getMessage() . '. [LINE CODE ERROR]: ' . $th->getLine() . $th->getCode());
        }
    }

    /**
     * La función `actualizarActividadPolimorfica` actualiza una actividad específica asociada a una
     * entidad determinada usando relaciones polimórficas en PHP.
     *
     * @param Model $entidad El parámetro `$entidad` es una instancia de Modelo. Representa una entidad
     * u objeto que tiene una relación con actividades. La función recorre las actividades asociadas con
     * esta entidad y actualiza una actividad específica en función de los datos proporcionados.
     * @param array $data Los datos a actualizar.
     *
     * @return bool Esta función devuelve un valor booleano. Devuelve "verdadero" si actualiza correctamente
     * la actividad con el ID proporcionado en la entidad dada y "falso" si no encuentra una actividad
     * con ese ID.
     * @throws Throwable
     */
    public function actualizarActividadPolimorfica(Model $entidad, array $data)
    {
        $data['tarea_id'] = $data['tarea']; // se castea la propiedad tarea
        try {
            foreach ($entidad->actividades as $actividad) {
                if ($actividad->id == $data['id']) {
                    DB::beginTransaction();
                    $actividad->update($data);
                    $actividad->save();
                    DB::commit();
                    return true;
                }
            }
            return false;
        } catch (Throwable $th) {
            DB::rollBack();
            throw new Exception($th->getMessage() . '. [LINE CODE ERROR]: ' . $th->getLine() . $th->getCode());
        }
    }

    /**
     * @throws Throwable
     */
    public function actualizarDiscapacidades(Model $entidad, array $data)
    {
        try {
            DB::beginTransaction();
            foreach ($data as $discapacidad) {
                $discapacidad['tipo_discapacidad_id'] = $discapacidad['tipo_discapacidad'];
                $registro = $entidad->discapacidades()->where('tipo_discapacidad_id', $discapacidad['tipo_discapacidad'])->first();
                if (!$registro) $entidad->discapacidades()->create($discapacidad);
                else $registro->update($discapacidad);
            }
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            Log::channel('testing')->error('Error al actualizar discapacidades', ['error' => $th->getMessage()]);
            throw $th;
        }
    }


}
