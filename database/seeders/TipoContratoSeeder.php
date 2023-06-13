<?php

namespace Database\Seeders;

use App\Models\TipoContrato;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoContratoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TipoContrato::insert(
            [['nombre' => 'Indefinido'],
            ['nombre' => 'Emergente'],
            ['nombre' => 'Obra o Servicio Determinado'],
            ['nombre' => 'Contrato de trabajo eventual']]
        );
    }
}
