<?php

namespace Database\Seeders;

use App\Models\FondosRotativos\Gasto\MotivoGasto;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MotivoGastoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MotivoGasto::insert ([
            [
                'nombre' => 'Alimentacion',
            ],
            [
                'nombre' => 'Hospedaje',
            ],
            [
                'nombre' => 'Mantenimiento de Vehiculos',
            ],
            [
                'nombre' => 'Mantenimiento de equipos',
            ],
            [
                'nombre' => 'Transporte',
            ],
            [
                'nombre' => 'Contratacion Eventual',
            ],
            [
                'nombre' => 'Alquileres Eventuales',
            ],
        ]);

    }
}
