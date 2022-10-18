<?php

namespace Database\Seeders;

use App\Models\Procesador;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProcesadorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Procesador::create(['nombre'=>'INTEL I3 9NA GEN']);
        Procesador::create(['nombre'=>'INTEL I5 9NA GEN']);
        Procesador::create(['nombre'=>'INTEL I5 10MA GEN']);
        Procesador::create(['nombre'=>'INTEL I7 9NA GEN']);
        Procesador::create(['nombre'=>'RYZEN 3']);
        Procesador::create(['nombre'=>'OCTA-CORE 2.3GHZ, 1.8GHZ']);
    }
}
