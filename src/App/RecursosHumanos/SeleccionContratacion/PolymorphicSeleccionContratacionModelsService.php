<?php

namespace Src\App\RecursosHumanos\SeleccionContratacion;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PolymorphicSeleccionContratacionModelsService
{
    public function actualizarFormacionAcademicaPolimorfica(Model $entidad, array $listado)
    {
        // Log::channel('testing')->info('Log', ['instancia del model', get_class($entidad)]);
        $ids_elementos = [];
        try {
            DB::beginTransaction();
            foreach ($listado as $fila) {
                $registro = $entidad->formacionesAcademicas()->find($fila['id']);
                if (!$registro)
                    // Si no encuentra un registro lo crea
                    $registro = $entidad->formacionesAcademicas()->create($fila);
                else
                    // Si encuentra el registro lo actualiza
                    $registro->update($fila);
                $ids_elementos[] = $registro->id;
            }
            // Verificamos los elementos que se deben eliminar
            $entidad->formacionesAcademicas()->whereNotIn('id', $ids_elementos)->delete();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::channel('testing')->error('Error al actualizar formación académica', ['error' => $th->getMessage()]);
            throw $th;
        }
    }
}
