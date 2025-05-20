<?php

namespace Src\App\Appenate\Telconet;

use App\Models\Appenate\MaterialUtilizadoProgresiva;
use App\Models\Appenate\Progresiva;
use App\Models\Appenate\RegistroProgresiva;
use DB;
use Exception;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProgresivaService
{


    /**
     * @throws Throwable
     */
    public function actualizarRegistrosProgresivas(Progresiva $progresiva, array $registros)
    {
        Log::channel('testing')->info('Log', ['actualizarRegistrosProgresivas::antes', $registros]);
        try {
            DB::beginTransaction();
            foreach ($registros as $registro) {
                Log::channel('testing')->info('Log', ['actualizarRegistrosProgresivas::registro foreach', $registro]);
                $registro['progresiva_id'] = $progresiva->id;
                // aqui se debe controlar el crear o actualizar
                $nuevo_registro = RegistroProgresiva::create($registro);
                Log::channel('testing')->info('Log', ['actualizarRegistrosProgresivas::creado', $nuevo_registro]);
                // aqui se crea o actualizan los materiales de cada registro
                $this->actualizarMaterialesRegistrosProgresivas($nuevo_registro, $registro['materiales']);
            }
            DB::commit();
        Log::channel('testing')->info('Log', ['actualizarRegistrosProgresivas::despues']);
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    /**
     * @throws Throwable
     */
    public function actualizarMaterialesRegistrosProgresivas(RegistroProgresiva $registro, array $materiales)
    {
        Log::channel('testing')->info('Log', ['actualizarMaterialesRegistrosProgresivas::antes', $materiales]);
        try {
            DB::beginTransaction();
            foreach ($materiales as $material) {
                $material['registro_id'] = $registro->id;
                $material['material'] = $material['material_utilizado'] == 'OTRO' ? $material['otro_material'] : $material['material_utilizado'];
                MaterialUtilizadoProgresiva::create($material);
            }
            DB::commit();
        Log::channel('testing')->info('Log', ['actualizarMaterialesRegistrosProgresivas::despues']);
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
}
