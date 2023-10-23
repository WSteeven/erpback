<?php

namespace Database\Seeders;

use App\Models\TipoExamen;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoExamenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TipoExamen::insert([
            [
                'nombre' => 'Ingreso',
            ],
            [
                'nombre' => 'Ocupacionales',
            ],
            [
                'nombre' => 'Reingreso',
            ],
            [
                'nombre' => 'Salida',
            ],
        ]);
    }
}
