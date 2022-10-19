<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Grupo;

class GrupoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Grupo::insert([
            [
                'nombre' => 'MACHALA',
                'empleado_id' => 1,
                'estado' => 1,
            ],
            [
                'nombre' => 'GUAYAQUIL',
                'empleado_id' => 2,
                'estado' => 1,
            ],[
                'nombre' => 'SANTO DOMINGO',
                'empleado_id' => 1,
                'estado' => 1,
            ],
            [
                'nombre' => 'BALSAS',
                'empleado_id' => 3,
                'estado' => 1,
            ],
        ]);
    }
}
