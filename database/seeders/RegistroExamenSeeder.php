<?php

namespace Database\Seeders;

use App\Models\RegistroExamen;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RegistroExamenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RegistroExamen::insert([
            [
                'numero_registro' => 1,
                'observacion' => 'Primer ingreso',
                'tipo_examen_id' => 1,
                'empleado_id' => 25,
            ],
        ]);
    }
}
