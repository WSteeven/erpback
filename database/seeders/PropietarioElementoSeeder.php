<?php

namespace Database\Seeders;

use App\Models\PropietarioElemento;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PropietarioElementoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PropietarioElemento::insert([
            ['descripcion' => 'CNEL'],
            ['descripcion' => 'CNT'],
            ['descripcion' => 'CONCEL'],
            ['descripcion' => 'OTECEL'],
            ['descripcion' => 'TELCONET'],
            ['descripcion' => 'NEDETEL'],
            ['descripcion' => 'SETEL'],
            ['descripcion' => 'PRIVADO'],
            ['descripcion' => 'EERRS'],
            ['descripcion' => 'CENTRO SUR'],
        ]);
    }
}
