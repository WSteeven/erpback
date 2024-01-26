<?php

namespace Database\Seeders;

// use App\Models\Examen;

use App\Models\Medico\Examen;
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
                'nombre' => 'GRUPO SANGUINEO',
                // 'categoria_examen_id' => 1,
                // 'ids_cargos_acceso' => null,
            ],
            [
                'nombre' => 'BIOMETRIA HEMATICA COMPLETA',
                // 'categoria_examen_id' => 1,
                // 'ids_cargos_acceso' => null,
            ],
            [
                'nombre' => 'ELEMENTAL Y MICROSCOPIO DE ORINA',
                // 'categoria_examen_id' => 2,
                // 'ids_cargos_acceso' => null,
            ],
            [
                'nombre' => 'COPROPARASITARIO + COPROLOGICO',
                // 'categoria_examen_id' => 3,
                // 'ids_cargos_acceso' => null,
            ],
            [
                'nombre' => 'GLUCOSA',
                // 'categoria_examen_id' => 4,
                // 'ids_cargos_acceso' => null,
            ],
            [
                'nombre' => 'CREATININA',
                // 'categoria_examen_id' => 4,
                // 'ids_cargos_acceso' => null,
            ],
            [
                'nombre' => 'ACIDO URICO',
                // 'categoria_examen_id' => 4,
                // 'ids_cargos_acceso' => null,
            ],
            [
                'nombre' => 'COLESTEROL',
                // 'categoria_examen_id' => 4,
                // 'ids_cargos_acceso' => null,
            ],
            [
                'nombre' => 'TRIGLICERIDOS',
                // 'categoria_examen_id' => 4,
                // 'ids_cargos_acceso' => null,
            ],
            [
                'nombre' => 'UREA',
                // 'categoria_examen_id' => 4,
                // 'ids_cargos_acceso' => null,
            ],
            [
                'nombre' => 'T.G.O',
                // 'categoria_examen_id' => 5,
                // 'ids_cargos_acceso' => null,
            ],
            [
                'nombre' => 'T.G.P',
                // 'categoria_examen_id' => 5,
                // 'ids_cargos_acceso' => null,
            ],
            [
                'nombre' => 'Rx. AP y LATERAL DE COLUMNA LUMBAR',
                // 'categoria_examen_id' => 6,
                // 'ids_cargos_acceso' => null,
            ],
            [
                'nombre' => 'ELECTROENCEFALOGRAMA',
                // 'categoria_examen_id' => 6,
                // 'ids_cargos_acceso' => json_encode('[5]'),
            ],
            [
                'nombre' => 'ELECTROCARDIOGRAMA',
                // 'categoria_examen_id' => 6,
                // 'ids_cargos_acceso' => json_encode('[5]'),
            ],
            [
                'nombre' => 'OPTOMETRIA',
                // 'categoria_examen_id' => 6,
                // 'ids_cargos_acceso' => json_encode('[5]'),
            ],
            [
                'nombre' => 'AUDIOMETRIA',
                // 'categoria_examen_id' => 6,
                // 'ids_cargos_acceso' => json_encode('[5]'),
            ],
        ]);
    }
}
