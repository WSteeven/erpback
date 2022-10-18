<?php

namespace Database\Seeders;

use App\Models\Disco;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DiscoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Disco::create(['nombre'=>'32 GB']);
        Disco::create(['nombre'=>'224 GB SSD']);
        Disco::create(['nombre'=>'240 GB HDD']);
        Disco::create(['nombre'=>'500 GB HDD']);
        Disco::create(['nombre'=>'540 GB SSD']);
        Disco::create(['nombre'=>'1 TB HDD']);
        Disco::create(['nombre'=>'2 TB HDD']);
    }
}
