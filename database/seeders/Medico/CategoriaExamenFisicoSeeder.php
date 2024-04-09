<?php

namespace Database\Seeders\Medico;

use App\Models\Medico\CategoriaExamenFisico;
use Illuminate\Database\Seeder;

class CategoriaExamenFisicoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CategoriaExamenFisico::insert([
            [
                'nombre' => 'Cicatrices',
                'region' => CategoriaExamenFisico::PIEL
            ],
            [
                'nombre' => 'Tatuajes',
                'region' => CategoriaExamenFisico::PIEL
            ],
            [
                'nombre' => 'Piel y Faneras',
                'region' => CategoriaExamenFisico::PIEL
            ],
            [
                'nombre' => 'Parpados',
                'region' => CategoriaExamenFisico::OJOS
            ],
            [
                'nombre' => 'Conjuntivas',
                'region' => CategoriaExamenFisico::OJOS
            ],
            [
                'nombre' => 'Pupilas',
                'region' => CategoriaExamenFisico::OJOS
            ],
            [
                'nombre' => 'Cornea',
                'region' => CategoriaExamenFisico::OJOS
            ],
            [
                'nombre' => 'Motilidad',
                'region' => CategoriaExamenFisico::OJOS
            ],
            [
                'nombre' => 'Motilidad',
                'region' => CategoriaExamenFisico::OJOS
            ],
            [
                'nombre' => 'Auditivo externo',
                'region' => CategoriaExamenFisico::OIDO
            ],
            [
                'nombre' => 'Pabellón',
                'region' => CategoriaExamenFisico::OIDO
            ],
            [
                'nombre' => 'Tímpanos',
                'region' => CategoriaExamenFisico::OIDO
            ],
            [
                'nombre' => 'Labios',
                'region' => CategoriaExamenFisico::OROFARINGUE
            ],
            [
                'nombre' => 'Lengua',
                'region' => CategoriaExamenFisico::OROFARINGUE
            ],
            [
                'nombre' => 'Faringue',
                'region' => CategoriaExamenFisico::OROFARINGUE
            ],
            [
                'nombre' => 'Amígdalas',
                'region' => CategoriaExamenFisico::OROFARINGUE
            ],
            [
                'nombre' => 'Dentadura',
                'region' => CategoriaExamenFisico::OROFARINGUE
            ],
            [
                'nombre' => 'Tabique',
                'region' => CategoriaExamenFisico::NARIZ
            ],
            [
                'nombre' => 'Cornetes',
                'region' => CategoriaExamenFisico::NARIZ
            ],
            [
                'nombre' => 'Mucosas',
                'region' => CategoriaExamenFisico::NARIZ
            ],
            [
                'nombre' => 'Senos paranasales',
                'region' => CategoriaExamenFisico::NARIZ
            ],
            [
                'nombre' => 'Tiroides masas',
                'region' => CategoriaExamenFisico::CUELLO
            ],
            [
                'nombre' => 'Movilidad',
                'region' => CategoriaExamenFisico::CUELLO
            ],
            [
                'nombre' => 'Mamás',
                'region' => CategoriaExamenFisico::TORAX
            ],
            [
                'nombre' => 'Corazón',
                'region' => CategoriaExamenFisico::TORAX
            ],
            [
                'nombre' => 'Pulmones',
                'region' => CategoriaExamenFisico::TORAX
            ],
            [
                'nombre' => 'Parrilla costal',
                'region' => CategoriaExamenFisico::TORAX
            ],
            [
                'nombre' => 'Vísceras',
                'region' => CategoriaExamenFisico::ABDOMEN
            ],
            [
                'nombre' => 'Pared abdominal',
                'region' => CategoriaExamenFisico::ABDOMEN
            ],
            [
                'nombre' => 'Flexibilidad',
                'region' => CategoriaExamenFisico::COLUMNA
            ],
            [
                'nombre' => 'Desviación',
                'region' => CategoriaExamenFisico::COLUMNA
            ],
            [
                'nombre' => 'Dolor',
                'region' => CategoriaExamenFisico::COLUMNA
            ],
            [
                'nombre' => 'Pelvis',
                'region' => CategoriaExamenFisico::PELVIS
            ],
            [
                'nombre' => 'Genitales',
                'region' => CategoriaExamenFisico::PELVIS
            ],
            [
                'nombre' => 'Vascular',
                'region' => CategoriaExamenFisico::EXTREMIDADES
            ],
            [
                'nombre' => 'Miembros superiores',
                'region' => CategoriaExamenFisico::EXTREMIDADES
            ],
            [
                'nombre' => 'Miembros inferiores',
                'region' => CategoriaExamenFisico::EXTREMIDADES
            ],
            [
                'nombre' => 'Fuerza',
                'region' => CategoriaExamenFisico::NEUROLOGICO
            ],
            [
                'nombre' => 'Sensibilidad',
                'region' => CategoriaExamenFisico::NEUROLOGICO
            ],
            [
                'nombre' => 'Marcha',
                'region' => CategoriaExamenFisico::NEUROLOGICO
            ],
            [
                'nombre' => 'Reflejos',
                'region' => CategoriaExamenFisico::NEUROLOGICO
            ],
        ]);
    }
}
