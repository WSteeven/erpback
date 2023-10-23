<?php

namespace Database\Seeders;

use App\Models\EsquemaVacuna;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EsquemaVacunaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EsquemaVacuna::insert([
            [
                'dosis_aplicadas' => 3,
                'observacion' => 'Covid...',
                'url_certificado' => 'http://backend_jpconstrucred.test',
                'tipo_vacuna_id' => 1,
                'registro_examen_id' => 1,
            ],
            [
                'dosis_aplicadas' => 1,
                'observacion' => 'Fiebre amarilla...',
                'url_certificado' => 'http://backend_jpconstrucred.test',
                'tipo_vacuna_id' => 2,
                'registro_examen_id' => 1,
            ],
        ]);
    }
}
