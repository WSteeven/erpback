<?php

namespace Database\Seeders\Medico;

use App\Models\Medico\SistemaOrganico;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrganosSistemasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SistemaOrganico::insert([
            [
                'nombre' => 'Piel anexos',
            ],
            [
                'nombre' => 'Respiratorio',
            ],
            [
                'nombre' => 'Digestivo',
            ],
            [
                'nombre' => 'Músculo esquelético',
            ],
            [
                'nombre' => 'Emo linfático',
            ],
            [
                'nombre' => 'Órganos de los sentidos',
            ],
            [
                'nombre' => 'Cardiovascular',
            ],
            [
                'nombre' => 'Genito urinario',
            ],
            [
                'nombre' => 'Endocrino',
            ],
            [
                'nombre' => 'Nervioso',
            ],
        ]);
    }
}
