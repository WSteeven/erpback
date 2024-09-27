<?php

namespace Database\Seeders\RecursosHumanos\Medico;

use App\Models\Medico\DetalleExamen;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DetalleExamenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class="Database\Seeders\Medico\DetalleExamenSeeder"
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'tipo_examen_id' => 1,
                'categoria_examen_id' => 1, //hematologia_grupoSan//
                'examen_id' => 1,
            ],
            [
                'tipo_examen_id' => 1,
                'categoria_examen_id' => 1, //hematologia_biometria//
                'examen_id' => 2,
            ],
            [
                'tipo_examen_id' => 1,
                'categoria_examen_id' => 2, //urologia//
                'examen_id' => 3,
            ],
            [
                'tipo_examen_id' => 1,
                'categoria_examen_id' => 3, //heces//
                'examen_id' => 4,
            ],
            [
                'tipo_examen_id' => 1,
                'categoria_examen_id' => 4, //quimica sanguinea
                'examen_id' => 5,
            ],
            [
                'tipo_examen_id' => 1,
                'categoria_examen_id' => 4, //quimica sanguinea
                'examen_id' => 6,
            ],
            [
                'tipo_examen_id' => 1,
                'categoria_examen_id' => 4, //quimica sanguinea
                'examen_id' => 7,
            ],
            [
                'tipo_examen_id' => 1,
                'categoria_examen_id' => 4, //quimica sanguinea
                'examen_id' => 8,
            ],
            [
                'tipo_examen_id' => 1,
                'categoria_examen_id' => 4, //quimica sanguinea
                'examen_id' => 9,
            ],
            [
                'tipo_examen_id' => 1,
                'categoria_examen_id' => 4, //quimica sanguinea
                'examen_id' => 10,
            ],
            [
                'tipo_examen_id' => 1,
                'categoria_examen_id' => 5, //quimica sanguines
                'examen_id' => 11,
            ],
            [
                'tipo_examen_id' => 1,
                'categoria_examen_id' => 5, //enzimas
                'examen_id' => 12,
            ],
            [
                'tipo_examen_id' => 1,
                'categoria_examen_id' => 6, // IMAGENOLOGIA
                'examen_id' => 13, // Rx. AP y LATERAL DE COLUMNA LUMBAR
            ],
            [
                'tipo_examen_id' => 2,
                'categoria_examen_id' => 7, // ORL
                'examen_id' => 17,          // AUDIOOMETRIA
            ],
            [
                'tipo_examen_id' => 2,
                'categoria_examen_id' => 8, // OFTALMOLOGÍA
                'examen_id' => 16,          // OPTOMETRIA
            ],
            [
                'tipo_examen_id' => 2,
                'categoria_examen_id' => 9, // CARDIOLOGÍA
                'examen_id' => 15,
            ],
            [
                'tipo_examen_id' => 2,
                'categoria_examen_id' => 10, // NEUROLOGÍA
                'examen_id' => 14,           // ELECTROENCEFALOGRAMA
            ],
        ];

        foreach ($data as $detalle) {
            DetalleExamen::create($detalle);
        }
    }
}
