<?php

namespace Database\Seeders\Medico;

use App\Models\Medico\RegionCuerpo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RegionCuerpoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class="Database\Seeders\Medico\RegionCuerpoSeeder"
     *
     * @return void
     */
    public function run()
    {
        RegionCuerpo::insert([
            ['nombre'=>'Piel'],
            ['nombre'=>'Ojos'],
            ['nombre'=>'Oido'],
            ['nombre'=>'Oro faringe'],
            ['nombre'=>'Nariz'],
            ['nombre'=>'Cuello'],
            ['nombre'=>'Torax'],
            ['nombre'=>'Abdomen'],
            ['nombre'=>'Columna'],
            ['nombre'=>'Pelvis'],
            ['nombre'=>'Extremidades'],
            ['nombre'=>'Neurológico'],
    ]);
    }
}
