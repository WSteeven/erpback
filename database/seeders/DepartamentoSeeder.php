<?php

namespace Database\Seeders;

use App\Models\Departamento;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartamentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Departamento::insert([
            ['nombre' => 'TÉCNICO'],
            ['nombre' => 'LEGAL'],
            ['nombre' => 'BODEGA'],
            ['nombre' => 'SEGURIDAD LABORAL'],
            ['nombre' => 'RECURSOS HUMANOS'],
            ['nombre' => 'INFORMATICA'],
            ['nombre' => 'CONTABILIDAD'],
            ['nombre' => 'GERENCIA GENERAL'],
            ['nombre' => 'ACTIVOS FIJOS'],
            ['nombre' => 'MEDICO'],
            ['nombre' => 'PROCESOS'],
            ['nombre' => 'SSO'],
        ]);
    }
}
