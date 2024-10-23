<?php

namespace Database\Seeders\Intranet;

use App\Models\Intranet\CategoriaNoticia;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CategoriaNoticia::insert([
            ['nombre'=>'Vacante'],
            ['nombre'=>'Capacitacion'],
            ['nombre'=>'Feriados'],
            ['nombre'=>'Nota luctuosa'],
            ['nombre'=>'Seguridad'],
            ['nombre'=>'Médico'],
            ['nombre'=>'Politica'],
            ['nombre'=>'Ente regulador'],
        ]);
    }
}
