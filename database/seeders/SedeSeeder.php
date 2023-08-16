<?php

namespace Database\Seeders;

use App\Models\Sede;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SedeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Sede::insert(['nombre'=> 'Machala']
        ,['nombre'=> 'Guayaquil']
        ,['nombre'=> 'Esmeraldas']
        ,['nombre'=> 'Cuenca']
    );
    }
}
