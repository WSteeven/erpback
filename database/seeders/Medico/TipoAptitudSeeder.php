<?php

namespace Database\Seeders\Medico;

use App\Models\Medico\TipoAptitud;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoAptitudSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TipoAptitud::insert([
            [
                'nombre' => 'Apto',
            ],
            [
                'nombre' => 'Apto en observación',
            ],
            [
                'nombre' => 'Apto con limitaciones',
            ],
            [
                'nombre' => 'No apto',
            ],
        ]);
    }
}
