<?php

namespace Database\Seeders\Medico;

use App\Models\Medico\CategoriaExamen;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriaExamenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class="Database\Seeders\Medico\CategoriaExamenSeeder"
     * @return void
     */
    public function run()
    {
        CategoriaExamen::insert([
            [
                'id' => 1,
                'nombre' => 'HEMATOLOGIA',
            ],
            [
                'id' => 2,
                'nombre' => 'UROANALISIS',
            ],
            [
                'id' => 3,
                'nombre' => 'HECES',
            ],
            [
                'id' => 4,
                'nombre' => 'QUIMICA SANGUINEA',
            ],
            [
                'id' => 5,
                'nombre' => 'ENZIMAS HEPATICAS',
            ],
            [
                'id' => 6,
                'nombre' => 'IMAGENOLOGIA',
            ],
            [
                'id' => 7,
                'nombre' => 'ORL',
            ],
            [
                'id' => 8,
                'nombre' => 'OFTALMOLOGÍA',
            ],
            [
                'id' => 9,
                'nombre' => 'CARDIOLOGÍA',
            ],
            [
                'id' => 10,
                'nombre' => 'NEUROLOGÍA',
            ],
        ]);
    }
}
