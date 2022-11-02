<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EstadoTareaSubtarea;

class EstadoTareaSubtareaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EstadoTareaSubtarea::insert([
            [
                'descripcion' => 'CREADO',
                ''
            ]
        ]);
    }
}
