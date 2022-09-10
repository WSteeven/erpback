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
        Categoria::create(['nombre'=>'UTILITARIOS']);
        Categoria::create(['nombre'=>'UNIFORMES']);
        Categoria::create(['nombre'=>'EPP']);
        Categoria::create(['nombre'=>'INFORMATICA']);
        Categoria::create(['nombre'=>'HERRAMIENTAS']);
        Categoria::create(['nombre'=>'MATERIALES']);
        Categoria::create(['nombre'=>'EQUIPOS']);
    }
}
