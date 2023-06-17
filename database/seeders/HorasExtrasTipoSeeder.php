<?php

namespace Database\Seeders;

use App\Models\RecursosHumanos\NominaPrestamos\HorasExtraTipo ;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HorasExtrasTipoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        HorasExtraTipo::insert([
            ['nombre' => 'Suplementarias'],
            ['nombre' => 'Extraordinarias'],
        ]);
    }
}
