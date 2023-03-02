<?php

namespace Database\Seeders;

use App\Models\FondosRotativos\Gasto\Gasto;
use Database\Factories\FondosRotativos\GastosFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GastosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       Gasto::factory(300)->create();
    }
}
