<?php

namespace Database\Seeders\RecursosHumanos\Medico;

use App\Models\Medico\OrientacionSexual;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrientacionSexualSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class="Database\Seeders\Medico\OrientacionSexualSeeder"
     * @return void
     */
    public function run()
    {
        OrientacionSexual::insert([
            ['nombre' => 'Lesbiana'],
            ['nombre' => 'Gay'],
            ['nombre' => 'Bisexual'],
            ['nombre' => 'Heterosexual'],
            ['nombre' => 'No sabe'],
        ]);
    }
}
