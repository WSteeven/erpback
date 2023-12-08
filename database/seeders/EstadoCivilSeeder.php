<?php

namespace Database\Seeders;

use App\Models\EstadoCivil;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EstadoCivilSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       EstadoCivil::insert([['nombre'=> 'Soltero'],
       ['nombre'=> 'Casado'],
       ['nombre'=> 'Divorciado'],
       ['nombre'=> 'Viudo'],
       ['nombre'=> 'Union Libre']]);
    }
}
