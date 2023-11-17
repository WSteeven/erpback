<?php

namespace Database\Seeders;

use App\Models\Ventas\Planes;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlanesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Planes::insert([
            ['nombre'=> '1Play'],
            ['nombre'=> '2Play'],
            ['nombre'=> '3Play'],
        ]);
    }
}
