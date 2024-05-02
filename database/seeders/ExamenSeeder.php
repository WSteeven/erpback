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
            ],
            [
                'nombre' => 'BIOMETRIA HEMATICA COMPLETA',
            ],
            [
                'nombre' => 'ELEMENTAL Y MICROSCOPIO DE ORINA',
            ],
            [
                'nombre' => 'COPROPARASITARIO + COPROLOGICO',
            ],
            [
                'nombre' => 'GLUCOSA',
            ],
            [
                'nombre' => 'CREATININA',
            ],
            [
                'nombre' => 'ACIDO URICO',
            ],
            [
                'nombre' => 'COLESTEROL',
            ],
            [
                'nombre' => 'TRIGLICERIDOS',
            ],
            [
                'nombre' => 'UREA',
            ],
            [
                'nombre' => 'T.G.O',
            ],
            [
                'nombre' => 'T.G.P',
            ],
            [
                'nombre' => 'Rx. AP y LATERAL DE COLUMNA LUMBAR',
            ],
            [
                'nombre' => 'ELECTROENCEFALOGRAMA',
            ],
            [
                'nombre' => 'ELECTROCARDIOGRAMA',
            ],
            [
                'nombre' => 'OPTOMETRIA',
            ],
            [
                'nombre' => 'AUDIOMETRIA',
            ],
        ]);
    }
}
