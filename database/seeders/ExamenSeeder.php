<?php

namespace Database\Seeders;

use App\Models\Examen;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExamenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Examen::insert([
            [
                'nombre' => 'Grupo sanguineo',
                'categoria_examen_id' => 1,
                'ids_cargos_acceso' => null,
            ],
            [
                'nombre' => 'Biometria hematica completa',
                'categoria_examen_id' => 1,
                'ids_cargos_acceso' => null,
            ],
            [
                'nombre' => 'Elemental y microscopico de orina',
                'categoria_examen_id' => 2,
                'ids_cargos_acceso' => null,
            ],
            [
                'nombre' => 'Coproparasitario + Coprologico',
                'categoria_examen_id' => 3,
                'ids_cargos_acceso' => null,
            ],
            [
                'nombre' => 'Glucosa',
                'categoria_examen_id' => 4,
                'ids_cargos_acceso' => null,
            ],
            [
                'nombre' => 'Creatinina',
                'categoria_examen_id' => 4,
                'ids_cargos_acceso' => null,
            ],
            [
                'nombre' => 'Acido urico',
                'categoria_examen_id' => 4,
                'ids_cargos_acceso' => null,
            ],
            [
                'nombre' => 'Colesterol',
                'categoria_examen_id' => 4,
                'ids_cargos_acceso' => null,
            ],
            [
                'nombre' => 'Trigliceridos',
                'categoria_examen_id' => 4,
                'ids_cargos_acceso' => null,
            ],
            [
                'nombre' => 'Urea',
                'categoria_examen_id' => 4,
                'ids_cargos_acceso' => null,
            ],
            [
                'nombre' => 'T.G.O',
                'categoria_examen_id' => 5,
                'ids_cargos_acceso' => null,
            ],
            [
                'nombre' => 'T.G.P',
                'categoria_examen_id' => 5,
                'ids_cargos_acceso' => null,
            ],
            [
                'nombre' => 'Rx. AP y Lateral de columna lumbar',
                'categoria_examen_id' => 6,
                'ids_cargos_acceso' => null,
            ],
            [
                'nombre' => 'Electroencefalograma',
                'categoria_examen_id' => 6,
                'ids_cargos_acceso' => json_encode('[5]'),
            ],
            [
                'nombre' => 'Electrocardiograma',
                'categoria_examen_id' => 6,
                'ids_cargos_acceso' => json_encode('[5]'),
            ],
            [
                'nombre' => 'Optometria',
                'categoria_examen_id' => 6,
                'ids_cargos_acceso' => json_encode('[5]'),
            ],
            [
                'nombre' => 'Audiometria',
                'categoria_examen_id' => 6,
                'ids_cargos_acceso' => json_encode('[5]'),
            ],
        ]);
    }
}
