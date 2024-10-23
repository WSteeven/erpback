<?php

namespace Database\Seeders\Medico;

use App\Models\Medico\SistemaOrganico;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrganosSistemasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class="Database\Seeders\Medico\OrganosSistemasSeeder"
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
                'nombre' => 'Órganos de los sentidos',
            ],
            [
                'nombre' => 'Respiratorio',
            ],
            [
                'nombre' => 'Cardiovascular',
            ],
            [
                'nombre' => 'Digestivo',
            ],
            [
                'nombre' => 'Genito urinario',
            ],
            [
                'nombre' => 'Músculo esquelético',
            ],
            [
                'nombre' => 'Endocrino',
            ],
            [
                'nombre' => 'Hemolinfático',
            ],
            [
                'nombre' => 'Nervioso',
            ],
        ]);
    }
}
