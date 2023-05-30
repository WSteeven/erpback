<?php

namespace Database\Seeders;

use App\Models\RecursosHumanos\NominaPrestamos\ConceptoEgreso;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConceptoEgresoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ConceptoEgreso::insert([['nombre'=> 'Efectivo'],
       ['nombre'=> 'Cheque'],
       ['nombre'=> 'Nota de Debito'],
       ]);
    }
}
