<?php

namespace Database\Seeders;

use App\Models\Hilo;
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
        Hilo::create(['nombre'=>48]);
        Hilo::create(['nombre'=>02]);
    }
}
