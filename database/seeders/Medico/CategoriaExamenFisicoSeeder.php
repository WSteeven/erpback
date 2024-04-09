<?php

namespace Database\Seeders\Medico;

use App\Models\Medico\CategoriaExamenFisico;
use App\Models\Medico\RegionCuerpo;
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
                'region_cuerpo_id' => RegionCuerpo::PIEL
            ],
            [
                'nombre' => 'Tatuajes',
                'region_cuerpo_id' => RegionCuerpo::PIEL
            ],
            [
                'nombre' => 'Piel y Faneras',
                'region_cuerpo_id' => RegionCuerpo::PIEL
            ],
            [
                'nombre' => 'Parpados',
                'region_cuerpo_id' => RegionCuerpo::OJOS
            ],
            [
                'nombre' => 'Conjuntivas',
                'region_cuerpo_id' => RegionCuerpo::OJOS
            ],
            [
                'nombre' => 'Pupilas',
                'region_cuerpo_id' => RegionCuerpo::OJOS
            ],
            [
                'nombre' => 'Cornea',
                'region_cuerpo_id' => RegionCuerpo::OJOS
            ],
            [
                'nombre' => 'Motilidad',
                'region_cuerpo_id' => RegionCuerpo::OJOS
            ],
            [
                'nombre' => 'Motilidad',
                'region_cuerpo_id' => RegionCuerpo::OJOS
            ],
            [
                'nombre' => 'Auditivo externo',
                'region_cuerpo_id' => RegionCuerpo::OIDO
            ],
            [
                'nombre' => 'Pabellón',
                'region_cuerpo_id' => RegionCuerpo::OIDO
            ],
            [
                'nombre' => 'Tímpanos',
                'region_cuerpo_id' => RegionCuerpo::OIDO
            ],
            [
                'nombre' => 'Labios',
                'region_cuerpo_id' => RegionCuerpo::OROFARINGUE
            ],
            [
                'nombre' => 'Lengua',
                'region_cuerpo_id' => RegionCuerpo::OROFARINGUE
            ],
            [
                'nombre' => 'Faringue',
                'region_cuerpo_id' => RegionCuerpo::OROFARINGUE
            ],
            [
                'nombre' => 'Amígdalas',
                'region_cuerpo_id' => RegionCuerpo::OROFARINGUE
            ],
            [
                'nombre' => 'Dentadura',
                'region_cuerpo_id' => RegionCuerpo::OROFARINGUE
            ],
            [
                'nombre' => 'Tabique',
                'region_cuerpo_id' => RegionCuerpo::NARIZ
            ],
            [
                'nombre' => 'Cornetes',
                'region_cuerpo_id' => RegionCuerpo::NARIZ
            ],
            [
                'nombre' => 'Mucosas',
                'region_cuerpo_id' => RegionCuerpo::NARIZ
            ],
            [
                'nombre' => 'Senos paranasales',
                'region_cuerpo_id' => RegionCuerpo::NARIZ
            ],
            [
                'nombre' => 'Tiroides masas',
                'region_cuerpo_id' => RegionCuerpo::CUELLO
            ],
            [
                'nombre' => 'Movilidad',
                'region_cuerpo_id' => RegionCuerpo::CUELLO
            ],
            [
                'nombre' => 'Mamás',
                'region_cuerpo_id' => RegionCuerpo::TORAX
            ],
            [
                'nombre' => 'Corazón',
                'region_cuerpo_id' => RegionCuerpo::TORAX
            ],
            [
                'nombre' => 'Pulmones',
                'region_cuerpo_id' => RegionCuerpo::TORAX
            ],
            [
                'nombre' => 'Parrilla costal',
                'region_cuerpo_id' => RegionCuerpo::TORAX
            ],
            [
                'nombre' => 'Vísceras',
                'region_cuerpo_id' => RegionCuerpo::ABDOMEN
            ],
            [
                'nombre' => 'Pared abdominal',
                'region_cuerpo_id' => RegionCuerpo::ABDOMEN
            ],
            [
                'nombre' => 'Flexibilidad',
                'region_cuerpo_id' => RegionCuerpo::COLUMNA
            ],
            [
                'nombre' => 'Desviación',
                'region_cuerpo_id' => RegionCuerpo::COLUMNA
            ],
            [
                'nombre' => 'Dolor',
                'region_cuerpo_id' => RegionCuerpo::COLUMNA
            ],
            [
                'nombre' => 'Pelvis',
                'region_cuerpo_id' => RegionCuerpo::PELVIS
            ],
            [
                'nombre' => 'Genitales',
                'region_cuerpo_id' => RegionCuerpo::PELVIS
            ],
            [
                'nombre' => 'Vascular',
                'region_cuerpo_id' => RegionCuerpo::EXTREMIDADES
            ],
            [
                'nombre' => 'Miembros superiores',
                'region_cuerpo_id' => RegionCuerpo::EXTREMIDADES
            ],
            [
                'nombre' => 'Miembros inferiores',
                'region_cuerpo_id' => RegionCuerpo::EXTREMIDADES
            ],
            [
                'nombre' => 'Fuerza',
                'region_cuerpo_id' => RegionCuerpo::NEUROLOGICO
            ],
            [
                'nombre' => 'Sensibilidad',
                'region_cuerpo_id' => RegionCuerpo::NEUROLOGICO
            ],
            [
                'nombre' => 'Marcha',
                'region_cuerpo_id' => RegionCuerpo::NEUROLOGICO
            ],
            [
                'nombre' => 'Reflejos',
                'region_cuerpo_id' => RegionCuerpo::NEUROLOGICO
            ],
        ]);
    }
}
