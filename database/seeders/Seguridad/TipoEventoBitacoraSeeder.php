<?php

namespace Database\Seeders\Seguridad;

use App\Models\Seguridad\TipoEventoBitacora;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoEventoBitacoraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TipoEventoBitacora::insert([
            ['nombre' => 'RONDA', 'descripcion' => ''],
            ['nombre' => '', 'descripcion' => ''],
            ['nombre' => '', 'descripcion' => ''],
            ['nombre' => '', 'descripcion' => ''],
            ['nombre' => '', 'descripcion' => ''],
            ['nombre' => '', 'descripcion' => ''],
            ['nombre' => '', 'descripcion' => ''],
            ['nombre' => '', 'descripcion' => ''],
            ['nombre' => '', 'descripcion' => ''],
            ['nombre' => '', 'descripcion' => ''], 
        ]);
    }
}
