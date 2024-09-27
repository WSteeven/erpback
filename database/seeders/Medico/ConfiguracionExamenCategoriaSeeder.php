<?php

namespace Database\Seeders\RecursosHumanos\Medico;

use App\Models\Medico\ConfiguracionExamenCategoria;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConfiguracionExamenCategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class="Database\Seeders\Medico\ConfiguracionExamenCategoriaSeeder"
     *
     * @return void
     */
    public function run()
    {
        ConfiguracionExamenCategoria::insert([
            // Grupo sanguineo
            [
                'nombre' => 'GRUPO SANGUINEO',
                'examen_id' => 1,
            ],
            [
                'nombre' => 'BIOMETRIA',
                'examen_id' => 2, // Biometria hematica completa
            ],
            // Elemental y microscopico de orina
            [
                'nombre' => 'FISICO',
                'examen_id' => 3,
            ],
            [
                'nombre' => 'QUIMICO',
                'examen_id' => 3,
            ],
            [
                'nombre' => 'MICROSCOPICO',
                'examen_id' => 3,
            ],
            // Coproparasitario + coprologico
            [
                'nombre' => 'FISICO',
                'examen_id' => 4,
            ],
            // Bioquimica sanguinea
            [
                'nombre' => 'BIOQUIMICA SANGUINEA (GLUCOSA)',
                'examen_id' => 5,
            ],
            [
                'nombre' => 'BIOQUIMICA SANGUINEA (COLESTEROL)',
                'examen_id' => 8,
            ],
            [
                'nombre' => 'BIOQUIMICA SANGUINEA (TRIGLICÉRIDOS)',
                'examen_id' => 9,
            ],
            [
                'nombre' => 'BIOQUIMICA SANGUINEA (ÚREA)',
                'examen_id' => 10,
            ],
            [//10
                'nombre' => 'BIOQUIMICA SANGUINEA (CREATININA)',
                'examen_id' => 6,
            ],
            [
                'nombre' => 'BIOQUIMICA SANGUINEA (ÁCIDO ÚRICO)',
                'examen_id' => 7,
            ],
            // Enzimas
            [
                'nombre' => 'ENZIMAS (TGO)',
                'examen_id' => 11,
            ],
            [
                'nombre' => 'ENZIMAS (TGP)',
                'examen_id' => 12,
            ],
            // Especiales
            [
                'nombre' => 'EXÁMENES ESPECIALES (Rx. AP y LATERAL DE COLUMNA LUMBAR)',
                'examen_id' => 13,
            ],
            [
                'nombre' => 'EXÁMENES ESPECIALES (ELECTROENCEFALOGRAMA)',
                'examen_id' => 14,
            ],
            [
                'nombre' => 'EXÁMENES ESPECIALES (ELECTROCARDIOGRAMA)',
                'examen_id' => 15,
            ],
            [
                'nombre' => 'EXÁMENES ESPECIALES (OPTOMETRIA)',
                'examen_id' => 16,
            ],
            [
                'nombre' => 'EXÁMENES ESPECIALES (AUDIOMETRIA)',
                'examen_id' => 17,
            ],
        ]);
    }
}
