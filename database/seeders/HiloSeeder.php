<?php

namespace Database\Seeders;

use App\Models\Hilo;
use App\Models\TipoFibra;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HiloSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Hilo::create(['nombre'=>02]);
        Hilo::create(['nombre'=>12]);
        Hilo::create(['nombre'=>24]);
        Hilo::create(['nombre'=>48]);
        Hilo::create(['nombre'=>144]);

        
        TipoFibra::create(['nombre'=>'ADSS']);
        TipoFibra::create(['nombre'=>'GYFS']);
    }
}
