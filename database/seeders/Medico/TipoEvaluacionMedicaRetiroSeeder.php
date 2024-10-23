<?php

namespace Database\Seeders\Medico;

use App\Models\Medico\TipoEvaluacionMedicaRetiro;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoEvaluacionMedicaRetiroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class="Database\Seeders\Medico\TipoEvaluacionMedicaRetiroSeeder"
     * @return void
     */
    public function run()
    {
        TipoEvaluacionMedicaRetiro::insert([
            [
                'nombre' => 'El usuario se realizó la evaluación médica de retiro.',
            ],
            [
                'nombre' => 'Condición del diagnóstico.',
            ],
            [
                'nombre' => 'La condición de salud está relacionada con el trabajo.',
            ],
        ]);
    }
}
