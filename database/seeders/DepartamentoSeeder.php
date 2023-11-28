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
            ['nombre' => 'TÉCNICO', 'responsable_id' => 3],
            ['nombre' => 'LEGAL', 'responsable_id' => null],
            ['nombre' => 'BODEGA', 'responsable_id' => 8],
            ['nombre' => 'SEGURIDAD LABORAL', 'responsable_id' => null],
            ['nombre' => 'RECURSOS HUMANOS', 'responsable_id' => 5],
            ['nombre' => 'INFORMATICA', 'responsable_id' => 25],
            ['nombre' => 'CONTABILIDAD', 'responsable_id' => 10],
            ['nombre' => 'GERENCIA GENERAL', 'responsable_id' => 2],
            ['nombre' => 'ACTIVOS FIJOS', 'responsable_id' => 12],
            ['nombre' => 'MEDICO', 'responsable_id' => 116],
            ['nombre' => 'PROCESOS', 'responsable_id' => null],
            ['nombre' => 'SSO', 'responsable_id' => 14],
        ]);
    }
}
