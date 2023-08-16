<?php

namespace Database\Seeders;

use App\Models\RecursosHumanos\Area;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AreasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Area::insert([
            ['nombre' => 'Administrativa'],
            ['nombre' => 'Tecnica']
        ]);
    }
}
