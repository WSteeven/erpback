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
        'rango_superior' => 4.50,
        'rango_inferior' => 10.00,
        'configuracion_examen_categoria_id' =>'1',
        ],
        [
            'campo' => 'HEMATROCRITO',
            'unidad_medida'=> '103/UL',
            'rango_superior' => 4.50,
            'rango_inferior' => 10.00,
            'configuracion_examen_categoria_id' =>'1',
        ],
        [
            'campo' => 'HEMOGLOBINA',
            'unidad_medida'=> '103/UL',
            'rango_superior' => 4.50,
            'rango_inferior' => 10.00,
            'configuracion_examen_categoria_id' =>'1',
        ],
        [
            'campo' => 'PLAQUETAS',
            'unidad_medida'=> '103/UL',
            'rango_superior' => 4.50,
            'rango_inferior' => 10.00,
            'configuracion_examen_categoria_id' =>'1',
        ],
        [
            'campo' => 'LINFOCITOS %',
            'unidad_medida'=> '103/UL',
            'rango_superior' => 4.50,
            'rango_inferior' => 10.00,
            'configuracion_examen_categoria_id' =>'1',
        ],
        [
            'campo' => 'EOSINOFILOS %',
            'unidad_medida'=> '103/UL',
            'rango_superior' => 4.50,
            'rango_inferior' => 10.00,
            'configuracion_examen_categoria_id' =>'1',
        ],
        [
            'campo' => 'BASOFILOS %',
            'unidad_medida'=> '103/UL',
            'rango_superior' => 4.50,
            'rango_inferior' => 10.00,
            'configuracion_examen_categoria_id' =>'1',
        ],
        [
            'campo' => 'MONOCITOS %',
            'unidad_medida'=> '103/UL',
            'rango_superior' => 4.50,
            'rango_inferior' => 10.00,
            'configuracion_examen_categoria_id' =>'1',
        ],   
        [
            'campo' => 'N.SEGMENTACION',
            'unidad_medida'=> '103/UL',
            'rango_superior' => 4.50,
            'rango_inferior' => 10.00,
            'configuracion_examen_categoria_id' =>'1',
        ],
        [
            'campo' => 'LINFOCITOS',
            'unidad_medida'=> '103/UL',
            'rango_superior' => 4.50,
            'rango_inferior' => 10.00,
            'configuracion_examen_categoria_id' =>'1',
        ],
        [
            'campo' => 'CANTIDAD',
            'unidad_medida'=> 'ML',
            'rango_superior' => null,
            'rango_inferior' => null,
            'configuracion_examen_categoria_id' =>'2',
        ],
        [
            'campo' => 'ASPECTO',
            'unidad_medida'=> 'ML',
            'rango_superior' => null,
            'rango_inferior' => null,
            'configuracion_examen_categoria_id' =>'2',
        ],
        [
            'campo' => 'PH',
            'unidad_medida'=> 'ML',
            'rango_superior' => null,
            'rango_inferior' => null,
            'configuracion_examen_categoria_id' =>'2',
        ],
        [
            'campo' => 'DENSIDAD',
            'unidad_medida'=> 'ML',
            'rango_superior' => null,
            'rango_inferior' => null,
            'configuracion_examen_categoria_id' =>'2',
        ],
        [
            'campo' => 'PROTEINAS',
            'unidad_medida'=> 'ML',
            'rango_superior' => null,
            'rango_inferior' => null,
            'configuracion_examen_categoria_id' =>'3',
        ],
        [
            'campo' => 'NITRITOS',
            'unidad_medida'=> 'ML',
            'rango_superior' => null,
            'rango_inferior' => null,
            'configuracion_examen_categoria_id' =>'3',
        ],

        ]);
    }
}
 