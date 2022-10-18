<?php

namespace Database\Seeders;

use App\Models\Ram;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Ram::create(['nombre'=>'2GB']);
        Ram::create(['nombre'=>'3GB']);
        Ram::create(['nombre'=>'4GB']);
        Ram::create(['nombre'=>'8GB']);
        Ram::create(['nombre'=>'16GB']);
    }
}
