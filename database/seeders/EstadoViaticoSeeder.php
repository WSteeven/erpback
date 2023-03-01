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
        EstadoViatico::create(['descripcion'=>'APROBADO','transcriptor'=>'ADMINISTRADOR','fecha_trans'=>date('Y-m-d')]);
        EstadoViatico::create(['descripcion'=>'RECHAZADO','transcriptor'=>'ADMINISTRADOR','fecha_trans'=>date('Y-m-d')]);
        EstadoViatico::create(['descripcion'=>'POR APROBAR','transcriptor'=>'ADMINISTRADOR','fecha_trans'=>date('Y-m-d')]);
    }
}
