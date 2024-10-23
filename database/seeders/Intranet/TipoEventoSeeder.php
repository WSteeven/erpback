<?php

namespace Database\Seeders\Intranet;

use App\Models\Intranet\TipoEvento;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoEventoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TipoEvento::create(['nombre'=>'capacitaciones']);
        TipoEvento::create(['nombre'=>'reunion']);
        TipoEvento::create(['nombre'=>'general']);
    }
}
