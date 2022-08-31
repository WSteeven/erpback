<?php

namespace Database\Seeders;

use App\Models\TipoTarea;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoTareaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TipoTarea::insert([
            [
                'nombre' => 'TENDIDO',
                'cliente_id' => 1
            ],
            [
                'nombre' => 'DESMONTAJE',
                'cliente_id' => 1
            ],
            [
                'nombre' => 'HINCADO',
                'cliente_id' => 1
            ],
            [
                'nombre' => 'RETIRO',
                'cliente_id' => 1
            ],
        ]);
    }
}
