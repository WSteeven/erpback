<?php

namespace Database\Seeders;

use App\Models\RecursosHumanos\Banco ;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BancoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Banco::insert([
            ['nombre' => 'PRODUBANCO','codigo'=>'0036'],
            ['nombre' => 'BOLIVARIANO','codigo'=>'0037'],
            ['nombre' => 'GUAYAQUIL','codigo'=>'0017'],
            ['nombre' => 'LOJA','codigo'=>'0029'],
            ['nombre' => 'PACIFICO','codigo'=>'0030'],
            ['nombre' => 'PICHINCHA ','codigo'=>'0010'],
            ['nombre' => 'RUMINAHUI','codigo'=>'0042'],
            ['nombre' => 'INTERNACIONAL','codigo'=>'0032'],
            ['nombre' => 'MACHALA','codigo'=>'0025'],
            ['nombre' => 'AUSTRO','codigo'=>'0035'],
            ['nombre' => 'COOP. JUVENTUD ECUATORIANA PROGRESISTA LTDA.','codigo'=>'9974'],
        ]);
    }
}
