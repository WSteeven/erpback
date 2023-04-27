<?php

namespace Database\Seeders;

use App\Models\FondosRotativos\Gasto\EstadoViatico;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EstadoViaticoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EstadoViatico::create(['descripcion'=>'APROBADO']);
        EstadoViatico::create(['descripcion'=>'RECHAZADO']);
        EstadoViatico::create(['descripcion'=>'POR APROBAR']);
        EstadoViatico::create(['descripcion'=>'ANULADO']);
    }
}
