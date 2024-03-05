<?php

namespace Database\Seeders;

use App\Models\Ventas\TipoChargeback;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoChargebackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TipoChargeback::insert([
            ['nombre' => 'Porcentaje'],
            ['nombre' => 'MalaVenta'],
        ]);
    }
}
