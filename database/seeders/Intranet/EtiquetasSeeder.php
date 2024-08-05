<?php

namespace Database\Seeders\Intranet;

use App\Models\Intranet\Etiqueta;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EtiquetasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Etiqueta::insert([
            ['categoria_id'=>1,'nombre'=>'Promocion Interna'],
            ['categoria_id'=>2,'nombre'=>'Interna'],
            ['categoria_id'=>2,'nombre'=>'Externa'],
            ['categoria_id'=>3,'nombre'=>'Nacional'],
            ['categoria_id'=>3,'nombre'=>'Local'],
            ['categoria_id'=>5,'nombre'=>'Normativa'],
            ['categoria_id'=>5,'nombre'=>'Advertencia'],
            ['categoria_id'=>5,'nombre'=>'Solicitud'],
            ['categoria_id'=>6,'nombre'=>'Campaña'],
            ['categoria_id'=>6,'nombre'=>'Vacunación'],
            ['categoria_id'=>6,'nombre'=>'Exámenes Médicos'],
            ['categoria_id'=>7,'nombre'=>'Interna'],
            ['categoria_id'=>7,'nombre'=>'Externa'],
            ['categoria_id'=>8,'nombre'=>'Avisos'],
            ['categoria_id'=>8,'nombre'=>'Aporte Personal'],
        ]);
    }
}
