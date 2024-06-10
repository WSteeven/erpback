<?php

namespace Database\Seeders;

use App\Models\Ventas\Plan;
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
        Plan::insert([
            ['nombre'=> '1Play'],
            ['nombre'=> '2Play'],
            ['nombre'=> '3Play'],
        ]);
    }
}
