<?php

namespace Database\Seeders;

use App\Models\RecursosHumanos\NominaPrestamos\ConceptoIngreso;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConceptoIngresoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ConceptoIngreso::insert([
            ['nombre' => 'Alimentacion','calculable_iess'=>true],
            ['nombre' => 'Horas Extras','calculable_iess'=>false],
            ['nombre' => 'Comisiones','calculable_iess'=>true]
        ]);
    }
}
