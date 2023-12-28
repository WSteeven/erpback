<?php

namespace Database\Seeders;

use App\Models\Ventas\EsquemaComision;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EsquemaComisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EsquemaComision::insert([
            ['mes_liquidacion' => 1, 'esquema_comision' => 'Comision', 'tarifa_basica' => 2.5],
            ['mes_liquidacion' => 1, 'esquema_comision' => 'Bono Cumplimiento ARPU', 'tarifa_basica' => 0.5],
            ['mes_liquidacion' => 1, 'esquema_comision' => 'Bono por medio de pago (TC)', 'tarifa_basica' => 0.5],
            ['mes_liquidacion' => 3, 'esquema_comision' => 'Bono calidad 90 dias', 'tarifa_basica' => 0.5],
            ['mes_liquidacion' => 3, 'esquema_comision' => 'Bono calidad 180 dias', 'tarifa_basica' => 1],
        ]);
    }
}
