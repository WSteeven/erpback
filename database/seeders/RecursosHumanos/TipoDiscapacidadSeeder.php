<?php

namespace Database\Seeders\RecursosHumanos\RecursosHumanos;

use App\Models\RecursosHumanos\TipoDiscapacidad;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoDiscapacidadSeeder extends Seeder
{
  /**
     * Run the database seeds.
     * php artisan db:seed --class="Database\Seeders\RecursosHumanos\TipoDiscapacidadSeeder"
     * @return void
     */
    public function run()
    {
        TipoDiscapacidad::insert([
            ['nombre' => 'Auditiva'],
            ['nombre' => 'Física'],
            ['nombre' => 'Intelectual'],
            ['nombre' => 'Lenguaje'],
            ['nombre' => 'Psicosocial '],
            ['nombre' => 'Visual'],
        ]);
    }
}
