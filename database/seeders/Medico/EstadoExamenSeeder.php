<?php

namespace Database\Seeders\Medico;

use App\Models\EstadoExamen;
use App\Models\Medico\EstadoExamen as MedicoEstadoExamen;
// use App\Models\Medico\EstadoExamen as MedicoEstadoExamen;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EstadoExamenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MedicoEstadoExamen::insert([
            [
                'nombre' => 'SOLICITADO',
            ],
            [
                'nombre' => 'APROBADO POR COMPRAS',
            ],
            [
                'nombre' => 'DIAGNÓSTICO REALIZADO',
            ],
            [
                'nombre' => 'APERTURA DE FICHA MEDICA',
            ],
        ]);
    }
}
