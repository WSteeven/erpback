<?php

namespace Database\Seeders;

use App\Models\Cargo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CargoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Cargo::create(['nombre' => 'GERENCIA']);
        Cargo::create(['nombre' => 'JEFE TECNICO']);
        Cargo::create(['nombre' => 'COORDINADOR TECNICO']);
        Cargo::create(['nombre' => 'TECNICO']);

        Cargo::insert([
            'nombre' => 'TÉCNICO LÍDER DE GRUPO',
            'nombre' => 'TÉCNICO CABLISTA',
            'nombre' => 'TÉCNICO SECRETARIO',
            'nombre' => 'TÉCNICO AYUDANTE',
            'nombre' => 'TECNICO FUSIONADOR',
            'nombre' => 'CHOFER'
        ]);

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
    }
}
