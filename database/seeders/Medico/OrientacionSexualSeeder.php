<?php

namespace Database\Seeders\Medico;

use App\Models\Medico\OrientacionSexual;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrientacionSexualSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OrientacionSexual::insert([
            ['nombres' => 'Lesbiana'],
            ['nombres' => 'Gay'],
            ['nombres' => 'Bisexual'],
            ['nombres' => 'Heterosexual'],
            ['nombres' => 'No sabe'],
        ]);
    }
}
