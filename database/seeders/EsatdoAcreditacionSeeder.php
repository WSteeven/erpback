<?php

namespace Database\Seeders;

use App\Models\FondosRotativos\Saldo\EstadoAcreditaciones;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EsatdoAcreditacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EstadoAcreditaciones::create(['estado'=> 'MIGRACION']);
    }
}
