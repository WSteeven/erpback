<?php

namespace Database\Seeders;

use App\Models\RecursosHumanos\NominaPrestamos\Rubros;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RubroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       Rubros::insert([['nombre_rubro' => 'IESS (Empleado)','valor_rubro'=>'9.45','es_porcentaje'=>true],
       ['nombre_rubro' => 'Sueldo Basico','valor_rubro'=>'450','es_porcentaje'=>false],
       ['nombre_rubro' => 'Decimo Cuarto','valor_rubro'=>'0','es_porcentaje'=>false],
       ['nombre_rubro' => 'Porcentaje de Anticipo','valor_rubro'=>'40','es_porcentaje'=>true],
       ['nombre_rubro' => 'Fondo de Reserva','valor_rubro'=>'8.33','es_porcentaje'=>true],
       ['nombre_rubro' => 'Decimo Tercero','valor_rubro'=>'0','es_porcentaje'=>false],
       ['nombre_rubro' => 'Vacaciones','valor_rubro'=>'0','es_porcentaje'=>true],
       ['nombre_rubro' => 'Horas extras Diurnas','valor_rubro'=>'50','es_porcentaje'=>true],
       ['nombre_rubro' => 'Horas extras Nocturnas','valor_rubro'=>'100','es_porcentaje'=>true],
       ['nombre_rubro' => 'Horas extras Extraordinarias','valor_rubro'=>'100','es_porcentaje'=>true]]);
    }
}
