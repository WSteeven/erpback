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
            ['nombre' => 'TÉCNICO', 'responsable_id' => null],
            ['nombre' => 'LEGAL', 'responsable_id' => null],
            ['nombre' => 'BODEGA', 'responsable_id' => null],
            ['nombre' => 'SEGURIDAD LABORAL', 'responsable_id' => null],
            ['nombre' => 'RECURSOS HUMANOS', 'responsable_id' => null],
            ['nombre' => 'INFORMATICA', 'responsable_id' => null],
            ['nombre' => 'CONTABILIDAD', 'responsable_id' => null],
            ['nombre' => 'GERENCIA GENERAL', 'responsable_id' => null],
            ['nombre' => 'ACTIVOS FIJOS', 'responsable_id' => null],
            ['nombre' => 'MEDICO', 'responsable_id' => null],
            ['nombre' => 'PROCESOS', 'responsable_id' => null],
            ['nombre' => 'SSO', 'responsable_id' => null],
        ]);
    }
}
