<?php

namespace Database\Seeders\Medico;

use App\Models\Medico\CategoriaExamen;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriaExamenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CategoriaExamen::insert([
            [
                'nombre' => 'HEMATOLOGIA',
            ],
            [
                'nombre' => 'UROANALISIS',
            ],
            [
                'nombre' => 'HECES',
            ],
            [
                'nombre' => 'QUIMICA SANGUINEA',
            ],
            [
                'nombre' => 'ENZIMAS HEPATICAS',
            ],
            [
                'nombre' => 'IMAGENOLOGIA',
            ],
        ]);
    }
}
