<?php

namespace Src\App\RecursosHumanos\TrabajoSocial;

use App\Models\Empleado;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Src\App\RegistroTendido\GuardarImagenIndividual;
use Src\Config\RutasStorage;
use Src\Shared\Utils;
use Throwable;

class PolymorphicTrabajoSocialModelsService
{
    /**
     * @throws Throwable
     */
    public function actualizarViviendaPolimorfica(Model $entidad, array $listado)
    {
//      Log::channel('testing')->info('Log', ['Antes de actualizarViviendaPolimorfica', $listado]);
        $empleado = Empleado::find($entidad->empleado_id);
        $listado['empleado_id'] = $entidad->empleado_id;
        if ($listado['imagen_croquis'] && Utils::esBase64($listado['imagen_croquis'])) {
            $listado['imagen_croquis'] = (new GuardarImagenIndividual($listado['imagen_croquis'], RutasStorage::RUTAGRAMAS, $empleado->identificacion . '_' . Carbon::now()->getTimestamp()))->execute();
        } else {
            unset($listado['imagen_croquis']);
        }
        DB::transaction(function () use ($entidad, $listado) {
            $vivienda = $entidad->vivienda()->first();
            if ($vivienda) {
                $entidad->vivienda()->update($listado);
            } else
                $entidad->vivienda()->create($listado);

        });
    }

    /**
     * @throws Throwable
     */
    public function actualizarSaludPolimorfica(Model $entidad, array $listado)
    {
        Log::channel('testing')->info('Log', ['Antes de actualizarSaludPolimorfica', $listado]);
        // Se quita variables booleanas que no se usan
        unset($listado['tiene_discapacidad']);
        unset($listado['tiene_familiar_dependiente_discapacitado']);
        unset($listado['tiene_enfermedad_cronica']);

        DB::transaction(function () use ($entidad, $listado) {
            $salud = $entidad->salud()->first();
            if ($salud) {
                $entidad->salud()->update($listado);
            } else
                $entidad->salud()->create($listado);

        });
    }

    /**
     * @throws Throwable
     */
    public function actualizarComposicionFamiliarPolimorfica(Model $entidad, array $listado)
    {
        $ids_elementos = [];
        try {
            DB::beginTransaction();
            foreach ($listado as $fila) {
                $registro = $entidad->composicionFamiliar()->find($fila['id']);
                if (!$registro)
                    // Si no encuentra un registro lo crea
                    $registro = $entidad->composicionFamiliar()->create($fila);
                else
                    // Si encuentra el registro lo actualiza
                    $registro->update($fila);
                $ids_elementos[] = $registro->id;
            }
            // Verificamos los elementos que se deben eliminar
            $entidad->composicionFamiliar()->whereNotIn('id', $ids_elementos)->delete();
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            Log::channel('testing')->error('Error  actualizarComposicionFamiliarPolimorfica', ['error' => $th->getMessage()]);
            throw $th;
        }
    }


}
