<?php

namespace Src\App\Appenate\Telconet;

use App\Models\Appenate\MaterialUtilizadoProgresiva;
use App\Models\Appenate\Progresiva;
use App\Models\Appenate\RegistroProgresiva;
use DB;
use Exception;
use Throwable;

class ProgresivaService
{


    /**
     * @throws Throwable
     */
    public function actualizarRegistrosProgresivas(Progresiva $progresiva, array $registros)
    {
//        Log::channel('testing')->info('Log', ['actualizarRegistrosProgresivas::antes', $registros]);
        try {
            DB::beginTransaction();
            foreach ($registros as $registro) {
//                Log::channel('testing')->info('Log', ['actualizarRegistrosProgresivas::registro foreach', $registro]);
                $registro['progresiva_id'] = $progresiva->id;
                // aqui se debe controlar el crear o actualizar
                $nuevo_registro = RegistroProgresiva::create($registro);
//                Log::channel('testing')->info('Log', ['actualizarRegistrosProgresivas::creado', $nuevo_registro]);
                // aqui se crea o actualizan los materiales de cada registro
                $this->actualizarMaterialesRegistrosProgresivas($nuevo_registro, $registro['materiales']);
            }
            DB::commit();
//            Log::channel('testing')->info('Log', ['actualizarRegistrosProgresivas::despues']);
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
//        Log::channel('testing')->info('Log', ['actualizarMaterialesRegistrosProgresivas::antes', $materiales]);
        try {
            DB::beginTransaction();
            foreach ($materiales as $material) {
                $material['registro_id'] = $registro->id;
                $material['material'] = $material['material_utilizado'] == 'OTRO' ? $material['otro_material'] : $material['material_utilizado'];
                MaterialUtilizadoProgresiva::create($material);
            }
            DB::commit();
//            Log::channel('testing')->info('Log', ['actualizarMaterialesRegistrosProgresivas::despues']);
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public static function castearNombrePosteEnProgresivas(RegistroProgresiva $registro)
    {
        return match ($registro->elemento) {
            'AMERICANO' => 'P-' . $registro->propietario . '-0' . $registro->num_elemento . '-A',
            'POZO' => 'PZ-' . $registro->propietario . '-0' . $registro->num_elemento,
            'POSTE/RESERVA' => 'P-' . $registro->propietario . '-0' . $registro->num_elemento . ' / RES',
            'POSTE/MANGA' => 'P-' . $registro->propietario . '-0' . $registro->num_elemento . ' / MAN',
            'POSTE/CAJA' => 'P-' . $registro->propietario . '-0' . $registro->num_elemento . ' / CAJA',
            default => 'P-' . $registro->propietario . '-0' . $registro->num_elemento
        };
    }

    public function obtenerColorPropietarioPosteProgresiva(RegistroProgresiva $registro)
    {
        return match ($registro->propietario) {
            'CNEL' => 'cFF00FFFF',
            'CNT' => 'cFFFF0000',
            'CON','CONECEL' => 'cFF0000FF',
            'TN','TELCONET' => 'cFFFFFF00',
            'NEDETEL' => 'cFF0080FF',
            'PRIV','PRIVADO' => 'cFFFF0080',
            'MUN', 'MUNICIPIO' => 'cFFFFFFFF',
            default => 'cFF888888',
        };
    }

    public function obtenerPuntosCoordenadas(Progresiva $progresiva)
    {
        $registros = $progresiva->registros()->get();

        $points = $registros->map(function ($registro) {
            [$latStr, $lonStr] = explode(' ', $registro->ubicacion_gps);
            $lat = floatval(str_replace(',', '.', $latStr));
            $lon = floatval(str_replace(',', '.', $lonStr));

            return[
                'name'=>self::castearNombrePosteEnProgresivas($registro),
                'description'=>$registro->propietario.' - '.$registro->elemento.' - '.$registro->tipo_poste.' - '.$registro->material_poste,
                'style'=>$this->obtenerColorPropietarioPosteProgresiva($registro),
                'lat' => $lat,
                'lon' => $lon
            ];
        });

        return $points->toArray();
    }
}
