<?php

namespace Database\Seeders;

use App\Models\Medico\ConfiguracionExamenCampo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConfiguracionExamenCampoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ConfiguracionExamenCampo::insert([
        [
        'campo' => 'LEUCOCITOS',
        'unidad_medida'=> '103/UL',
        'intervalo_referencia' => '',
        'configuracion_examen_categoria_id' =>'1',
         ]

            
            
        ]);
    }
}
 