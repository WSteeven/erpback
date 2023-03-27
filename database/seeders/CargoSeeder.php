<?php

namespace Database\Seeders;

use App\Models\Cargo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CargoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $datos = [
            [1, 'GERENTE', '2023-02-24 19:24:13', '2023-03-01 10:17:27'],
            [2, 'JEFE TECNICO', '2023-02-24 19:24:13', '2023-02-24 19:24:13'],
            [3, 'COORDINADOR TECNICO', '2023-02-24 19:24:13', '2023-02-24 19:24:13'],
            [4, 'TÉCNICO LÍDER DE GRUPO', '2023-02-24 19:24:13', '2023-02-24 19:24:13'],
            [5, 'TÉCNICO CABLISTA', '2023-02-24 19:24:13', '2023-02-24 19:24:13'],
            [6, 'TÉCNICO SECRETARIO', '2023-02-24 19:24:13', '2023-02-24 19:24:13'],
            [7, 'TÉCNICO AYUDANTE', '2023-02-24 19:24:13', '2023-02-24 19:24:13'],
            [8, 'TECNICO FUSIONADOR', '2023-02-24 19:24:13', '2023-02-24 19:24:13'],
            [9, 'CHOFER', '2023-02-24 19:24:13', '2023-02-24 19:24:13'],
            [10, 'ADMINISTRADOR', '2023-02-24 19:24:13', '2023-02-24 19:24:13'],
            [11, 'ASISTENTE ADMINISTRATIVO', '2023-02-24 19:24:13', '2023-02-24 19:24:13'],
            [12, 'FISCALIZADOR', '2023-02-24 19:24:13', '2023-02-24 19:24:13'],
            [13, 'COORDINADOR DE BODEGA Y ACTIVOS', '2023-02-24 19:24:13', '2023-02-24 19:24:13'],
            [14, 'OPERADOR DE BODEGA', '2023-02-24 19:24:13', '2023-02-24 19:24:13'],
            [15, 'AUXILIAR DE BODEGA', '2023-02-24 19:24:13', '2023-02-24 19:24:13'],
            [16, 'CONSULTA', '2023-02-24 19:24:13', '2023-02-24 19:24:13'],
            [17, 'CONTABILIDAD', '2023-02-24 19:24:13', '2023-02-24 19:24:13'],
            [18, 'PROGRAMADOR', '2023-02-24 19:24:13', '2023-02-24 19:24:13'],
            [19, 'ASISTENTE GERENCIA GENERAL', '2023-02-24 19:24:13', '2023-02-24 19:24:13'],
            [20, 'COORDINADOR', '2023-02-24 19:24:13', '2023-02-24 19:24:13'],
            [21, 'COORDINADOR SSO', '2023-02-24 19:24:13', '2023-02-24 19:24:13'],
            [22, 'GIS', '2023-02-24 19:24:13', '2023-02-24 19:24:13'],
            [23, 'ASISTENTE LEGAL', '2023-02-24 19:24:13', '2023-02-24 19:24:13'],
            [24, 'COORDINADOR RRHH', '2023-03-01 09:31:29', '2023-03-01 09:31:29'],
            [25, 'JEFE ADMINISTRATIVO', '2023-03-01 10:17:13', '2023-03-01 10:17:13'],
            [26, 'AUXILIAR CONTABLE', '2023-03-03 20:26:03', '2023-03-03 20:26:03'],
            [27, 'AUXILIAR DE SERVICIOS GENERALES', '2023-03-03 20:54:11', '2023-03-03 20:54:11'],
            [28, 'ASISTENTE DE OPERACIONES', '2023-03-03 21:19:08', '2023-03-03 21:19:08'],
            [29, 'MEDICO OCUPACIONAL', '2023-03-08 00:05:02', '2023-03-08 00:05:02'],
            [30, 'TECNICO DE FIBRA OPTICA', '2023-03-08 20:13:31', '2023-03-08 20:13:31'],
            [31, 'ASISTENTE TÉCNICO DE TELECOMUNICACIONES', '2023-03-08 20:14:31', '2023-03-08 20:14:31'],
            [32, 'CABLISTA', '2023-03-08 20:35:44', '2023-03-08 20:35:44'],
            [33, 'AYUDANTE CABLISTA', '2023-03-08 20:35:50', '2023-03-08 20:35:50'],
            [34, 'ACTIVOS FIJOS', '2023-03-15 17:09:26', '2023-03-15 17:09:26'],
        ];
        foreach ($datos as $fila) {
            DB::insert('INSERT INTO `cargos` (`id`, `nombre`, `created_at`, `updated_at`) VALUES(?,?,?,?)', $fila);
        }


        /*
        Cargo::create(['nombre' => 'GERENCIA']);
        Cargo::create(['nombre' => 'JEFE TECNICO']);
        Cargo::create(['nombre' => 'COORDINADOR TECNICO']);

        Cargo::create(['nombre' => 'TÉCNICO LÍDER DE GRUPO']);
        Cargo::create(['nombre' => 'TÉCNICO CABLISTA']);
        Cargo::create(['nombre' => 'TÉCNICO SECRETARIO']);
        Cargo::create(['nombre' => 'TÉCNICO AYUDANTE']);
        Cargo::create(['nombre' => 'TECNICO FUSIONADOR']);
        Cargo::create(['nombre' => 'CHOFER']);

        Cargo::create(['nombre' => 'ADMINISTRADOR']);
        Cargo::create(['nombre' => 'ASISTENTE ADMINISTRATIVO']);
        Cargo::create(['nombre' => 'FISCALIZADOR']);
        Cargo::create(['nombre' => 'COORDINADOR DE BODEGA Y ACTIVOS']);
        Cargo::create(['nombre' => 'OPERADOR DE BODEGA']);
        Cargo::create(['nombre' => 'AUXILIAR DE BODEGA']);
        Cargo::create(['nombre' => 'CONSULTA']);
        Cargo::create(['nombre' => 'CONTABILIDAD']);
        Cargo::create(['nombre' => 'PROGRAMADOR']);
        Cargo::create(['nombre' => 'ASISTENTE GERENCIA GENERAL']);
        Cargo::create(['nombre' => 'COORDINADOR']);
        Cargo::create(['nombre' => 'COORDINADOR SSO']);
        Cargo::create(['nombre' => 'GIS']);
        Cargo::create(['nombre' => 'ASISTENTE LEGAL']);
        */
    }
}
