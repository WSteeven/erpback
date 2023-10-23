<?php

namespace Database\Seeders;

use App\Models\CategoriaExamen;
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
                'nombre' => 'Hematología',
            ],
            [
                'nombre' => 'Uroanalisis',
            ],
            [
                'nombre' => 'Heces',
            ],
            [
                'nombre' => 'Quimica sanguinea',
            ],
            [
                'nombre' => 'Enzimas hepaticas',
            ],
            [
                'nombre' => 'Imagenologia',
            ],
        ]);
    }
}
