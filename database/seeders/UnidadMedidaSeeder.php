<?php

namespace Database\Seeders;

use App\Models\UnidadMedida;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnidadMedidaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UnidadMedida::create(['nombre'=>'UNIDAD', 'simbolo'=>'U']);  
        UnidadMedida::create(['nombre'=>'KILOGRAMO', 'simbolo'=>'KG']);
        UnidadMedida::create(['nombre'=>'METRO', 'simbolo'=>'M']);  
        UnidadMedida::create(['nombre'=>'METRO LINEAL', 'simbolo'=>'ML']);  
        UnidadMedida::create(['nombre'=>'KILOMETRO', 'simbolo'=>'KM']);  
        UnidadMedida::create(['nombre'=>'KILOMETRO CUBICO', 'simbolo'=>'KC']);  
    }
}
