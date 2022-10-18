<?php

namespace Database\Seeders;

use App\Models\Categoria;
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
        /*
        Categorias
         */
        Categoria::create(['nombre'=>'ACCESORIOS']);
        Categoria::create(['nombre'=>'EPP']);
        Categoria::create(['nombre'=>'EQUIPOS PROPIOS']);
        Categoria::create(['nombre'=>'EQUIPOS']);
        Categoria::create(['nombre'=>'HERRAMIENTAS']);
        Categoria::create(['nombre'=>'INFORMATICA']);
        Categoria::create(['nombre'=>'MATERIALES']);
        Categoria::create(['nombre'=>'SUMINISTROS']);
    }
}
