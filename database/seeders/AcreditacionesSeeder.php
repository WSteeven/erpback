<?php

namespace Database\Seeders;

use App\Models\FondosRotativos\Saldo\Acreditaciones;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AcreditacionesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Acreditaciones::factory(300)->create();
    }
}
