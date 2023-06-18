<?php

namespace Database\Seeders;

use App\Models\RecursosHumanos\NominaPrestamos\HorasExtraSubTipo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HorasExtrasSubTipoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        HorasExtraSubTipo::insert([
            ['nombre' => 'Diurno','hora_extra_id'=> 1],
            ['nombre' => 'Nocturno','hora_extra_id'=> 1],
        ]);
    }
}
