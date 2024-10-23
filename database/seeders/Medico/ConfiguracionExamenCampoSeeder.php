<?php

namespace Database\Seeders\Medico;

use App\Models\Medico\ConfiguracionExamenCampo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConfiguracionExamenCampoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class="Database\Seeders\Medico\ConfiguracionExamenCampoSeeder"
     *
     * @return void
     */
    public function run()
    {
        ConfiguracionExamenCampo::insert([
            // GRUPO SANGUINEO
            [
                'campo' => 'GRUPO SANGUÍNEO',
                'unidad_medida' => null,
                'rango_superior' => null,
                'rango_inferior' => null,
                'configuracion_examen_categoria_id' => '1',
            ],
            [
                'campo' => 'FACTOR RH',
                'unidad_medida' => null,
                'rango_superior' => null,
                'rango_inferior' => null,
                'configuracion_examen_categoria_id' => '1',
            ],
            // BIOMETRIA HEMATICA COMPLETA
            [
                'campo' => 'LEUCOCITOS',
                'unidad_medida' => '103/UL',
                'rango_superior' => 10.00,
                'rango_inferior' => 4.5,
                'configuracion_examen_categoria_id' => '2',
            ],
            [
                'campo' => 'HEMATÓCRITO',
                'unidad_medida' => '%',
                'rango_superior' => 54.0,
                'rango_inferior' => 40.0,
                'configuracion_examen_categoria_id' => '2',
            ],
            [
                'campo' => 'HEMOGLOBINA',
                'unidad_medida' => 'g/dl',
                'rango_superior' => 18.0,
                'rango_inferior' => 13.0,
                'configuracion_examen_categoria_id' => '2',
            ],
            [
                'campo' => 'PLAQUETAS',
                'unidad_medida' => '103/UL',
                'rango_superior' => 450.0,
                'rango_inferior' => 150.0,
                'configuracion_examen_categoria_id' => '2',
            ],
            [
                'campo' => 'LINFOCITOS %',
                'unidad_medida' => '%',
                'rango_superior' => 48.0,
                'rango_inferior' => 21.0,
                'configuracion_examen_categoria_id' => '2',
            ],
            [
                'campo' => 'EOSINÓFILOS %',
                'unidad_medida' => '%',
                'rango_superior' => 3.9,
                'rango_inferior' => 0.1,
                'configuracion_examen_categoria_id' => '2',
            ],
            [
                'campo' => 'BASOFILOS %',
                'unidad_medida' => '%',
                'rango_superior' => 1.0,
                'rango_inferior' => 0.0,
                'configuracion_examen_categoria_id' => '2',
            ],
            [
                'campo' => 'MONOCITOS %',
                'unidad_medida' => '%',
                'rango_superior' => 8.0,
                'rango_inferior' => 2.0,
                'configuracion_examen_categoria_id' => '2',
            ],
            [
                'campo' => 'N.SEGMENTACION',
                'unidad_medida' => '103/UL',
                'rango_superior' => 7.0,
                'rango_inferior' => 1.5,
                'configuracion_examen_categoria_id' => '2',
            ],
            [
                'campo' => 'LINFOCITOS',
                'unidad_medida' => '103/UL',
                'rango_superior' => 3.7,
                'rango_inferior' => 1.0,
                'configuracion_examen_categoria_id' => '2',
            ],
            /**
             * Examen: Elemental y microscopico de orina
             * Categoria: Fisico
             */
            [
                'campo' => 'CANTIDAD',
                'unidad_medida' => 'ML',
                'rango_superior' => null,
                'rango_inferior' => null,
                'configuracion_examen_categoria_id' => '3',
            ],
            [
                'campo' => 'COLOR',
                'unidad_medida' => null,
                'rango_superior' => null,
                'rango_inferior' => null,
                'configuracion_examen_categoria_id' => '3',
            ],
            [
                'campo' => 'ASPECTO',
                'unidad_medida' => null,
                'rango_superior' => null,
                'rango_inferior' => null,
                'configuracion_examen_categoria_id' => '3',
            ],
            [
                'campo' => 'PH',
                'unidad_medida' => null,
                'rango_superior' => null,
                'rango_inferior' => null,
                'configuracion_examen_categoria_id' => '3',
            ],
            [
                'campo' => 'DENSIDAD',
                'unidad_medida' => null,
                'rango_superior' => null,
                'rango_inferior' => null,
                'configuracion_examen_categoria_id' => '3',
            ],
            // Categoria: Quimico
            [
                'campo' => 'PROTEINAS',
                'unidad_medida' => 'ML',
                'rango_superior' => null,
                'rango_inferior' => null,
                'configuracion_examen_categoria_id' => '4',
            ],
            [
                'campo' => 'NITRITOS',
                'unidad_medida' => null,
                'rango_superior' => null,
                'rango_inferior' => null,
                'configuracion_examen_categoria_id' => '4',
            ],
            [
                'campo' => 'UROBILINÓGENO',
                'unidad_medida' => null,
                'rango_superior' => null,
                'rango_inferior' => null,
                'configuracion_examen_categoria_id' => '4',
            ],
            [
                'campo' => 'BILIRRUBINAS',
                'unidad_medida' => null,
                'rango_superior' => null,
                'rango_inferior' => null,
                'configuracion_examen_categoria_id' => '4',
            ],
            [
                'campo' => 'C. CETÓNICOS',
                'unidad_medida' => null,
                'rango_superior' => null,
                'rango_inferior' => null,
                'configuracion_examen_categoria_id' => '4',
            ],
            // Microscopico
            [
                'campo' => 'HEMATIES',
                'unidad_medida' => null,
                'rango_superior' => null,
                'rango_inferior' => null,
                'configuracion_examen_categoria_id' => '5',
            ],
            [
                'campo' => 'CÉLULAS EPITELIALES BAJAS',
                'unidad_medida' => null,
                'rango_superior' => null,
                'rango_inferior' => null,
                'configuracion_examen_categoria_id' => '5',
            ],
            [
                'campo' => 'BACTERIAS',
                'unidad_medida' => null,
                'rango_superior' => null,
                'rango_inferior' => null,
                'configuracion_examen_categoria_id' => '5',
            ],
            [
                'campo' => 'LEUCOCITOS',
                'unidad_medida' => null,
                'rango_superior' => null,
                'rango_inferior' => null,
                'configuracion_examen_categoria_id' => '5',
            ],
            /**
             * Examen: Coproparasitario + coprologico
             * Categoria: Coproparasitario + coprologico
             */
            [
                'campo' => 'COLOR',
                'unidad_medida' => null,
                'rango_superior' => null,
                'rango_inferior' => null,
                'configuracion_examen_categoria_id' => '6',
            ],
            [
                'campo' => 'CONSISTENCIA',
                'unidad_medida' => null,
                'rango_superior' => null,
                'rango_inferior' => null,
                'configuracion_examen_categoria_id' => '6',
            ],
            [
                'campo' => 'pH',
                'unidad_medida' => null,
                'rango_superior' => null,
                'rango_inferior' => null,
                'configuracion_examen_categoria_id' => '6',
            ],
            [
                'campo' => 'RESTOS MACROSCÓPICOS',
                'unidad_medida' => null,
                'rango_superior' => null,
                'rango_inferior' => null,
                'configuracion_examen_categoria_id' => '6',
            ],
            [
                'campo' => 'MOCO',
                'unidad_medida' => null,
                'rango_superior' => null,
                'rango_inferior' => null,
                'configuracion_examen_categoria_id' => '6',
            ],
            [
                'campo' => 'SANGRE',
                'unidad_medida' => null,
                'rango_superior' => null,
                'rango_inferior' => null,
                'configuracion_examen_categoria_id' => '6',
            ],
            [
                'campo' => 'HEMATIES',
                'unidad_medida' => null,
                'rango_superior' => null,
                'rango_inferior' => null,
                'configuracion_examen_categoria_id' => '6',
            ],
            [
                'campo' => 'LEUCOCITOS',
                'unidad_medida' => null,
                'rango_superior' => null,
                'rango_inferior' => null,
                'configuracion_examen_categoria_id' => '6',
            ],
            [
                'campo' => 'PIOCITOS',
                'unidad_medida' => null,
                'rango_superior' => null,
                'rango_inferior' => null,
                'configuracion_examen_categoria_id' => '6',
            ],
            [
                'campo' => 'GLÓBULOS DE GRASA',
                'unidad_medida' => null,
                'rango_superior' => null,
                'rango_inferior' => null,
                'configuracion_examen_categoria_id' => '6',
            ],
            [
                'campo' => 'LEVADURAS',
                'unidad_medida' => null,
                'rango_superior' => null,
                'rango_inferior' => null,
                'configuracion_examen_categoria_id' => '6',
            ],
            [
                'campo' => 'FLORA INTESTINAL',
                'unidad_medida' => null,
                'rango_superior' => null,
                'rango_inferior' => null,
                'configuracion_examen_categoria_id' => '6',
            ],
            [
                'campo' => 'PARÁSITOS',
                'unidad_medida' => null,
                'rango_superior' => null,
                'rango_inferior' => null,
                'configuracion_examen_categoria_id' => '6',
            ],
            [
                'campo' => 'PMN',
                'unidad_medida' => 'cél/µL',
                'rango_superior' => null,
                'rango_inferior' => null,
                'configuracion_examen_categoria_id' => '6',
            ],
            /**
             * Examen: GLUCOSA
             */
            [
                'campo' => 'GLUCOSA BASAL',
                'unidad_medida' => 'mg/dl',
                'rango_superior' => 100,
                'rango_inferior' => 70,
                'configuracion_examen_categoria_id' => '7',
            ],
            [
                'campo' => 'COLESTEROL TOTAL',
                'unidad_medida' => 'mg/dl',
                'rango_superior' => 200,
                'rango_inferior' => 199,
                'configuracion_examen_categoria_id' => '8',
            ],
            [
                'campo' => 'HDL',
                'unidad_medida' => 'mg/dl',
                'rango_superior' => NULL,
                'rango_inferior' => 40,
                'configuracion_examen_categoria_id' => '8',
            ],
            [
                'campo' => 'LDL',
                'unidad_medida' => 'mg/dl',
                'rango_superior' => 100,
                'rango_inferior' => NULL,
                'configuracion_examen_categoria_id' => '8',
            ],
            [
                'campo' => 'TRIGLICÉRIDOS',
                'unidad_medida' => 'mg/dl',
                'rango_superior' => 150,
                'rango_inferior' => 149,
                'configuracion_examen_categoria_id' => '9',
            ],
            [
                'campo' => 'ÚREA',
                'unidad_medida' => 'mg/dl',
                'rango_superior' => 48,
                'rango_inferior' => 16.6,
                'configuracion_examen_categoria_id' => '10',
            ],
            [
                'campo' => 'CREATININA',
                'unidad_medida' => 'mg/dl',
                'rango_superior' => 1.2,
                'rango_inferior' => 0.5,
                'configuracion_examen_categoria_id' => '11',
            ],
            [
                'campo' => 'ÁCIDO ÚRICO',
                'unidad_medida' => 'mg/dl',
                'rango_superior' => 7.2,
                'rango_inferior' => 3.5,
                'configuracion_examen_categoria_id' => '12',
            ],
            /**
             * Examen: Enzimas
             */
            [
                'campo' => 'TGO/ASAT',
                'unidad_medida' => 'U/L',
                'rango_superior' => 40.0,
                'rango_inferior' => null,
                'configuracion_examen_categoria_id' => '13',
            ],
            [
                'campo' => 'TGO/ASAT',
                'unidad_medida' => 'U/L',
                'rango_superior' => 41.0,
                'rango_inferior' => null,
                'configuracion_examen_categoria_id' => '14',
            ],
            /**
             * Examenes especiales
             */
            [
                'campo' => 'Rx. AP y LATERAL DE COLUMNA LUMBAR',
                'unidad_medida' => null,
                'rango_superior' => null,
                'rango_inferior' => null,
                'configuracion_examen_categoria_id' => '15',
            ],
            [
                'campo' => 'ELECTROENCEFALOGRAMA',
                'unidad_medida' => null,
                'rango_superior' => null,
                'rango_inferior' => null,
                'configuracion_examen_categoria_id' => '16',
            ],
            [
                'campo' => 'ELECTROCARDIOGRAMA',
                'unidad_medida' => null,
                'rango_superior' => null,
                'rango_inferior' => null,
                'configuracion_examen_categoria_id' => '17',
            ],
            [
                'campo' => 'OPTOMETRIA',
                'unidad_medida' => null,
                'rango_superior' => null,
                'rango_inferior' => null,
                'configuracion_examen_categoria_id' => '18',
            ],
            [
                'campo' => 'AUDIOMETRIA',
                'unidad_medida' => null,
                'rango_superior' => null,
                'rango_inferior' => null,
                'configuracion_examen_categoria_id' => '19',
            ],
        ]);
    }
}
