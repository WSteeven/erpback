<?php

namespace Src\App\RecursosHumanos\SeleccionContratacion;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PolymorphicSeleccionContratacionModelsService
{
    public function actualizarFormacionAcademicaPolimorfica(Model $entidad, Array $listado)
    {
        $ids_elementos = [];
        foreach ($listado as $fila){
            DB::beginTransaction();
            $registro = $entidad->formacionesAcademicas()->find($fila['id']);
            if(!$registro){
                // Si no encuentra un registro lo crea
                $registro = $entidad->formacionesAcademicas()->create($fila);
            }
            $registro->update($fila);
            $ids_elementos[] = $registro->id;
            DB::commit();
        }
        // Verificamos los elementos que se deben eliminar
        $entidad->formacionesAcademicas()->whereNotIn('id', $ids_elementos)->delete();
    }
}
