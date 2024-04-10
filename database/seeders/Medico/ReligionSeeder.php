<?php

namespace Database\Seeders\Medico;

use App\Models\Medico\Religion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReligionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Religion::insert([
            ['nombre' => 'Católica'],
            ['nombre' => 'Evangélica'],
            ['nombre' => 'Testigos de Jehová'],
            ['nombre' => 'Mormona'],
            ['nombre' => 'Otros'],
        ]);
    }
}
